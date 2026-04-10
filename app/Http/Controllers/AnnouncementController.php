<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $data = Announcement::latest()->get();

        return view('pengumuman', compact('data'));
    }

    public function store(Request $request)
    {
        Announcement::create([
            'title' => $request->title,
            'body' => $request->body
        ]);

        return back();
    }

    public function destroy($id)
{
    Announcement::findOrFail($id)->delete();
    return back();
}

    public function update(Request $request, $id)
    {
        $data = Announcement::findOrFail($id);

        $data->update([
            'title' => $request->title,
            'body' => $request->body
        ]);

        return back();
    }
}