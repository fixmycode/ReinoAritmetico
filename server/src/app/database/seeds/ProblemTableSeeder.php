<?php

class ProblemTableSeeder extends Seeder {

  public function run()
  {

    DB::table('problems')->delete();


    DB::table('problems')->insert(
      array(
        array('id'=>1,'problem'=>'3+3','correct_answer'=>'6',   'difficulty'=>'1'),
        array('id'=>2,'problem'=>'3+5','correct_answer'=>'8',   'difficulty'=>'1'),
        array('id'=>3,'problem'=>'2+1','correct_answer'=>'3',   'difficulty'=>'1'),
        array('id'=>4,'problem'=>'8+9','correct_answer'=>'17',  'difficulty'=>'1'),
        array('id'=>5,'problem'=>'3+3','correct_answer'=>'6',   'difficulty'=>'1'),
        array('id'=>6,'problem'=>'3+5','correct_answer'=>'8',   'difficulty'=>'1'),
        array('id'=>24,'problem'=>'2+1','correct_answer'=>'3',   'difficulty'=>'1'),
        array('id'=>25,'problem'=>'1+1','correct_answer'=>'2',   'difficulty'=>'1'),
        array('id'=>26,'problem'=>'7+0','correct_answer'=>'7',   'difficulty'=>'1'),
        array('id'=>27,'problem'=>'3*0','correct_answer'=>'0',   'difficulty'=>'1'),
        array('id'=>28,'problem'=>'2/1','correct_answer'=>'2',   'difficulty'=>'1'),
        array('id'=>29,'problem'=>'5/1','correct_answer'=>'5',   'difficulty'=>'1'),
        array('id'=>30,'problem'=>'6*0','correct_answer'=>'0',   'difficulty'=>'1'),
        array('id'=>31,'problem'=>'5/1','correct_answer'=>'5',   'difficulty'=>'1'),
        array('id'=>32,'problem'=>'8/1','correct_answer'=>'8',   'difficulty'=>'1'),
        array('id'=>33,'problem'=>'9/1','correct_answer'=>'9',   'difficulty'=>'1'),
        array('id'=>34,'problem'=>'10/1','correct_answer'=>'10', 'difficulty'=>'1'),
        array('id'=>35,'problem'=>'2+1','correct_answer'=>'3',   'difficulty'=>'1'),
        array('id'=>36,'problem'=>'6-3','correct_answer'=>'3',   'difficulty'=>'1'),
        array('id'=>37,'problem'=>'2+1','correct_answer'=>'3',   'difficulty'=>'1'),
        array('id'=>38,'problem'=>'3-1','correct_answer'=>'2',   'difficulty'=>'1'),
        array('id'=>39,'problem'=>'4-2','correct_answer'=>'2',   'difficulty'=>'1'),
        array('id'=>40,'problem'=>'8-2','correct_answer'=>'6',   'difficulty'=>'1'),
        array('id'=>41,'problem'=>'2*2','correct_answer'=>'4',   'difficulty'=>'1'),
        array('id'=>42,'problem'=>'1*2','correct_answer'=>'2',   'difficulty'=>'1'),
        array('id'=>43,'problem'=>'8+9','correct_answer'=>'17',  'difficulty'=>'1'),

        array('id'=>45,'problem'=>'13+21','correct_answer'=>'34','difficulty'=>'2'),
        array('id'=>46,'problem'=>'11-3', 'correct_answer'=>'8', 'difficulty'=>'2'),
        array('id'=>47,'problem'=>'11-4', 'correct_answer'=>'7',  'difficulty'=>'2'),
        array('id'=>48,'problem'=>'10-2', 'correct_answer'=>'8',  'difficulty'=>'2'),
        array('id'=>49,'problem'=>'13-4', 'correct_answer'=>'9',  'difficulty'=>'2'),
        array('id'=>50,'problem'=>'13-6', 'correct_answer'=>'7',  'difficulty'=>'2'),
        array('id'=>51,'problem'=>'2*4',  'correct_answer'=>'8',  'difficulty'=>'2'),
        array('id'=>52,'problem'=>'2*5',  'correct_answer'=>'10',  'difficulty'=>'2'),
        array('id'=>53,'problem'=>'6*2',  'correct_answer'=>'12',  'difficulty'=>'2'),
        array('id'=>54,'problem'=>'7*2',  'correct_answer'=>'14',  'difficulty'=>'2'),
        array('id'=>55,'problem'=>'2*8',  'correct_answer'=>'16',  'difficulty'=>'2'),
        array('id'=>56,'problem'=>'2*9',  'correct_answer'=>'18',  'difficulty'=>'2'),
        array('id'=>57,'problem'=>'10*2', 'correct_answer'=>'20',  'difficulty'=>'2'),
        array('id'=>58,'problem'=>'2*3',  'correct_answer'=>'6',  'difficulty'=>'2'),
        array('id'=>59,'problem'=>'3*3',  'correct_answer'=>'9',  'difficulty'=>'2'),
        array('id'=>60,'problem'=>'8-3',  'correct_answer'=>'5',  'difficulty'=>'2'),
        array('id'=>61,'problem'=>'10/2',  'correct_answer'=>'5',  'difficulty'=>'2'),
        array('id'=>62,'problem'=>'6/2',  'correct_answer'=>'3',  'difficulty'=>'2'),
        array('id'=>63,'problem'=>'8/4',  'correct_answer'=>'2',  'difficulty'=>'2'),
        array('id'=>64,'problem'=>'9/3',  'correct_answer'=>'3',  'difficulty'=>'2'),
        array('id'=>65,'problem'=>'10/5',  'correct_answer'=>'2',  'difficulty'=>'2'),
        array('id'=>66,'problem'=>'8/8',  'correct_answer'=>'1',  'difficulty'=>'2'),
        array('id'=>67,'problem'=>'15+13',  'correct_answer'=>'28',  'difficulty'=>'2'),
        array('id'=>68,'problem'=>'14-4',  'correct_answer'=>'10',  'difficulty'=>'2'),
        array('id'=>69,'problem'=>'13-7',  'correct_answer'=>'6',  'difficulty'=>'2'),
        array('id'=>70,'problem'=>'3*4',  'correct_answer'=>'12',  'difficulty'=>'2'),
        array('id'=>71,'problem'=>'4*5',  'correct_answer'=>'20',  'difficulty'=>'2'),
        array('id'=>72,'problem'=>'3*6',  'correct_answer'=>'18',  'difficulty'=>'2'),

        array('id'=>11,'problem'=>'6*8','correct_answer'=>'48', 'difficulty'=>'3'),
        array('id'=>12,'problem'=>'3*2','correct_answer'=>'6',  'difficulty'=>'3'),
        array('id'=>13,'problem'=>'5*3','correct_answer'=>'15', 'difficulty'=>'3'),
        array('id'=>14,'problem'=>'7*9','correct_answer'=>'63', 'difficulty'=>'3'),
        array('id'=>15,'problem'=>'50/10','correct_answer'=>'5',  'difficulty'=>'3'),
        array('id'=>16,'problem'=>'81/9','correct_answer'=>'9', 'difficulty'=>'3'),
        array('id'=>17,'problem'=>'8*8','correct_answer'=>'64', 'difficulty'=>'3'),
        array('id'=>18,'problem'=>'6*1','correct_answer'=>'6',  'difficulty'=>'3'),
        array('id'=>19,'problem'=>'21/3','correct_answer'=>'7', 'difficulty'=>'3'),
        array('id'=>20,'problem'=>'4*8','correct_answer'=>'32', 'difficulty'=>'3'),
        array('id'=>21,'problem'=>'6*7','correct_answer'=>'42', 'difficulty'=>'3'),
        array('id'=>22,'problem'=>'9*4','correct_answer'=>'32', 'difficulty'=>'3'),
        array('id'=>23,'problem'=>'6/3','correct_answer'=>'2',  'difficulty'=>'3'),
        )
    );

  }

}