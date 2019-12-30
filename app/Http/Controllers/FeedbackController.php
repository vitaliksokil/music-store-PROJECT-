<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Http\Traits\LikesTrait;
use App\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\Psr7\str;

class FeedbackController extends Controller
{
    use LikesTrait;

    public function create(Request $request)
    {
        $temp = $request['feedback'];
        $request['feedback'] = strip_tags($request['feedback']);
        $request->validate([
            'feedback' => 'required|min:10',
        ]);
        $request['feedback'] = $temp;
        try {
            $newFeedback = Feedback::create($request->all());
        } catch (QueryException $e) {
            abort(403, 'You have been already added feedback!!! Delete your feedback to create a new one!!!');
        }

        if ($newFeedback) {
            return response(['messageType' => 'success', 'message' => 'Your feedback was successfully added!!!']);
        } else {
            abort(400, 'Something went wrong!!!');
        }

    }

    public function isUserLeftFeedback(Request $request)
    {
        $feedback = Feedback::with('user')->where([
            ['product_id', '=', $request['product_id']],
            ['user_id', '=', $request['user_id']]
        ])->first();
        if ($feedback) {
            return response(['userFeedback' => $feedback]);
        } else {
            abort(400, 'false');
        }

    }

    public function update(Request $request)
    {
        $temp = $request['feedback'];
        $request['feedback'] = strip_tags($request['feedback']);
        $request->validate([
            'feedback' => 'required|min:10',
        ]);
        $request['feedback'] = $temp;

        $feedback = Feedback::where([
            ['id', $request['id']],
            ['user_id', Auth::user()->id]
        ])->first();
        if($feedback){
            $feedback->feedback = $request->feedback;
            $feedback->save();
            return response(['messageType' => 'success', 'message' => 'Your feedback was successfully updated!!!']);
        }else{
            abort(400, 'Something went wrong!!!');
        }
    }

    public function delete($id){
        $feedback = Feedback::where([
            ['id', $id],
            ['user_id', Auth::user()->id]
        ])->first();
        if($feedback->delete()){
            return response(['messageType' => 'success', 'message' => 'Your feedback was successfully deleted!!!']);
        }else{
            abort(400, 'Something went wrong!!!');
        }
    }
    public function getLikes($id){
        $feedback = Feedback::with('likes')->find($id);
        $this->divisionToLikesDislikes($feedback);
        return $feedback;
    }
}
