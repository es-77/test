<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
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
