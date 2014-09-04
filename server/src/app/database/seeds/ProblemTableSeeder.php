<?php

class ProblemTableSeeder extends Seeder {

  public function run()
  {

    DB::table('problems')->delete();
    DB::table('problem_type')->delete();

    DB::table('problem_type')->insert(array('id'=>'1', 'type'=>'Normal', 'max_difficulty'=>3));
    
    DB::table('problems')->insert(
      array(
        array('id'=>1,'question'=>'3+3','answer'=>'6', 'difficulty'=>'1', 'problem_type_id'=>'1'),
        array('id'=>2,'question'=>'3+5','answer'=>'8', 'difficulty'=>'1', 'problem_type_id'=>'1'),
        array('id'=>3,'question'=>'2+1','answer'=>'3', 'difficulty'=>'2', 'problem_type_id'=>'1'),
        array('id'=>4,'question'=>'8+9','answer'=>'17', 'difficulty'=>'3', 'problem_type_id'=>'1'),
        array('id'=>5,'question'=>'3+3','answer'=>'6', 'difficulty'=>'1', 'problem_type_id'=>'1'),
        array('id'=>6,'question'=>'3+5','answer'=>'8', 'difficulty'=>'1', 'problem_type_id'=>'1'),
        array('id'=>7,'question'=>'2+1','answer'=>'3', 'difficulty'=>'2', 'problem_type_id'=>'1'),
        array('id'=>8,'question'=>'8+9','answer'=>'17', 'difficulty'=>'3', 'problem_type_id'=>'1')
        )
    );

    // $players = [
    //   new Game(["uuid" => "290sdk230230", 'address' => "127.0.0.1", "started"=> "2014-01-01 00:00:00", 'ended'=>'2014-01-01 00:00:00'])
    // ];
    

    
    

    




    
  }

}