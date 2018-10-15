<?php

use Illuminate\Database\Seeder;

class spinDetails extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($x = 1; $x <= 20; $x++) {
   			 DB::table('spinoffers')->insert([
            'spinid' => $x,
            'spintype' => 'Caffe',
            'offerid' => 'na',
            'empty' => 0,
       		 ]);
		} 
    }
}
