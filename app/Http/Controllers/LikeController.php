<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Request $request){
        $user_id = Auth::user()->id;
        $like = Like::where([
            ['feedback_id', $request['feedback_id']],
            ['user_id', $user_id]
        ])->first();
        if($like){
            // x - value on server(right), y - value from user(down), (x,y) - new value
            $states_matrix = [
                [function() use ($like){$like->delete();},$request['like']],
                [$request['like'],function() use ($like){$like->delete();}],
            ];
            $newValue = $states_matrix[$request['like']][$like->like];
            if(is_callable($newValue)){
                // deleted
                $newValue();
                return response(['like'=>0]);
            }else{
                $like->like = $newValue;
                $like->save();
            }
        }else{
            $request['user_id'] = $user_id;
            $like = Like::create($request->all());
        }
        return response(['like'=>$like]);

    }
}
