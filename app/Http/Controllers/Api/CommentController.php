<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::notDisplayed()->with(['user', 'feedback'])->get();
        $response = ResponseUtil::getResponseArray(CommentResource::collection($comments));
        return response($response);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'feedbackId' => 'required|gt:0|exists:feedbacks,id',
        ]);
        if ($validator->fails()) {
            $errors = array_map(function ($err) {
                return $err[0];
            }, $validator->errors()->toArray());

            $errors = implode(',', $errors);
            $response = ResponseUtil::getResponseArray(null, 102, $errors);
            return response()->json($response, 422);
        }

        $user = Auth::user();

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->feedback_id = $request->feedbackId;
        $comment->content = $request->content;
        $comment->save();

        $response = ResponseUtil::getResponseArray($comment, 101, 'Comment Create successfully it will show after admin approved');
        return response($response);
    }

    public function update(Request $request, Comment $comment)
    {

        if ($request->tab === 'updateDisplay') {
            $comment->is_display = true;
            $comment->save();
        }

        $response = ResponseUtil::getResponseArray($comment, 101, 'Comment Approved successfully');
        return response($response);
    }

    public function destroy($id)
    {
        try {
            $commentId = $id;
            DB::beginTransaction();
            $comment = Comment::find($commentId);

            if (!$comment) {
                DB::rollBack();
                throw new GeneralException('comment not found');
            }
            $comment->delete();
            DB::commit();
            $response = ResponseUtil::getResponseArray(null, 101, 'Comment deleted successfully');
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new GeneralException('Unable to delete Comment');
        }
    }
}
