<?php


namespace App\Http\Traits;


trait LikesTrait
{
    public function divisionToLikesDislikes(&$feedbacks){

        // chech if it is iterable array -> if yes, iterate each element of array
        // else work only with 1
        if(is_iterable ($feedbacks)){
            foreach($feedbacks as &$feedback){
                $this->iterateLikesAndDivide($feedback);
            }
        }else{
            $this->iterateLikesAndDivide($feedbacks);
        }
    }
    private function iterateLikesAndDivide(&$feedback){
        $likes = [];
        $dislikes = [];
        foreach ($feedback->likes as $like){
            if ($like->like == 1){
                $likes[] = $like;
            }else{
                $dislikes[] = $like;
            }
        }
        unset($feedback->likes);
        $feedback->likes = $likes;
        $feedback->dislikes = $dislikes;
        $likes = [];
        $dislikes = [];
    }
}
