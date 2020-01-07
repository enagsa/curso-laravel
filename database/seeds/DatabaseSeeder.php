<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables([
        	'users',
            'user_profiles',
            'user_skill',
        	'professions',
            'skills',
            'teams'
        ]);

        $this->call([
            SkillSeeder::class,
            ProfessionSeeder::class,
            TeamSeeder::class,
            UserSeeder::class
        ]);
    }

    protected function truncateTables(array $tables)
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0');
    	foreach($tables as $table){
    		DB::table($table)->truncate();
    	}
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
