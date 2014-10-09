<?php

class ItemsTableSeeder extends Seeder {
public function run(){

        DB::table('items')->delete();
        DB::table('item_type')->delete();

        DB::table('item_type')->insert(array('nombre' => 'Armas',     'description'=>'Espadas, arcos, hachas, dagas, etc'));
        DB::table('item_type')->insert(array('nombre' => 'Armaduras', 'description'=>'Cascos, corazas, blindaje, escudos, etc'));

        /* WARRIOR ITEMS */
        DB::table('items')->insert(
            array(
                        'nombre' => 'Hacha Simple', 
                   'description' => 'Hacha de madera',
                         'price' => '15', 
                    'image_path' => 'upload/items/armas/axe2.png',
                  'item_type_id' => '1',
             'character_type_id' => '1'
            )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Hacha Sigma', 
                        'description' => 'Excelente arma contra las sumas',
                              'price' => '150', 
                         'image_path' => 'upload/items/armas/dark_soul_axe.png',
                       'item_type_id' => '1',
                  'character_type_id' => '1'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Espada Integral', 
                        'description' => 'Areas bajo la curva, ningún problema!',
                              'price' => '250', 
                         'image_path' => 'upload/items/armas/dragon_sword.png',
                       'item_type_id' => '1',
                  'character_type_id' => '1'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Espada Euler', 
                        'description' => 'La mas rapida de todas',
                              'price' => '600', 
                         'image_path' => 'upload/items/armas/demon_sword.png',
                       'item_type_id' => '1',
                  'character_type_id' => '1'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Armadura Euler', 
                        'description' => 'La mas fuerte de todas, aun que la derives sige manteniendose firme',
                              'price' => '1000', 
                         'image_path' => 'upload/items/armaduras/dragon_slayer_armor.png',
                       'item_type_id' => '2',
                  'character_type_id' => '1'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Escudo Simple', 
                        'description' => 'Escudo simple',
                              'price' => '15', 
                         'image_path' => 'upload/items/armaduras/shield_of_disorientation.png',
                       'item_type_id' => '2',
                  'character_type_id' => '1'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Casco de División', 
                        'description' => 'Las divisiónes no te pueden tocar',
                              'price' => '25', 
                         'image_path' => 'upload/items/armaduras/golden_helmet_of_deception.png',
                       'item_type_id' => '2',
                  'character_type_id' => '1'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Escudo de Cartesianas', 
                        'description' => 'Para que nunca te sientas sin un origen',
                              'price' => '50', 
                         'image_path' => 'upload/items/armaduras/arms_of_faith.png',
                       'item_type_id' => '2',
                  'character_type_id' => '1'
             )
        );

        /* WIZARD ITEMS */
        DB::table('items')->insert(
            array(
                             'nombre' => 'Daga Simple', 
                        'description' => 'Converger nunca fue tan rápido',
                              'price' => '15', 
                         'image_path' => 'upload/items/armas/dagger.png',
                       'item_type_id' => '1',
                  'character_type_id' => '2'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Bastón de Newton', 
                        'description' => 'Ningún problema no tendra matemáticas',
                              'price' => '20', 
                         'image_path' => 'upload/items/armas/elder_staff_of_wisdom.png',
                       'item_type_id' => '1',
                  'character_type_id' => '2'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Bastón Logaritmico', 
                        'description' => 'Converger nunca fue tan rápido',
                              'price' => '150', 
                         'image_path' => 'upload/items/armas/staff_of_the_magi.png',
                       'item_type_id' => '1',
                  'character_type_id' => '2'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Tridente Jacobiano', 
                        'description' => 'Derivadas multiples no son un drama',
                              'price' => '800', 
                         'image_path' => 'upload/items/armas/trident_of_sorrow.png',
                       'item_type_id' => '1',
                  'character_type_id' => '2'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Armadura Jacobiano', 
                        'description' => 'Derivadas multiples no son un drama',
                              'price' => '1000', 
                         'image_path' => 'upload/items/armaduras/wizard_robe_class_4.png',
                       'item_type_id' => '2',
                  'character_type_id' => '2'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Mascara Lineal',
                        'description' => 'Protección Simple',
                              'price' => '25', 
                         'image_path' => 'upload/items/armaduras/mask_of_power.png',
                       'item_type_id' => '2',
                  'character_type_id' => '2'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Túnica Numérica', 
                        'description' => 'Los metodos numéricos convergen rápidamente',
                              'price' => '250', 
                         'image_path' => 'upload/items/armaduras/cursed_egyptian_armor.png',
                       'item_type_id' => '2',
                  'character_type_id' => '2'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Túnica R3', 
                        'description' => 'En R3 no tienes problemas para integrar',
                              'price' => '150', 
                         'image_path' => 'upload/items/armaduras/wizard_robe_class_2.png',
                       'item_type_id' => '2',
                  'character_type_id' => '2'
             )
        );

        /* ARCHER ITEMS */
        DB::table('items')->insert(
            array(
                             'nombre' => 'Arco Simple', 
                        'description' => 'Arco para problemas simples',
                              'price' => '15', 
                         'image_path' => 'upload/items/armas/bow1.png',
                       'item_type_id' => '1',
                  'character_type_id' => '3'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Arco Simple', 
                        'description' => 'Arco para problemas simples',
                              'price' => '150', 
                         'image_path' => 'upload/items/armas/bone_bow.png',
                       'item_type_id' => '1',
                  'character_type_id' => '3'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Ballesta Epsilon', 
                        'description' => 'Un roce tan pequeño, que tus enemigos no lo veran viniendo',
                              'price' => '250', 
                         'image_path' => 'upload/items/armas/crossbow.png',
                       'item_type_id' => '1',
                  'character_type_id' => '3'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Ballesta L´hopital', 
                        'description' => 'Dispara hasta el infinito',
                              'price' => '600',
                         'image_path' => 'upload/items/armas/lightning_bow.png',
                       'item_type_id' => '1',
                  'character_type_id' => '3'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Collar de estabilidad', 
                        'description' => 'Manten la calma, baja tus revoluciones, controla tu pulso',
                              'price' => '20',
                         'image_path' => 'upload/items/armaduras/rune_necklace.png',
                       'item_type_id' => '2',
                  'character_type_id' => '3'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Anillo de Estadística', 
                        'description' => 'Tu presición nunca sera mejor que al promediar tus tiros',
                              'price' => '100',
                         'image_path' => 'upload/items/armaduras/techpaires_ring.png',
                       'item_type_id' => '2',
                  'character_type_id' => '3'
             )
        );

        DB::table('items')->insert(
            array(
                             'nombre' => 'Máscara de Refracción', 
                        'description' => 'Tu visión llegara mas allá del campo visual',
                              'price' => '200',
                         'image_path' => 'upload/items/armaduras/soulless_warrior_mask.png',
                       'item_type_id' => '2',
                  'character_type_id' => '3'
             )
        );


        DB::table('items')->insert(
            array(
                             'nombre' => 'Túnica de Invisibilidad', 
                        'description' => 'Para que dispares sin ser visto',
                              'price' => '250',
                         'image_path' => 'upload/items/armaduras/cloak_of_evasion.png',
                       'item_type_id' => '2',
                  'character_type_id' => '3'
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