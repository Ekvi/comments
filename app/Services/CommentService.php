<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Transformers\CommentsTransformer;

class CommentService
{
    private $commentRepository;
    private $commentsTransformer;

    public function __construct(CommentRepository $commentRepository, CommentsTransformer $commentsTransformer)
    {
        $this->commentRepository = $commentRepository;
        $this->commentsTransformer = $commentsTransformer;
    }

    public function getComments()
    {
        $comments = $this->commentRepository->getAllOrderedComments();

        return $this->commentsTransformer->transform($comments);
    }

    public function addComment($message, $parentId)
    {
        if($parentId > 0) {
            $parent = $this->getCommentById($parentId);
        } else {
            $parent = $this->commentRepository->one(['level' => 0]);
        }

        $this->rebuildTreeBeforeSave($parent);
        $this->updateParentBranchBeforeSave($parent);

        $comment = Comment::create($message, $parent['right'], $parent['right'] + 1, $parent['level'] + 1);
        $this->commentRepository->save($comment);

        return ['comment' => $comment, 'replies' => []];
    }

    private function rebuildTreeBeforeSave($parent)
    {
        $where = [['left', '>', $parent['right']]];

        $comments = $this->commentRepository->all($where);

        foreach($comments as $comment) {
            $comment->left += 2;
            $comment->right += 2;
            $this->commentRepository->update($comment);
        }
    }

    private function updateParentBranchBeforeSave($parent)
    {
        $where = [
            ['right', '>=', $parent['right']],
            ['left', '<', $parent['right']],
        ];

        $comments = $this->commentRepository->all($where);

        foreach($comments as $comment) {
            $comment->right += 2;
            $this->commentRepository->update($comment);
        }
    }

    public function updateComment($id, $message)
    {
        /** @var $comment Comment*/
        $comment = $this->getCommentById($id);
        $comment->changeMessage($message);

        $this->commentRepository->update($comment);
    }

    public function deleteComment($id)
    {
        $comment = $this->getCommentById($id);

        $where = [
            ['left', '>=', $comment['left']],
            ['right', '<=', $comment['right']],
        ];

        $this->commentRepository->deleteAll($where);

        $this->updateParentBranchAfterDelete($comment);
        $this->rebuildTreeAfterDelete($comment);
    }

    public function getCommentById($id)
    {
        return $this->commentRepository->one(['id' => $id]);
    }

    private function updateParentBranchAfterDelete($parent)
    {
        $where = [
            ['right', '>', $parent['right']],
            ['left', '<', $parent['left']],
        ];

        $comments = $this->commentRepository->all($where);

        foreach($comments as $comment) {
            $comment->right = $comment->right - ($parent->right - $parent->left + 1);
            $this->commentRepository->update($comment);
        }
    }

    private function rebuildTreeAfterDelete($parent)
    {
        $where = [['left', '>', $parent['right']]];

        $comments = $this->commentRepository->all($where);

        foreach($comments as $comment) {
            $comment->left = $comment->left - ($parent->right - $parent->left + 1);
            $comment->right = $comment->right - ($parent->right - $parent->left + 1);
            $this->commentRepository->update($comment);
        }
    }
}