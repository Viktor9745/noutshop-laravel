<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'content' => 'required',
            'item_id' => 'required|numeric|exists:items,id',
        ]);
        Auth::user()->reviews()->create($validated);
        return back()->with('message',  __('messages.review_create') );
    }

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);
        $review->update([
            'content'=>$request->input('content'),
            'item_id' => $request->item_id,

        ]);

        return back()->with('message', __('messages.review_update'));
    }
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return back()->with('message', __('messages.review_delete'));
    }
}
