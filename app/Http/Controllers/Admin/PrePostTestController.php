<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrePost;
use App\Models\PrePostTest;
use App\Models\Question;
use Illuminate\Http\Request;

class PrePostTestController extends Controller
{
    public function index()
    {
        $preposts = PrePost::paginate(5);
        $title = 'Menghapus Pre atau Post Test!';
        $text = "Anda yakin ini menghapus Pre atau Post Test?";
        confirmDelete($title, $text);

        return view('admin.preposttest.index', compact('preposts'));
    }

    //? get Soal&Jawaban Pre atau Post Test
    public function getQuestion(Request $request)
    {
        try {

            $questions = Question::all();

            if (count($questions) > 0) {

                $data = [];
                $counter = 0;

                foreach ($questions as $question) {
                    $qnaPrePost = PrePostTest::where([
                        'pre_post_id' => $request->pre_post_id,
                        'soal_id' => $question->id
                    ])->get();

                    if (count($qnaPrePost) == 0) {
                        $data[$counter]['id'] = $question->id;
                        $data[$counter]['questions'] = $question->question;
                        $counter++;
                    }
                }

                return response()->json(['success' => true, 'msg' => 'Soal&Jawaban Pre atau Post Test berhasil ditampilkan!', 'data' => $data]);

            } else {
                return response()->json(['success' => false, 'msg' => 'Soal&Jawaban Pre atau Post Test tidak ditemukan']);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function addQuestion(Request $request)
    {
        try {

            if (isset($request->question_ids)) {

                foreach ($request->question_ids as $qid) {
                    PrePostTest::insert([
                        'pre_post_id' => $request->pre_post_id,
                        'soal_id' => $qid,
                    ]);
                }

            }

            return response()->json(['success' => true, 'msg' => 'Soal&Jawaban Pre atau Post Test berhasil ditambahkan']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getPrePostTestQuestion(Request $request)
    {
        try {

            $data = PrePostTest::where('pre_post_id',$request->pre_post_id)->with('question')->get();

            return response()->json(['success' => true, 'msg' => 'Details Soal&Jawaban Pre atau Post Test', 'data' => $data]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deletePrePostTestQuestion(Request $request)
    {
        try {

            PrePostTest::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Soal&Jawaban Pre atau Post Test berhasil dihapus']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
