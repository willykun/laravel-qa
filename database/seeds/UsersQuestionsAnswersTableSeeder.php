<?php

use Illuminate\Database\Seeder;

class UsersQuestionsAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('answers')->delete();
        \DB::table('questions')->delete();
        \DB::table('users')->delete();

        factory('App\User', 2)->create()->each(function ($u){
            $u->questions()
               ->saveMany(
                   factory('App\Question', rand(1, 10))->make()
               )->each(function($q){
                    $q->answers()->saveMany(factory('App\Answer', rand(1,7))->make());
               });
        });
    }
}
