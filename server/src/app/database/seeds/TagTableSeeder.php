<?php

class TagTableSeeder extends Seeder {

  public function run()
  {
    DB::table('problem_tag')->delete();
    DB::table('tags')->delete();

    DB::table('tags')->insert([
        array('id'=>1, 'name'=>'Suma'),
          array('id'=>2, 'name'=>'Resta'),
          array('id'=>3, 'name'=>'Multiplicación'),
          array('id'=>4, 'name'=>'División')
        ]
          
    );

    DB::table('problem_tag')->insert(
      array(
        array('id'=>1, 'problem_id'=>'1','tag_id'=>'1'),
        array('id'=>2, 'problem_id'=>'2','tag_id'=>'1'),
        array('id'=>3, 'problem_id'=>'3','tag_id'=>'1'),
        array('id'=>4, 'problem_id'=>'4','tag_id'=>'1'),
        array('id'=>5, 'problem_id'=>'5','tag_id'=>'1'),
        array('id'=>6, 'problem_id'=>'6','tag_id'=>'1'),
        array('id'=>24, 'problem_id'=>'24','tag_id'=>'1'),
        array('id'=>25, 'problem_id'=>'25','tag_id'=>'1'),
        array('id'=>26, 'problem_id'=>'26','tag_id'=>'1'),
        array('id'=>27, 'problem_id'=>'27','tag_id'=>'1'),
        array('id'=>28, 'problem_id'=>'28','tag_id'=>'1'),
        array('id'=>29, 'problem_id'=>'29','tag_id'=>'1'),
        array('id'=>30, 'problem_id'=>'30','tag_id'=>'1'),
        array('id'=>31, 'problem_id'=>'31','tag_id'=>'1'),
        array('id'=>32, 'problem_id'=>'32','tag_id'=>'1'),
        array('id'=>33, 'problem_id'=>'33','tag_id'=>'1'),
        array('id'=>34, 'problem_id'=>'34','tag_id'=>'1'),
        array('id'=>35, 'problem_id'=>'35','tag_id'=>'1'),
        array('id'=>36, 'problem_id'=>'36','tag_id'=>'1'),
        array('id'=>37, 'problem_id'=>'37','tag_id'=>'1'),
        array('id'=>38, 'problem_id'=>'38','tag_id'=>'1'),
        array('id'=>39, 'problem_id'=>'39','tag_id'=>'1'),
        array('id'=>40, 'problem_id'=>'40','tag_id'=>'1'),
        array('id'=>41, 'problem_id'=>'41','tag_id'=>'1'),
        array('id'=>42, 'problem_id'=>'42','tag_id'=>'1'),
        array('id'=>43, 'problem_id'=>'43','tag_id'=>'1'),

        array('id'=>45, 'problem_id'=>'45','tag_id'=>'2'),
        array('id'=>46, 'problem_id'=>'46', 'tag_id'=>'2'),
        array('id'=>47, 'problem_id'=>'47', 'tag_id'=>'2'),
        array('id'=>48, 'problem_id'=>'48', 'tag_id'=>'2'),
        array('id'=>49, 'problem_id'=>'49', 'tag_id'=>'2'),
        array('id'=>50, 'problem_id'=>'50', 'tag_id'=>'2'),
        array('id'=>51, 'problem_id'=>'51',  'tag_id'=>'2'),
        array('id'=>52, 'problem_id'=>'52',  'tag_id'=>'2'),
        array('id'=>53, 'problem_id'=>'53',  'tag_id'=>'2'),
        array('id'=>54, 'problem_id'=>'54',  'tag_id'=>'2'),
        array('id'=>55, 'problem_id'=>'55',  'tag_id'=>'2'),
        array('id'=>56, 'problem_id'=>'56',  'tag_id'=>'2'),
        array('id'=>57, 'problem_id'=>'57', 'tag_id'=>'2'),
        array('id'=>58, 'problem_id'=>'58',  'tag_id'=>'2'),
        array('id'=>59, 'problem_id'=>'59',  'tag_id'=>'2'),
        array('id'=>60, 'problem_id'=>'60',  'tag_id'=>'2'),
        array('id'=>61, 'problem_id'=>'61',  'tag_id'=>'2'),
        array('id'=>62, 'problem_id'=>'62',  'tag_id'=>'2'),
        array('id'=>63, 'problem_id'=>'63',  'tag_id'=>'2'),
        array('id'=>64, 'problem_id'=>'64',  'tag_id'=>'2'),
        array('id'=>65, 'problem_id'=>'65',  'tag_id'=>'2'),
        array('id'=>66, 'problem_id'=>'66',  'tag_id'=>'2'),
        array('id'=>67, 'problem_id'=>'67',  'tag_id'=>'2'),
        array('id'=>68, 'problem_id'=>'68',  'tag_id'=>'2'),
        array('id'=>69, 'problem_id'=>'69',  'tag_id'=>'2'),
        array('id'=>70, 'problem_id'=>'70',  'tag_id'=>'2'),
        array('id'=>71, 'problem_id'=>'71',  'tag_id'=>'2'),
        array('id'=>72, 'problem_id'=>'72',  'tag_id'=>'2'),

        array('id'=>11, 'problem_id'=>'11','tag_id'=>'3'),
        array('id'=>12, 'problem_id'=>'12','tag_id'=>'3'),
        array('id'=>13, 'problem_id'=>'13','tag_id'=>'3'),
        array('id'=>14, 'problem_id'=>'14','tag_id'=>'3'),
        array('id'=>15, 'problem_id'=>'15','tag_id'=>'3'),
        array('id'=>16, 'problem_id'=>'16','tag_id'=>'3'),
        array('id'=>17, 'problem_id'=>'17','tag_id'=>'3'),
        array('id'=>18, 'problem_id'=>'18','tag_id'=>'4'),
        array('id'=>19, 'problem_id'=>'19','tag_id'=>'4'),
        array('id'=>20, 'problem_id'=>'20','tag_id'=>'4'),
        array('id'=>21, 'problem_id'=>'21','tag_id'=>'4'),
        array('id'=>22, 'problem_id'=>'22','tag_id'=>'4'),
        array('id'=>23, 'problem_id'=>'23','tag_id'=>'4'),
        )
    );

  }

}