<?php

namespace App\Http\Controllers;

use App\User; 
use App\videoModel as video; 
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function create(Request $request, $id) {
        
         $request->validate([
           'name' => 'required',
           'source' => 'required|file|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi',
           ]);

        $file = $request->file('source');
        $getID3 = new getID3;
        $fileAnalysed = $getID3->analyze($request->source);
        $extention = $request->file('source')->getClientOriginalExtension();
        $fileName = Str::random(10) . '.' . $extention;
        while (Storage::disk('videos')->exists($fileName)) {
            $fileName = Str::random(10) . '.' . $extention;
        }
        $request->file('source')->move(storage_path('app/videos'), Str::random(10) . '.' . $extention);
        $video = Video::create([
            'name' => $request->name, 'duration' => $fileAnalysed['playtime_seconds'], 'user_id' => $id, 'source' => $fileName, 'created_at' => date("Y-m-d H:i:s"), 'view' => 0, 'enabled' => 1,
        ]);

        return response()->json([
            'message' => 'created',
            'data' => $video
        ]);
    }

    
    public function videoindexbyid(Request $request) {
        $request->validate([

            'name' => 'nullable',
	        'user' => 'nullable|integer',
	        'duration' => 'nullable|integer',
            'page' => 'nullable|integer',
            'perPage' => 'nullable|integer'
        ]);

        $videos = Video::select('id', 'name', 'duration', 'user_id');
        if ($request->name) {
            $videos->where('name', $request->name);
        }

        if ($request->perPage) {
            $videos = $videos->paginate($request->perPage);
            return response()->json([
                "message" => "OK",
                "data" => $videos->items(),
                "pager" => [
                    "current" => $videos->currentPage(),
                    "total" => $videos->lastPage()
                ]
            ], 200); 
        } else {
            $videos = $videos->get();
            return response()->json([
                "message" => "OK",
                "data" => $videos->items()
            ], 200); 
        }
    }
    
    public function getVideoById(Request $request, $id) 
    {
        $user = Video::getVideo('id',$id);

         if($video)
         {
            return response()->json([
                'message'=>'ok', 
                'data'=> $video], 200);
         }
         else
         {
             return response()->json(['message'=>'Impossible de supprimer id introuvable'], 401);
         }
    }

}
