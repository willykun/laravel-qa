<?php

namespace App\Http\Controllers;
use App\Question;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Question $question)
    {
        if(request()->expectsJson()){
            return response()->json(null, 200);
        }
        $question->favorites()->attach(auth()->id());
    }

    public function destroy(Question $question)
    {
        if(request()->expectsJson()){
           return response()->json(null, 200); 
        }
        $question->favorites()->detach(auth()->id());
    }
}
