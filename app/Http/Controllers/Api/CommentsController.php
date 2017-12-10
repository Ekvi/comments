<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function get()
    {
        return response()->json(['comments' => $this->commentService->getComments()]);
    }

    public function add(Request $request)
    {
        $comment = $this->commentService->addComment($request->get('message'), $request->get('parentId'));

        return response()->json(['comment' => $comment]);
    }

    public function update(Request $request, $id)
    {
        $this->commentService->updateComment($id, $request->get('message'));
    }

    public function delete($id)
    {
        $this->commentService->deleteComment($id);
    }
}