<?php

namespace App\Http\Controllers;

use GrahamCampbell\Binput\Facades\Binput;
use App\Facades\CommentRepository;
use App\Facades\PostRepository;
use App\Models\Comment;
use App\Models\Post;
use GrahamCampbell\Credentials\Facades\Credentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends AbstractController
{
    /**
     * Display a listing of the comments.
     *
     * @param int $postId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($postId)
    {
        $post = PostRepository::find($postId, ['id']);
        if (!$post) {
            Session::flash('error', trans('messages.comment.view_error'));

            return Response::json([
                'success' => false,
                'code'    => 404,
                'msg'     => trans('messages.comment.view_error'),
                'url'     => route('posts.index'),
            ], 404);
        }

        $comments = $post->comments()->get(['id', 'version']);

        $data = [];

        foreach ($comments as $comment) {
            $data[] = ['comment_id' => $comment->id, 'comment_ver' => $comment->version];
        }

        return Response::json(array_reverse($data));
    }

    /**
     * Store a new comment.
     *
     * @param int $postId
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($postId)
    {
        $input = array_merge(Binput::only('body'), [
            'user_id'  => Credentials::getuser()->id,
            'post_id'  => $postId,
            'version'  => 1,
            'approved' => false
        ]);

        if (CommentRepository::validate($input, array_keys($input))->fails()) {
            throw new BadRequestHttpException('Your comment was empty.');
        }

        $comment = CommentRepository::create($input);

        if (!config('app.moderation')) {
            $comment->approved = true;
            $comment->save();
        }

        $contents = view('posts.comment', [
            'comment' => $comment,
            'post' => Post::find($postId),
        ]);

        return Response::json([
            'success'    => true,
            'msg'        => trans('messages.comment.store_success'),
            'contents'   => $contents->render(),
            'comment_id' => $comment->id
        ], 201);
    }

    /**
     * Show the specified comment.
     *
     * @param int $postId
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($postId, $id)
    {
        $comment = CommentRepository::find($id);
        $this->checkComment($comment);

        $contents = view('posts.comment', [
            'comment' => $comment,
            'post_id' => $postId,
        ]);

        return Response::json([
            'contents'     => $contents->render(),
            'comment_text' => nl2br(e($comment->body)),
            'comment_id'   => $id,
            'comment_ver'  => $comment->version,
        ]);
    }

    /**
     * Update an existing comment.
     *
     * @param int $id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\ConflictHttpException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $body = Binput::input('body');

        if (!$body) {
            throw new BadRequestHttpException('Your comment was empty.');
        }

        $comment = CommentRepository::find($id);
        $this->checkComment($comment);

        $version = Binput::input('version');

        if (empty($version)) {
            throw new BadRequestHttpException('No version data was supplied.');
        }

        if ($version != $comment->version && $version) {
            throw new ConflictHttpException('The comment was modified by someone else.');
        }

        $version++;

        $comment->body = $body;
        $comment->version = $version;

        $comment->update();

        return Response::json([
            'success'      => true,
            'msg'          => trans('messages.comment.update_success'),
            'comment_text' => nl2br(e($comment->body)),
            'comment_id'   => $id,
            'comment_ver'  => $version,
        ]);
    }

    /**
     * Delete an existing comment.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $comment = CommentRepository::find($id);
        $this->checkComment($comment);

        $comment->delete();

        return Response::json([
            'success'    => true,
            'msg'        => trans('messages.comment.delete_success'),
            'comment_id' => $id,
        ]);
    }

    /**
     * Check the comment model.
     *
     * @param mixed $comment
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function checkComment($comment)
    {
        if (!$comment) {
            throw new NotFoundHttpException('Comment Not Found.');
        }
    }

    /**
     * Comment approved by moderator
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $comment = Comment::find($id);

        if ($comment) {
            $comment->approved = true;
            $comment->save();
        }

        return redirect()->back();
    }
}
