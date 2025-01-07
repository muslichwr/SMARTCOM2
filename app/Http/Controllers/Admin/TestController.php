<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Latihan;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $latihans = Latihan::paginate(5);
        $title = 'Menghapus Test!';
        $text = "Anda yakin ingin menghapus test?";
        confirmDelete($title, $text);

        return view('admin.test.index', compact('latihans'));
    }

    //? get Soal&Jawaban
    public function getQuestion(Request $request)
    {
        try {

            $questions = Question::all();

            if (count($questions) > 0) {

                $data = [];
                $counter = 0;

                foreach ($questions as $question) {
                    $qnaExam = Test::where([
                        'latihan_id' => $request->latihan_id,
                        'soal_id' => $question->id
                    ])->get();

                    if (count($qnaExam) == 0) {
                        $data[$counter]['id'] = $question->id;
                        $data[$counter]['questions'] = $question->question;
                        $counter++;
                    }

                    // dd($question);

                }
                return response()->json(['success' => true, 'msg' => 'Soal&Jawaban berhasil ditampilkan!', 'data' => $data]);

            } else {
                return response()->json(['success' => false, 'msg' => 'Soal&Jawaban tidak ditemukan!']);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function addQuestion(Request $request)
    {
        try {

            if(isset($request->question_ids)) {

                foreach($request->question_ids as $qid) {
                    Test::insert([
                        'latihan_id' => $request->latihan_id,
                        'soal_id' => $qid
                    ]);
                }

            }

            return response()->json(['success' => true, 'msg' => 'Soal&Jawaban berhasil ditambahkan!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getTestQuestion(Request $request)
    {
        try {

            $data = Test::where('latihan_id', $request->latihan_id)->with('question')->get();

            return response()->json(['success' => true, 'msg' => 'Details Soal&Jawaban!', 'data' => $data]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteTestQuestion(Request $request)
    {
        try {

            Test::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Soal&Jawaban berhasil Dihapus!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
