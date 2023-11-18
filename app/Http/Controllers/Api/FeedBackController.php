<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FeedBackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::displayed()
            ->with(['comments' => function ($query) {
                $query->where('is_display', true)->with('user');
            }])
            ->withCount('votes')
            ->withCount('comments')
            ->get();
        $response = ResponseUtil::getResponseArray(FeedbackResource::collection($feedbacks));
        return response($response);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',

        ]);
        if ($validator->fails()) {
            $errors = array_map(function ($err) {
                return $err[0];
            }, $validator->errors()->toArray());

            $errors = implode(',', $errors);
            $response = ResponseUtil::getResponseArray(null, 102, $errors);
            return response()->json($response, 422);
        }

        $feedback = new Feedback();
        $feedback->title = $request->title;
        $feedback->description = $request->description;
        $feedback->category = $request->category;
        $feedback->save();

        $response = ResponseUtil::getResponseArray($feedback, 101, 'Feedback Create successfully');
        return response($response);
    }

    public function update(Request $request, Feedback $feedback)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',

        ]);
        if ($validator->fails()) {
            $errors = array_map(function ($err) {
                return $err[0];
            }, $validator->errors()->toArray());

            $errors = implode(',', $errors);
            $response = ResponseUtil::getResponseArray(null, 102, $errors);
            return response()->json($response, 422);
        }
        $feedback->title = $request->title;
        $feedback->description = $request->description;
        $feedback->category = $request->category;
        $feedback->save();

        $response = ResponseUtil::getResponseArray($feedback, 101, 'Feedback update successfully');
        return response($response);
    }

    public function destroy($id)
    {
        try {
            $feedback_id = $id;
            DB::beginTransaction();
            $feedback = Feedback::with('votes', 'comments')->find($feedback_id);

            if (!$feedback) {
                DB::rollBack();
                throw new GeneralException('Feedback not found');
            }
            $feedback->votes()->delete();
            $feedback->comments()->delete();
            $feedback->delete();
            DB::commit();

            $response = ResponseUtil::getResponseArray(null, 101, 'Feedback and associated data deleted successfully');
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new GeneralException('Unable to delete Feedback and associated data');
        }
    }
}
