<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::displayed()
            ->with(['comments' => function ($query) {
                $query->where('is_display', true)->with('user');
            }])
            ->withCount('votes')
            ->paginate(10);

        return view('feedback.index', compact('feedbacks'));
    }

    public function create()
    {
        return view('feedback.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
        ]);

        Feedback::create($validatedData);

        return redirect()->route('feedback.index')->with('success', 'Feedback submitted successfully!');
    }

    public function show($id)
    {
        $feedback = Feedback::with('usersThroughComments')->find($id);
        $users = $feedback->usersThroughComments;

        return view('feedback.show', compact('feedback', 'users'));
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $this->middleware('admin');
        try {
            $feedback_id = $id;
            DB::beginTransaction();
            $feedback = Feedback::with('votes', 'comments')->find($feedback_id);

            if (!$feedback) {
                DB::rollBack();
                return response()->json(['error' => 'User not found'], 404);
            }
            $feedback->votes()->delete();
            $feedback->comments()->delete();
            $feedback->delete();
            DB::commit();

            return response()->json(['message' => 'Feedback and associated data deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Unable to delete Feedback and associated data'], 500);
        }
    }
}
