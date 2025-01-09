<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionType;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::paginate(10);

        $title = 'Menghapus Pertanyaan!';
        $text = "Anda yakin ingin menghapus Pertanyaan?";
        confirmDelete($title, $text);

        return view('admin.question.index', compact('questions'));
    }

    public function create()
    {
        $questionTypes = QuestionType::all();
        return view('admin.question.create', compact('questionTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'question_type_id' => 'required|integer|exists:question_types,id',
            'media_url' => 'nullable|url',
            'media_type' => 'nullable|string|max:255',
            'is_active' => 'required|boolean'
        ]);
    
        $question = Question::create($validatedData);
        return redirect('admin/question')->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function show(Question $question)
    {
        return view('admin.question.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $questionTypes = QuestionType::all(); // Mendapatkan semua tipe pertanyaan
        return view('admin.question.edit', compact('question', 'questionTypes'));
    }


    public function update(Request $request, Question $question)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'question_type_id' => 'required|integer|exists:question_types,id',
            'media_url' => 'nullable|url',
            'media_type' => 'nullable|string|max:255',
            'is_active' => 'required|boolean'
        ]);
    
        $question->update($validatedData);
        return redirect('admin/question')->with('message', 'Pertanyaan berhasil diupdate.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        alert()->success('Pertanyaan Berhasil Dihapus.');
        return back();
    }

    public function editOptions(Question $question)
    {
        return view('admin.questions.options', compact('question'));
    }

    public function storeOption(Request $request, Question $question)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
            'media_url' => 'nullable|url',
            'media_type' => 'nullable|string|max:255',
        ]);

        $question->options()->create($validatedData);
        return back()->with('message', 'Option added successfully.');
    }

    public function updateOption(Request $request, QuestionOption $option)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
            'media_url' => 'nullable|url',
            'media_type' => 'nullable|string|max:255',
        ]);

        $option->update($validatedData);
        return back()->with('message', 'Option updated successfully.');
    }

    public function destroyOption(QuestionOption $option)
    {
        $option->delete();
        return back()->with('message', 'Option deleted successfully.');
    }

}