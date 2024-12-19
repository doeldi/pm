<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $report = Report::findOrFail($id);

        Comment::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return redirect()->route('report.show', $id)->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //edit comment
        $comment = Comment::findOrFail($id);
        $comment->comment = $request->comment;
        $comment->save();

        return redirect()->back()->with('success', 'Komentar berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete comment
        $comment = Comment::findOrFail($id);
        $comment->delete();
        
        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }
}
