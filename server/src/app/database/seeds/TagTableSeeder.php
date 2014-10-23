<?php

class TagTableSeeder extends Seeder {

  public function run()
  {
    DB::table('problem_tag')->delete();
    DB::table('tags')->delete();
    
    DB::table('tags')->insert(
        array('id'=>1, 'name'=>'Suma'),
        array('id'=>2, 'name'=>'Resta'),
        array('id'=>3, 'name'=>'Multiplicación'),
        array('id'=>4, 'name'=>'División')
        )

    
    );

  }

}