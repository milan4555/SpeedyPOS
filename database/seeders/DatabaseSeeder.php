<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory()->create([
            'categoryId' => 807,
            'categoryName' => 'Random termékek'
        ]);

        $myfile = fopen('public/factories/products.txt', "r") or die("Unable to open file!");
        while(!feof($myfile)) {
            $line = fgets($myfile);
            $data = explode(';',$line);
            Product::factory()->create([
                'productName' => $data[0],
                'productShortName' => $data[1],
                'bPrice' => $data[2],
                'nPrice' => $data[3],
                'stock' => $data[4],
                'categoryId' => 807
            ]);
        }
        fclose($myfile);
    }
}
