<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\productCodes;
use App\Models\StorageUnit;
use App\Models\User;
use App\Models\UserRight;
use App\Models\Variable;
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
        for ($j = 0; $j < 5; $j++) {
            StorageUnit::create([
                'storageName' => 'Raktárhelység #'.$j,
                'numberOfRows' => random_int(6,10),
                'widthNumber' => random_int(5,15),
                'heightNumber' => random_int(4,8)
            ]);
        }
        $abc = 'ABCDEFGHIJKLMNOPQRSTUVWZ';
        $myfile = fopen('public/factories/products.txt', "r") or die("Unable to open file!");
        $i = 1;
        $productIds = [];
        while(!feof($myfile)) {
            $line = fgets($myfile);
            $data = explode(';',$line);
            if ($data[0] != null) {
                $productIds[] = '807'.str_repeat(0,7-strlen($i)).$i;
                Product::factory()->create([
                    'productId' => '807'.str_repeat(0,7-strlen($i)).$i,
                    'productName' => $data[0],
                    'productShortName' => $data[1],
                    'bPrice' => $data[2],
                    'nPrice' => round($data[2]*1.8),
                    'stock' => $data[4],
                    'categoryId' => 807,
                    'storagePlace' => random_int(1,5).'-'.$abc[6].'5-4'
                ]);
                $i++;
            }
        }
        fclose($myfile);
        $myfile = fopen('public/factories/productCodes.txt', "r") or die("Unable to open file!");
        while(!feof($myfile)) {
            $line = fgets($myfile);
            $data = explode(';',$line);
            if ($data[0] != null) {
                productCodes::factory()->create([
                    'productIdCode' => $productIds[random_int(0, count($productIds)-1)],
                    'productCode' => $data[1]
                ]);
            }
        }
        fclose($myfile);
        $myfile = fopen('public/factories/companies.txt', "r") or die("Unable to open file!");
        while(!feof($myfile)) {
            $line = fgets($myfile);
            $data = explode(';',$line);
            if ($data[0] != null) {
                Company::create([
                    'companyName' => $data[0],
                    'postcode' => $data[1],
                    'city' => $data[2],
                    'street' => $data[3],
                    'streetNumber' => $data[4],
                    'isSupplier' => $data[5],
                    'taxNumber' => $data[6],
                    'owner' => $data[7],
                    'phoneNumber' => $data[8]
                ]);
            }
        }
        fclose($myfile);

        $shortNames = ['companyName', 'companyAddress', 'shopName', 'shopAddress', 'taxNumber'];
        $variables = ['Cég neve', 'Cég címe', 'Üzlet neve', 'Üzlet címe', 'Adószám'];
        for ($i = 0; $i < count($shortNames); $i++) {
            Variable::create([
                'variableShortName' => $shortNames[$i],
               'variableName' => $variables[$i]
            ]);
        }
        UserRight::factory()->create([
            'isSuperior' => false,
            'canCreateProduct' => false,
            'canUpdateProduct' => false,
            'canDeleteProduct' => false,
        ]);
        User::factory()->create([
            'username' => 'admin',
            'password' => 'admin',
            'firstName' => 'admin',
            'lastName' => 'admin',
            'phoneNumber' => 704176989,
            'position' => 'admin',
            'rightsId' => 1
        ]);
    }
}
