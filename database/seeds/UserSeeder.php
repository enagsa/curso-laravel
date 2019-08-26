<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$professionId = DB::table('professions')
    		->where('title', 'Desarrollador back-end')
    		->value('id');
    	
        DB::table('users')->insert([
        	'name' => 'Enrique Aguilar',
        	'email' => 'enriqueaguilar@expcioweb.com',
        	'password' => bcrypt('laravel'),
        	'profession_id' => $professionId
        ]);
    }
}
