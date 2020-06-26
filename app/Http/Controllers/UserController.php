<?php

namespace App\Http\Controllers;
use App\User; 
use App\tokenModel as token;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register ( Request $request)
    {
            //first validate
        $data = $request->validate([
            'username'=>'required|max:55',
            'pseudo'=>'required|max:55',
            'email'=>'email|required',
            'password'=>'required'
        ]);

            
        $data['password'] = bcrypt($request->password);
            /*Verification si l'utilisateur n'existe pas*/
            if(!$verification = User::checkIfExist($request->email))
            {
                $user = User::create($data);
                return response()->json([

                    'message'=>'ok',
                    'user'=> $user], 201);
            }
            else
            {
                //utilisateur existe deja.
                return  response()->json([
                    'utilisateur existe'], 401);
            }                         
    }

    public function login (Request $request) {
        $login = $request->validate([
            'email'=>'email|required',
            'password'=>'required'
        ]);

        if(auth()->attempt($login))
        {
            $token = Str::random(30);
            $token = token::create([
                'code' => $token,
                'user_id' => User::getUser('email',$request->email)->id,
                'expired_at' => "2020-12-12",
            ]);

            return response()->json(['message'=>'ok', 'data' => $token], 201);
        }
        else 
        {
            return response(['error'=>'Invalid Unauthorised'], 401);
        }
    }
/**
 * Fonction pour supprimer un utilisateur en condition que tu sois connecté
 */
    public function delete(Request $request, $id)
    {  
       $token = str_replace('Bearer ','',$header = $request->header('Authorization'));

        if(!$token) {
            return response()->json(['Pas moyen de supprimer'], 401);
        }
         $auth = token::where('code', $token)->first();
         //$tokenUser = token::where('code', $token)->first();
        
         /*if($id == $auth->user_id)
         {    
             if(!$auth || $auth->id != $id) 
             {
            return response()->json(['Unauthorized'], 401);
         }*/
        if($id == $auth->user_id){


        if(!$user = User::getUser('id',$id)){
            return response([
                'message'=>'error pas de id',
            ], 401);
        }
        $auth->delete(); 
        $user->delete();

        return response([
            'message'=>'success',
        ], 201);
        
    }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
           'username'=>'required|max:55',
           'pseudo'=>'required|max:55',
           'email'=>'email|required',
           'password'=>'required',
           'token' => 'required|max:30|min:30'
        ]);
        $token = str_replace('Bearer ','',$header = $request->header('Authorization'));

        if(!$token) {
            return response()->json(['Pas moyen de supprimer'], 401);
        }
         $auth = token::where('code', $token)->first();
        // $auth = token::where("code", $request->token)->first();
        // if(!$auth || $auth->id != $id) {
        //     return response()->json(['Unauthorized'], 401);
        // }

        if($id == $auth->user_id){
        $user = User::getUser('id',$id);
         if($user)
         {
            $user->username = $request->username;
            $user->pseudo = $request->pseudo;
            $user->email = $request->email;
            $user->password = $request->password;
            $auth->save();
            $user->save();
            return response()->json([
                'message'=>'ok', 
                'data'=> $user], 200);
         } else {
             return response()->json(['error'], 401);
         }
        }
    }

    public function index(Request $request) {
        $request->validate([
            "pseudo" => 'nullable',
            "page" => 'nullable|integer',
            "perPage" => 'nullable|integer'
        ]);

        $users = User::select('id', 'username', 'pseudo', 'email');
        if ($request->pseudo) {
            $users->where('pseudo', $request->pseudo);
        }

        if ($request->perPage) {
            $users = $users->paginate($request->perPage);
            return response()->json([
                "message" => "OK",
                "data" => $users->items(),
                "pager" => [
                    "current" => $users->currentPage(),
                    "total" => $users->lastPage()
                ]
            ], 200); 
        } else {
            $users = $users->get();
            return response()->json([
                "message" => "OK",
                "data" => $users->items()
            ], 200); 
        }
    }

    public function getUserById(Request $request, $id) 
    {
        $user = User::getUser('id',$id);

         if($user)
         {
            return response()->json([
                'message'=>'ok', 
                'data'=> $user], 200);
         }
         else
         {
             return response()->json(['message'=>'Impossible de supprimer id introuvable'], 401);
         }
    }

    public function store(Request $request)
    {

    }
}