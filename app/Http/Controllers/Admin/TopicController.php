<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class TopicController extends Controller
{
    public function index()
    {
        $parentTopics = Topic::whereNull('parent_id')->with('children')->paginate(10);
        $childTopics = Topic::whereNotNull('parent_id')->with('parent')->get();

        $title = 'Menghapus Topic!';
        $text = "Anda yakin ingin menghapus Topic?";
        confirmDelete($title, $text);
        return view('admin.topic.index', ['parentTopics' => $parentTopics, 'childTopics' => $childTopics]);
    }

    public function create()
    {
        $topics = Topic::where('parent_id', null)->get();
        return view('admin.topic.create', compact('topics'));
    }

    public function store(TopicRequest $request)
    {
        $topic = Topic::create($request->validated());
        return redirect('admin/topic')->with('success', 'Topic berhasil ditambahkan.');
    }

    public function edit(Topic $topic)
    {
        return view('admin.topic.edit', compact('topic'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $topic->update($request->validated());
        return redirect()->route('admin.topic.index')->with('success', 'Topic updated successfully!');
    }

    public function destroy(Topic $topic)
    {
        // Cek apakah topic memiliki children
        if ($topic->children()->count() > 0) {
            // Rekursif hapus semua children
            foreach ($topic->children as $child) {
                $child->delete();
            }
        }
    
        // Hapus topic utama
        $topic->delete();
        alert()->success('Success', 'Topic berhasil dihapus.');
        return back();
    }
}