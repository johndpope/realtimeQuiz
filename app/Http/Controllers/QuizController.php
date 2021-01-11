<?php

namespace App\Http\Controllers;

use App\Category;
use App\Question;
use App\QuestionsOption;
use App\Quiz;
use App\QuizCategory;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function categorylist()
    {
        $quizcategory = QuizCategory::all();
        return view('Admin.PartialPages.Quiz.quiz_category', compact('quizcategory'));
    }

    public function storeCategory(Request $request)
    {
        //    return $request->all();
        $request->validate([
            'name' => 'required',
        ]);
        QuizCategory::create([
            'name' => $request->name
        ]);

        return redirect('quiz/categorylist');
    }

    public function updateCategory(Request $request)
    {
        QuizCategory::where('id', $request->id)->update([
            'name' => $request->name
        ]);
        return redirect('quiz/categorylist');
    }

    public function deleteCategory($id)
    {
        QuizCategory::where('id', $id)->delete();
        return 'Delete Successfully.';
    }

    public function create()
    {
        $question_topic = Category::all();
        $question_category = QuizCategory::all();
        return view('Admin.PartialPages.Quiz.quiz_create', compact(['question_topic', 'question_category']));
    }

    public function list()
    {
        $category = Category::all();
        return view('Admin.PartialPages.Quiz.quiz_list', compact('category'));
    }

    public function getQuestionsByTopic($id, $cid = '')
    {
        if ($cid) {
            $questions = QuizCategory::with(['questions' => function ($q) use ($id) {
                $q->where('category_id', $id);
            }])->where('id', $cid)->get();
        } else {
            $questions = QuizCategory::with(['questions' => function ($q) use ($id) {
                $q->where('category_id', $id);
            }])->get();
        }

        return view('Admin.PartialPages.Quiz.Partial.questions_list', compact('questions'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        if ($request->quizCreateType == 'qb') {
            $this->storeFromQB($request);
            return redirect('quiz/create');
        }
        $this->storeFromCustom($request);

        return redirect('quiz/create');
    }

    public function storeFromQB($request)
    {
        $questions = array();
        foreach ($request->questions as $q) {
            $questions[] = $q;
        }
        $questionsid = implode(',', $questions);
        Quiz::create([
            'quiz_name'         => $request->quizName,
            'questions'         => $questionsid,
            'category_id'       => $request->topic,
            'quiz_category_id'  => $request->category,
        ]);
    }
    public function storeFromCustom($request)
    {
        $questionId = array();
        foreach ($request->question as  $k => $qq) {
            $options = 'option' . $k;
            $answers = 'answer' . $k;
            $qid = Question::create([
                'question_text'         => $qq,
                'answer_explanation'    => $request->explenation[$k],
                'category_id'           =>  $request->topic[$k],
                'quizcategory_id'           =>  $request->category[$k],
            ])->id;
            $questionId[] = $qid;
            foreach ($request->$options as  $i => $o) {
                $data[$i]['question_id'] = $qid;
                $data[$i]['option'] = $o;
                $data[$i]['correct'] = $request->$answers[$i];
            }
            QuestionsOption::insert($data);
        }

        $questions = implode(',', $questionId);
        Quiz::create([
            'quiz_name'         => $request->quizName,
            'questions'         => $questions,
            'category_id'       => $request->topic,
            'quiz_category_id'  => $request->category,
            'custom_create'     => 1,
        ]);
    }
    public function quiz($id)
    {
        $q = Quiz::find($id);
        $Questions = Question::with('options')->whereIn('id', explode(",", $q->questions))->get();
        return view('Admin.PartialPages.Quiz.partial.questionwithOption', compact('Questions'));
    }

    public function quizList($id)
    {
        // return Quiz::with('quizCategory')->where('category_id', $id)->get();
        $quiz = QuizCategory::with(['quizzes' => function ($q) use ($id) {
            $q->where('category_id', $id);
        }])->get();
        return view('Admin.PartialPages.Quiz.Partial.quizzes_list', compact('quiz'));
    }

    public function getlistbytopic($topic)
    {
        return 'success';
    }
}