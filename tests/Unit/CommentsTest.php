<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use DatabaseTransactions;

    /** @var $commentService CommentService */
    private $commentService;

    public function setUp()
    {
        parent::setUp();

        $this->commentService = $this->app->make('App\Services\CommentService');
    }

    public function testAddFirstLevelComment()
    {
        $message = 'new comment text';
        $parentId = 1;

        $oldComments = $this->commentService->getComments();

        $this->commentService->addComment($message, $parentId);

        $comments = $this->commentService->getComments();


        $this->assertEquals(count($oldComments) + 1, count($comments));
        $this->assertDatabaseHas('comments', ['message' => $message]);
    }

    public function testAddSecondLevelComment()
    {
        $firstLevel = factory(Comment::class, 'firstLevel')->create();

        $message = 'Reply to first level comment.';

        $this->commentService->addComment($message, $firstLevel->id);

        $comments = $this->commentService->getComments();

        $reply = $comments[0]['replies'][0]['comment'];

        $this->assertTrue(!empty($comments[0]['replies']));
        $this->assertEquals(3, $reply['left']);
        $this->assertEquals(4, $reply['right']);
        $this->assertEquals(2, $reply['level']);
        $this->assertEquals($message, $reply['message']);
    }

    public function testUpdateComment()
    {
        $firstLevel = factory(Comment::class, 'firstLevel')->create();
        $message = 'Updated message.';

        $this->commentService->updateComment($firstLevel->id, $message);

        $comment = $this->commentService->getCommentById($firstLevel->id);

        $this->assertEquals($firstLevel->id, $comment->id);
        $this->assertEquals($message, $comment->message);
    }

    public function testDeleteComments()
    {
        $firstLevel = factory(Comment::class, 'firstLevel')->create();

        $reply1 = $this->commentService->addComment('first reply', $firstLevel->id);
        $reply2 = $this->commentService->addComment('second reply', $firstLevel->id);
        $reply3 = $this->commentService->addComment('reply to second reply', $reply2['comment']['id']);

        $this->commentService->deleteComment($firstLevel->id);

        $this->assertDatabaseMissing('comments', ['id' => $firstLevel->id]);
        $this->assertDatabaseMissing('comments', ['id' => $reply1['comment']['id']]);
        $this->assertDatabaseMissing('comments', ['id' => $reply2['comment']['id']]);
        $this->assertDatabaseMissing('comments', ['id' => $reply3['comment']['id']]);
    }
}