<?php

class ItemsTableSeeder extends Seeder {
public function run(){

        DB::table('items')->delete();
        DB::table('item_type')->delete();

        DB::table('item_type')->insert(array('nombre' => 'Armas',     'description'=>'Espadas, arcos, hachas, dagas, etc'));
        DB::table('item_type')->insert(array('nombre' => 'Armaduras', 'description'=>'Cascos, corazas, blindaje, escudos, etc'));

        DB::table('items')->insert(
          array(
                           'nombre' => 'Pelo',
                      'description' => 'Pelo Elvis',
                            'price' => '0',
                       'image_path' => 'upload/items/armaduras/default.png',
                     'item_type_id' => '2',
                'character_type_id' => '1',
                            'headX' => '0.48',
                            'heady' => '0.60'
          )
        );
        DB::table('items')->insert(
                                   array(
                             'nombre' => 'Espada de palo',
                        'description' => 'Rasguña a tu oponente',
                              'price' => '0',
                         'image_path' => 'upload/items/armas/default.png',
                       'item_type_id' => '1',
                  'character_type_id' => '1',
                              'handX' => '0.60',
                              'handY' => '0.74'
            )
        );
        DB::table('items')->insert(array(
                             'nombre' => 'Casco de bronce',
                        'description' => 'Casco para warriors principiantes',
                              'price' => '15',
                         'image_path' => 'upload/items/armaduras/warrior.png',
                       'item_type_id' => '2',
                  'character_type_id' => '1',
                              'headX' => '0.55',
                              'heady' => '0.57'
            )
        );
        DB::table('items')->insert(array(
                             'nombre' => 'Sombrero de lana',
                        'description' => 'Casco para wizards principiantes',
                              'price' => '15',
                         'image_path' => 'upload/items/armaduras/wizard.png',
                       'item_type_id' => '2',
                  'character_type_id' => '3',
                              'headX' => '0.56',
                              'heady' => '0.72'
            )
        );
        DB::table('items')->insert(array(
                             'nombre' => 'Capucha Greener',
                        'description' => 'Capucha para arqueros principiantes',
                              'price' => '15',
                         'image_path' => 'upload/items/armaduras/archer.png',
                       'item_type_id' => '2',
                  'character_type_id' => '3',
                              'headX' => '0.55',
                              'heady' => '0.46'
            )
        );
        DB::table('items')->insert(
          array(
                           'nombre' => 'Android',
                      'description' => 'Protegete contra manzanas mordidas',
                            'price' => '18',
                       'image_path' => 'upload/items/armaduras/android.png',
                     'item_type_id' => '2',
                'character_type_id' => '1',
                            'headX' => '0.65',
                            'heady' => '0.66'
          )
        );
        DB::table('items')->insert(
          array(
                           'nombre' => 'Applace',
                      'description' => '¿No tienes donde llegar? Este casco te busca un lugar y te guia a el',
                            'price' => '20',
                       'image_path' => 'upload/items/armaduras/applace.png',
                     'item_type_id' => '2',
                'character_type_id' => '1',
                            'headX' => '0.50',
                            'heady' => '0.68'
          )
        );
        DB::table('items')->insert(
          array(
                           'nombre' => 'Gorro BBSW',
                      'description' => 'Gorro contra todo tipo de opareciones y problemas',
                            'price' => '25',
                       'image_path' => 'upload/items/armaduras/bbhat.png',
                     'item_type_id' => '2',
                'character_type_id' => '1',
                            'headX' => '0.44',
                            'heady' => '0.66'
          )
        );
        DB::table('items')->insert(
          array(
                           'nombre' => 'Lechuza',
                      'description' => 'Te da alas',
                            'price' => '18',
                       'image_path' => 'upload/items/armaduras/lechuza.png',
                     'item_type_id' => '2',
                'character_type_id' => '1',
                            'headX' => '0.50',
                            'heady' => '0.565'
          )
        );
        DB::table('items')->insert(
          array(
                           'nombre' => 'Lego',
                      'description' => 'Si te pisan, dolera mucho',
                            'price' => '22',
                       'image_path' => 'upload/items/armaduras/lego.png',
                     'item_type_id' => '2',
                'character_type_id' => '1',
                            'headX' => '0.5',
                            'heady' => '0.58'
          )
        );
        DB::table('items')->insert(
          array(
                           'nombre' => 'Pont',
                      'description' => 'Vence a tu enemigo de forma fabulosa',
                            'price' => '15',
                       'image_path' => 'upload/items/armaduras/pony.png',
                     'item_type_id' => '2',
                'character_type_id' => '1',
                            'headX' => '0.45',
                            'heady' => '0.65'
          )
        );
        DB::table('items')->insert(
          array(
                           'nombre' => 'Wuqi',
                      'description' => 'Para que nunca te quedes sin trabajo',
                            'price' => '20',
                       'image_path' => 'upload/items/armaduras/wuqi.png',
                     'item_type_id' => '2',
                'character_type_id' => '1',
                            'headX' => '0.55',
                            'heady' => '0.50'
          )
        );
        DB::table('items')->insert(array(
                             'nombre' => 'Espada de piedra',
                        'description' => 'Deberas ser fuere para levantarla',
                              'price' => '15',
                         'image_path' => 'upload/items/armas/sword1.png',
                       'item_type_id' => '1',
                  'character_type_id' => '1',
                              'handX' => '0.53',
                              'handY' => '0.75'
            )
        );
        DB::table('items')->insert(array(
                             'nombre' => 'Espada de llamas',
                        'description' => 'Con precaución, puedes quemarte',
                              'price' => '30',
                         'image_path' => 'upload/items/armas/sword2.png',
                       'item_type_id' => '1',
                  'character_type_id' => '2',
                              'handX' => '0.5',
                              'handY' => '0.791'
            )
        );
        DB::table('items')->insert(array(
                             'nombre' => 'Espada robot',
                        'description' => 'Extiende tu brazo bionico',
                              'price' => '22',
                         'image_path' => 'upload/items/armas/sword3.png',
                       'item_type_id' => '1',
                  'character_type_id' => '3',
                              'handX' => '0.6',
                              'handY' => '0.74'
            )
        );
        DB::table('items')->insert(array(
                             'nombre' => 'Baston del laplaciano',
                        'description' => 'Deriva sin parar',
                              'price' => '30',
                         'image_path' => 'upload/items/armas/staff1.png',
                       'item_type_id' => '1',
                  'character_type_id' => '2',
                              'handX' => '0.48',
                              'handY' => '0.40'
            )
        );
        DB::table('items')->insert(array(
                             'nombre' => 'Arco de Euler',
                        'description' => 'Nada podra detenerte, derivadas ni integrales',
                              'price' => '30',
                         'image_path' => 'upload/items/armas/bow1.png',
                       'item_type_id' => '1',
                  'character_type_id' => '3',
                              'handX' => '0.68',
                              'handY' => '0.45'
            )
        );
  }
}