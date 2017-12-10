<?php

namespace App\Transformers;

class CommentsTransformer
{
    public function transform($data)
    {
        if(empty($data)) {
            return [];
        }

        $comments = [];
        foreach($data as $item) {
            if($item['level'] == 1) {
                $replies = $this->getReplies($data, $item['level'] + 1, $item['left'], $item['right']);
                $comments[] = ['comment' => $item, 'replies' => $replies];
            }
        }

        return $comments;
    }

    private function getReplies($comments, $level, $left, $right)
    {
        $children = [];
        for($i = 0; $i < count($comments); $i++) {
            if($comments[$i]['level'] == $level) {
                if($comments[$i]['left'] > $left && $comments[$i]['right'] < $right) {
                    $replies = $this->getReplies(
                        $comments, $comments[$i]['level'] + 1, $comments[$i]['left'], $comments[$i]['right']);

                    $children[] = ['comment' => $comments[$i], 'replies' => !empty($replies) ? $replies : []];
                }
            }
        }

        return $children;
    }
}