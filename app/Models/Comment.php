<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;

    public static function create($message, $left = 0, $right = 0, $level = 0): self
    {
        $comment = new self();
        $comment->message = $message;
        $comment->left = $left;
        $comment->right = $right;
        $comment->level = $level;

        return $comment;
    }

    public function changeMessage($message)
    {
        $this->message = $message;
    }
}