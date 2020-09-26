<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;


class QuestionController extends Controller
{
    public function __construct(){
        $this->middleware('auth', ['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // \DB::enableQueryLog();
        // $questions = Question::with('user')->latest()->paginate(5);
        // view('questions.index', compact('questions'))->render();
        // dd(\DB::getQueryLog());

        $questions = Question::with('user')->latest()->paginate(10);
        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question();
        return view('questions.create', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title','body'));
        return redirect()->route('question.index')->with('success', 'your question has been submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        // $question->views = $question->views + 1;
        // $question->save();
        $question->increment('views');
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {   
        // //using gates
        // if(\Gate::allows('update-question', $question))
        // {
        //   return view("questions.edit", compact('question'));
        // }else{
        //     abort(403,"Access denied");
        // }

        //using policies
        $this->authorize('update', $question);
        return view("questions.edit", compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        $this->authorize('update', $question);
        $question->update($request->only('title','body'));

        if($request->expectsJson())
        {
            return response()->json([
                'message' => "Your question has been updated",
                'body_html' => $question->body_html
            ]);
        }
        return redirect('/question')->with('success', "Your question has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        // if(\Gate::denies('delete-question', $question)){
        //     abort(403, 'Access Denied');
        // }
        // $question->delete();
        // return redirect('/question')->with('success', "Your question has been deleted");

        // using policies
        $this->authorize('delete', $question);
        $question->delete();

        if(request()->expectsJson())
        {
            return response()->json([
                'message' => "Your question has been deleted"
            ]);
        }

        return redirect('/question')->with('success', "Your question has been deleted");
    }
}
