<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    public function createcomment( Request $request, $id)
    {
            //first validate
        $request->validate([
            'body'=>'required|max:55',
        ]);

        $token = Str::random(30);
        $video = Video::create([
            'name' => $request->name, 'duration' => $fileAnalysed['playtime_seconds'], 'user_id' => $id, 'source' => $fileName, 'created_at' => date("Y-m-d H:i:s"), 'view' => 0, 'enabled' => 1,
        ]);    
    }
}
