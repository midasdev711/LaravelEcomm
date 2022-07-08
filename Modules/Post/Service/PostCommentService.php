<?php

namespace Modules\Post\Service;

use App\Notifications\StatusNotification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Modules\Post\Models\Post;
use Modules\Post\Models\PostComment;
use Modules\Post\Repository\PostCommentRepository;
use Modules\User\Models\User;

class PostCommentService
{
    private PostCommentRepository $post_comment_repository;
    
    public function __construct(PostCommentRepository $post_comment_repository)
    {
        $this->post_comment_repository = $post_comment_repository;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $post_info       = Post::getPostBySlug($request->slug);
        $data            = $request->all();
        $data['user_id'] = $request->user()->id;
        $data['status']  = 'active';
        $status          = PostComment::create($data);
        $details         = [
            'title'     => "New Comment created",
            'actionURL' => route('blog.detail', $post_info->slug),
            'fas'       => 'fas fa-comment',
        ];
        Notification::send(User::role('super-admin')->get(), new StatusNotification($details));
        if ($status) {
            request()->session()->flash('success', 'Thank you for your comment');
        } else {
            request()->session()->flash('error', 'Something went wrong! Please try again!!');
        }
        
        return redirect()->back();
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  PostComment  $postComment
     *
     * @return Application|Factory|View
     */
    public function edit(PostComment $postComment): View|Factory|Application
    {
        return view('backend.comment.edit', compact('postComment'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  PostComment  $postComment
     *
     * @return RedirectResponse
     */
    public function update(Request $request, PostComment $postComment): RedirectResponse
    {
        $status = $postComment->update($request->validated());
        if ($status) {
            request()->session()->flash('success', 'Comment successfully updated');
        } else {
            request()->session()->flash('error', 'Something went wrong! Please try again!!');
        }
        
        return redirect()->route('comment.index');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  PostComment  $postComment
     *
     * @return RedirectResponse
     */
    public function destroy(PostComment $postComment): RedirectResponse
    {
        $status = $postComment->delete();
        if ($status) {
            request()->session()->flash('success', 'Post Comment successfully deleted');
        } else {
            request()->session()->flash('error', 'Error occurred please try again');
        }
        
        return back();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $comments = $this->post_comment_repository->getAllComments();
        
        return view('backend.comment.index', compact('comments'));
    }
}