<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$this->call(LaratrustSeeder::class);
		 
		for($i=1; $i<4; $i++) {
				
			DB::table('films')->insert([
			    'id' => $i,
				'name' => str_random(10),
				'description' => str_random(10) ,
				'realease_date' => date('Y-m-d' ),
				'rating' => rand(1,5),
				'ticket_price' => 100,
				'country' => str_random(10),
				'genre' => str_random(10),
				'photo' => $i.'.jpg'
				
			]);
			
			DB::table('comments')->insert([ 
				'name' => str_random(10),
				'comment' => str_random(10),
				'user_id' => 1,
				'film_id' => $i
				
			]);
			
		 }
    }
}
