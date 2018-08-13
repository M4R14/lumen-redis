<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IcdxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $handle = fopen(__DIR__.'/icdx_category_id_3.csv', "r");
        // $header = false;
        $header = true;

        $row_number = 1;
        $data = [];
        while ($csvLine = fgetcsv($handle, 1000, ",")) {
            $pack_size = 10000;
            if (count($data) == $pack_size) {
                echo "insert data +$pack_size ~> $row_number.... \n";
                DB::table('icdx')->insert($data);
                $data = [];
            }
            if ($header) {
                $header = false;
            } else {
                $data[] = [
                    'group' => $csvLine[0],
                    'code' => $csvLine[1],
                    'name' => $csvLine[2],
                ];
                $row_number += 1;
            }
        }
        echo 'size: '.DB::table('icdx')->count()."\n";
    }
}
