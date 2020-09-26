<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use VotableTrait;
    
    protected $fillable = ['body', 'user_id'];

    protected $appends = ['created_date', 'body_html', 'is_best'];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getBodyHtmlAttribute()
    {
        return clean(\Parsedown::instance()->text($this->body));
    }

    public static function boot()
    {
        parent::boot(); //ejecuta codigo cuando una respuesta es creada

        static::created(function($answer){ //$answer the current model instance
            $answer->question->increment('answers_count');
            //$answer->question->save();
        });

        static::deleted(function($answer){

            //second way to delete
            $answer->question->decrement('answers_count');
            //first way to delete
            //$question = $answer->question;
            
            //first way to delete
            // if($question->best_answer_id === $answer->id)
            // {
            //     $question->best_answer_id = NULL;
            //     $question->save();
            // }

            //$answer->question->decrement('answers_count');
        });

        // static::saved(function($answer){
        //     echo "answer saved\n";
        // });

    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        return $this->isBest() ? 'vote-accepted' : '';
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function isBest()
    {
        return $this->id === $this->question->best_answer_id;
    }
}
