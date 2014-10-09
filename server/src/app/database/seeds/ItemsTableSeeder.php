<?php

class ItemsTableSeeder extends Seeder {
public function run(){

        DB::table('items')->delete();
        DB::table('item_type')->delete();

        DB::table('item_type')->insert(array('nombre' => 'Casco', 'description'=>'Cascos y armaduras'));
        DB::table('item_type')->insert(array('nombre' => 'Arma', 'description'=>'Espadas, arcos, etc'));

        DB::table('items')->insert(
            array('nombre'     => 'Casco fragmentado', 
                 'description'=>'Casco roto, util si notienes nada mas',
                 'price'      =>'10', 
                 'image_path' =>'upload/items/casco/1-diamond.png',
                 'item_type_id'=>'1',
                 'character_type_id'=>'1'
             )
        );

        DB::table('items')->insert(
            array('nombre'     => 'Casco de Diamante',
                 'description'=>'Casco irrompible',
                 'price'      =>'1000',
                 'image_path' =>'upload/items/casco/2-diamond.png',
                 'item_type_id'=>'1',
                 'character_type_id'=>'1'
             )
        );


        DB::table('items')->insert(
            array(
                 'nombre'           => 'Espada de madera', 
                 'description'       => 'Poco daño pero de algo sirve',
                 'price'             => '15', 
                 'image_path'        => 'upload/items/espada/1-madera.png',
                 'item_type_id'      => '2',
                 'character_type_id' =>'1'
             )
        );


        DB::table('items')->insert(
            array('nombre'     => 'Espada Ruby', 
                 'description'=>'Espada especial para sumas. Ahora seras mas fuerte!',
                 'price'      =>'500', 
                 'image_path' =>'upload/items/espada/2-ruby.png',
                 'item_type_id'=>'2',
                 'character_type_id'=>'1'
             )
        );

        

        $classroom = Classroom::whereName('3ro Basico')->first();
        $client = Client::where('name','=','Ruben Dario')->first();
        DB::table('players')->insert(
          array(
            array('name' => 'Jon Snow', 'android_id' => 'know', 'character_type_id'=>1, 'classroom_id'=>$classroom->id,'client_id'=>$client->id),
            )
        );
  }
}