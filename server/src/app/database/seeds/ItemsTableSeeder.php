<?php

class ItemsTableSeeder extends Seeder {
public function run(){

        DB::table('items')->delete();
        DB::table('item_type')->delete();

        DB::table('item_type')->insert(array('nombre' => 'Armas',     'description'=>'Espadas, arcos, hachas, dagas, etc'));
        DB::table('item_type')->insert(array('nombre' => 'Armaduras', 'description'=>'Cascos, corazas, blindaje, escudos, etc'));

        DB::table('items')->insert(
            array(
                             'nombre' => 'Casco de pony',
                        'description' => 'Controla el poder del arcoiris',
                              'price' => '0',
                         'image_path' => 'upload/items/armaduras/pony.png',
                       'item_type_id' => '2',
                  'character_type_id' => '1',
                              'headX' => '0.55',
                              'heady' => '0.46'
            ),
            array(
                             'nombre' => 'Espada de palo',
                        'description' => 'Rasguña a tu oponente',
                              'price' => '0',
                         'image_path' => 'upload/items/armas/sword1.png',
                       'item_type_id' => '1',
                  'character_type_id' => '1',
                              'handX' => '0.5',
                              'handy' => '0.792'
            ),
            array(
                             'nombre' => 'Casco de bronce',
                        'description' => 'Casco para warriors principiantes',
                              'price' => '15',
                         'image_path' => 'upload/items/armaduras/warrior.png',
                       'item_type_id' => '2',
                  'character_type_id' => '1',
                              'headX' => '0.55',
                              'heady' => '0.57'
            ),
            array(
                             'nombre' => 'Sombrero de lana',
                        'description' => 'Casco para wizards principiantes',
                              'price' => '15',
                         'image_path' => 'upload/items/armaduras/warrior.png',
                       'item_type_id' => '2',
                  'character_type_id' => '3',
                              'headX' => '0.55',
                              'heady' => '0.57'
            ),
            array(
                             'nombre' => 'Capucha Greener',
                        'description' => 'Capucha para arqueros principiantes',
                              'price' => '15',
                         'image_path' => 'upload/items/armaduras/archer.png',
                       'item_type_id' => '2',
                  'character_type_id' => '3',
                              'headX' => '0.55',
                              'heady' => '0.46'
            ),
            array(
                             'nombre' => 'Espada de piedra',
                        'description' => 'Deberas ser fuere para levantarla',
                              'price' => '15',
                         'image_path' => 'upload/items/armas/sword1.png',
                       'item_type_id' => '1',
                  'character_type_id' => '1',
                              'handX' => '0.5',
                              'handy' => '0.792'
            ),
            array(
                             'nombre' => 'Espada de hierro',
                        'description' => 'Cuídala, se gasta',
                              'price' => '15',
                         'image_path' => 'upload/items/armas/sword2.png',
                       'item_type_id' => '1',
                  'character_type_id' => '2',
                              'handX' => '0.5',
                              'handy' => '0.791'
            ),
            array(
                             'nombre' => 'Espada de acero',
                        'description' => 'Corta sin parar',
                              'price' => '15',
                         'image_path' => 'upload/items/armas/sword3.png',
                       'item_type_id' => '1',
                  'character_type_id' => '3',
                              'handX' => '0.5',
                              'handy' => '0.78'
            )
        );
  }
}