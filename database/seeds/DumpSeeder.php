<?php

use Illuminate\Database\Seeder;

class DumpSeeder extends Seeder
{
    protected $files = [
        'states' => 'states.sql',
        'cities' => 'cities.sql',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach($this->files as $table => $filename){
            DB::statement("TRUNCATE {$table}");
            $content = file_get_contents(database_path("dumps/{$filename}"));
            DB::statement($content);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
