<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::notDisplayed()->with('user')->get();
        return view('admin.comments_approved', ['comments' => $comments]);
    }

    public function create()
    {
        return view('comments.create');
    }

    public function store(Request $request, $feedbackId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $user = Auth::user();

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->feedback_id = $feedbackId;
        $comment->content = $request->input('content');
        $comment->save();

        return redirect()->route('feedback.index')->with('success', 'Comment submitted successfully!');
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->middleware('admin');
        $comment = Comment::findOrFail($id);
        $comment->is_display = true;
        $comment->save();

        return response()->json(['message' => 'Comment update successfully']);
    }

    public function destroy($id)
    {
        $this->middleware('admin');
        try {
            $commentId = $id;
            DB::beginTransaction();
            $comment = Comment::find($commentId);

            if (!$comment) {
                DB::rollBack();
                return response()->json(['error' => 'comment not found'], 404);
            }
            $comment->delete();
            DB::commit();

            return response()->json(['message' => 'Comment deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Unable to delete Comment'], 500);
        }
    }

    protected function approvedComment($comment)
    {

        $comment->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Comment approval status updated successfully.');
    }
}
