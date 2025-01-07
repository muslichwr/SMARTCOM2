<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class SoalJawabanController extends Controller
{
    public function index()
    {
        $soals = Question::paginate(5);
        // $jawabans = Answer::all();

        $title = 'Menghapus Soal & Jawaban!';
        $text = "Anda yakin ingin menghapus soal & jawaban ini?";
        confirmDelete($title, $text);

        return view('admin.soaljawaban.index', compact('soals'));
    }

    public function create()
    {
        return view('admin.soaljawaban.create');
    }

    public function store(Request $request)
    {
        $questionId = Question::insertGetId([
            'question' => $request->question
        ]);

        Answer::insert([
            'soal_id' => $questionId,
            'answer' => strtolower($request->answer),
        ]);

        return redirect('/admin/soal-jawaban')->with('message', 'Soal & Jawaban Berhasil Ditambahkan.');
    }

    public function edit(int $soal_id)
    {
        $soal = Question::findOrFail($soal_id);
        $jawabans = Answer::all();

        return view('admin.soaljawaban.edit', compact('soal', 'jawabans'));
    }

    public function update(Request $request, $soal_id)
    {
        $question = Question::findOrFail($soal_id);

        if ($request->has('question')) {
            $question->question = $request->question;
            $question->save();
        }

        if ($request->has('answer')) {
            $answer = Answer::where('soal_id', $soal_id)->first();

            if ($answer) {
                $answer->answer = strtolower($request->answer);
                $answer->save();
            } else {
                Answer::create([
                    'soal_id' => $soal_id,
                    'answer' => strtolower($request->answer),
                ]);
            }
        }

        return redirect('/admin/soal-jawaban')->with('message', 'Soal & Jawaban Berhasil Diupdate.');
    }
}
