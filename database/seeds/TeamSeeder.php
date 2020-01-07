<?php

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Team::class)->create([
        	'name' => 'ExpacioWeb'
        ]);
        factory(Team::class)->times(99)->create();
    }
}
