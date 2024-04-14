<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\productCodes;
use App\Models\ProductInOut;
use App\Models\ProductOut;
use App\Models\Receipt;
use App\Models\StoragePlace;
use App\Models\StorageUnit;
use App\Models\User;
use App\Models\UserRight;
use App\Models\Variable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

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
                'storageName' => 'Raktárhelység #'.$j+1,
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
                    'stock' => 0,
                    'categoryId' => 807,
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

        $shortNames = ['companyName', 'companyAddress', 'shopName', 'shopAddress', 'taxNumber', 'phoneNumber'];
        $variables = ['Cég neve', 'Cég címe', 'Üzlet neve', 'Üzlet címe', 'Adószám', 'Telefonszám'];
        $variablesValues = ['Kitalált cég', '6078, Jakabszállás Kossuth Lajos utca 76', 'Kitalált üzlet', '6078, Jakabszállás Kossuth Lajos utca 76', '70675432-2-11', '704176989'];
        for ($i = 0; $i < count($shortNames); $i++) {
            Variable::create([
               'variableShortName' => $shortNames[$i],
               'variableName' => $variables[$i],
               'variableValue' => $variablesValues[$i]
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
        for ($k = 0; $k < 100; $k++) {
            $randomNumbers = [];
            $faker = \Faker\Factory::create();
            $randtime = $faker->dateTimeBetween('2024-01-01', '2024-12-30'." 23:59:59");
            while (count($randomNumbers) != 8) {
                $randomNumber = rand(0, count($productIds)-1);
                if (!in_array($randomNumber, $randomNumbers)) {
                    $randomNumbers[] = $randomNumber;
                }
            }
            for ($l = 0; $l < 8; $l++) {
                $randomNumber = random_int(5, 15);
                ProductOut::factory()->create([
                    'productId' => $productIds[$randomNumbers[$l]],
                    'howMany' => $randomNumber,
                    'howManyLeft' => $randomNumber,
                    'orderNumber' => $k+1,
                    'isCompleted' => false,
                    'created_at' => $randtime
                ]);
            }
        }
        for ($m = 0; $m < 3; $m++) {
            $randomInt = rand(3,5);
            $randomProductCode = $productIds[rand(0, count($productIds)-1)];
            for ($n = 0; $n < $randomInt; $n++) {
                StoragePlace::factory()->create([
                   'productId' =>  $randomProductCode,
                    'index' => $n+1,
                    'howMany' => rand(5,15),
                    'storagePlace' => random_int(1,5).'-'.$abc[2].'5-4'
                ]);
            }
        }
        for ($o = 0; $o < count($productIds); $o++) {
            if (StoragePlace::where('productId', '=', $productIds[$o])->count() > 0) {
                Product::find($productIds[$o])->update(['stock' => StoragePlace::where('productId', '=', $productIds[$o])->sum('howMany')]);
            } else {
                $randomStock = rand(10, 50);
                StoragePlace::factory()->create([
                   'productId' => $productIds[$o],
                   'index' => 1,
                   'howMany' => $randomStock,
                   'storagePlace' => random_int(1,5).'-'.$abc[2].'5-4'
                ]);
                Product::find($productIds[$o])->update(['stock' => $randomStock]);
            }
        }
        for ($p = 0; $p < 100; $p++) {
            ProductInOut::factory()->create([
               'productId' => $productIds[rand(0, count($productIds)-1)],
                'howMany' => rand(50,70),
                'newBPrice' => rand(3000,10000),
                'isFinished' => true,
                'created_at' => $faker->dateTimeBetween('2024-01-01', '2024-12-30'." 23:59:59")
            ]);
        }

        for ($q = 0; $q < 50; $q++) {
            Receipt::factory()->create([
               'isInvoice' => 0,
                'date' => date('Y-m-d'),
                'change' => 0,
                'sumPrice' => round(rand(4000, 1000)/5)*5,
                'paymentType' => rand(0,1) == 0 ? 'B' : 'C',
                'employeeId' => 1
            ]);
        }
    }
}
