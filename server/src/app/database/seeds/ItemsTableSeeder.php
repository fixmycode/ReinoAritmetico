<?php

class ItemsTableSeeder extends Seeder {
public function run(){

        DB::table('items')->delete();
        DB::table('item_type')->delete();

        DB::table('item_type')->insert(array('nombre' => 'item Type 1', 'description'=>'Descripcion del Item Type 1'));
        DB::table('item_type')->insert(array('nombre' => 'item Type 2', 'description'=>'Descripcion del Item Type 2'));
        DB::table('item_type')->insert(array('nombre' => 'item Type 3', 'description'=>'Descripcion del Item Type 3'));
        DB::table('item_type')->insert(array('nombre' => 'item Type 4', 'description'=>'Descripcion del Item Type 4'));

        DB::table('items')->insert(array('nombre'     => 'item 1', 
                                         'description'=>'Descripcion del Item',
                                         'price'      =>'100', 
                                         'image_path' =>'public/imb/upload/items/image1.png',
                                         'item_type_id'=>'1',
                                         'character_type_id'=>'1'));

        

        $classroom = Classroom::whereName('3ro Basico')->first();
        $client = Client::where('name','=','Ruben Dario')->first();
        DB::table('players')->insert(
          array(
            array('name' => 'Jon Snow', 'android_id' => 'know', 'character_type_id'=>1, 'classroom_id'=>$classroom->id,'client_id'=>$client->id),
            )
        );
  }
}