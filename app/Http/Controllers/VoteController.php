<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        // Ensure the user is authenticated
        if (Auth::check()) {
            // Check if the user has already voted for this feedback
            $user = Auth::user();
            $feedback = Feedback::findOrFail($id);

            if (!$user->votes()->where('feedback_id', $feedback->id)->exists()) {
                // User hasn't voted for this feedback, create a vote
                $vote = new Vote();
                $vote->user_id = $user->id;
                $vote->feedback_id = $feedback->id;
                $vote->save();

                return redirect()->route('feedback.index')->with('success', 'Vote submitted successfully!');
            }

            return redirect()->route('feedback.index')->with('error', 'You have already voted for this feedback.');
        }

        return redirect()->route('feedback.index')->with('error', 'You must be logged in to vote.');
    }
}
