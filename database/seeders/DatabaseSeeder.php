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
use App\Models\recToProd;
use App\Models\StoragePlace;
use App\Models\StorageUnit;
use App\Models\User;
use App\Models\UserRight;
use App\Models\Variable;
use Faker\Guesser\Name;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $myfile = fopen('public/factories/categories.txt', "r") or die("Unable to open file!");
        while(!feof($myfile)) {
            $line = fgets($myfile);
            $data = explode(';',$line);
            if ($data[0] != null) {
                Category::factory()->create([
                    'categoryId' => $data[0],
                    'categoryName' => $data[1]
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
        for ($j = 0; $j < 5; $j++) {
            StorageUnit::create([
                'storageName' => 'Raktárhelység #'.$j+1,
                'numberOfRows' => random_int(3,5),
                'widthNumber' => random_int(3,7),
                'heightNumber' => random_int(3,7)
            ]);
        }
        $abc = 'ABCDEFGHIJKLMNOPQRSTUVWZ';
        $myfile = fopen('public/factories/products.txt', "r") or die("Unable to open file!");
        $productIds = [];
        while(!feof($myfile)) {
            $line = fgets($myfile);
            $data = explode(';',$line);
            if ($data[0] != null) {
                $companyId = Company::all();
                $indexNumber = Product::where('categoryId', $data[5])->count() + 1;
                $productIds[] = $data[5].str_repeat(0,7-strlen($indexNumber)).$indexNumber;
                Product::factory()->create([
                    'productId' => $data[5].str_repeat(0,7-strlen($indexNumber)).$indexNumber,
                    'productName' => $data[0],
                    'productShortName' => $data[1],
                    'bPrice' => $data[2],
                    'nPrice' => $data[3],
                    'stock' => $data[4],
                    'categoryId' => $data[5],
                    'companyId' => random_int(1,100) > 90 ? null : random_int(1, count($companyId))
                ]);
            }
        }
        fclose($myfile);
        for ($i = 0; $i < 300; $i++) {
            $randomCode = null;
            while ($randomCode == null) {
                $randomGenerated = random_int(10000000000, 99999999999);
                if (productCodes::where('productCode', '=', $randomGenerated)->count() == 0) {
                    $randomCode = $randomGenerated;
                }
            }
            productCodes::factory()->create([
                'productIdCode' => $productIds[random_int(0, count($productIds)-1)],
                'productCode' => $randomGenerated
            ]);
        }

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
//        UserRight::factory()->create([
//            'isSuperior' => false,
//            'canCreateProduct' => false,
//            'canUpdateProduct' => false,
//            'canDeleteProduct' => false,
//        ]);
        User::factory()->create([
            'username' => 'admin',
            'password' => 'admin',
            'firstName' => 'admin',
            'lastName' => 'admin',
            'phoneNumber' => 704176989,
            'position' => 'admin',
        ]);
        for ($r = 0; $r < 40; $r++) {
            User::factory()->create([
                'firstName' => fake()->firstName,
                'lastName' => fake()->lastName,
                'username' => fake()->userName,
                'password' => fake()->password,
                'phoneNumber' => 701234567,
                'position' => rand(0,1) == 1 ? 'cashier' : 'storage',
            ]);
        }
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
            $finished = rand(0,1) == 1;
            for ($l = 0; $l < 8; $l++) {
                $randomNumber = random_int(5, 15);
                ProductOut::factory()->create([
                    'productId' => $productIds[$randomNumbers[$l]],
                    'howMany' => $randomNumber,
                    'howManyLeft' => $randomNumber,
                    'orderNumber' => $k+1,
                    'isCompleted' => $finished,
                    'created_at' => $randtime
                ]);
            }
        }
        for ($m = 0; $m < 80; $m++) {
            $randomInt = rand(10,20);
            $randomProductCode = $productIds[rand(0, count($productIds)-1)];
            for ($n = 0; $n < $randomInt; $n++) {
                $storage = StorageUnit::all()->random(1)[0];
                $index = StoragePlace::where('productId', '=', $randomProductCode)->count() + 1;
                StoragePlace::factory()->create([
                   'productId' =>  $randomProductCode,
                    'index' => $index,
                    'howMany' => rand(5,15),
                    'storagePlace' => $storage->storageId.'-'.$abc[rand(0,($storage->numberOfRows-1))].rand(1, $storage->heightNumber).'-'.rand(1, $storage->widthNumber)
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
            $sum = 0;
            $randomSerialNumber = 0;
            while ($randomSerialNumber == 0) {
                $random = rand(1111111, 9999999);
                if (Receipt::where('receiptSerialNumber', '=', $random)->count() == 0) {
                    $randomSerialNumber = $random;
                }
            }
            $receipt = Receipt::factory()->create([
                'receiptSerialNumber' => $randomSerialNumber,
                'isInvoice' => 0,
                'date' => date('Y-m-d'),
                'change' => 0,
                'sumPrice' => $sum,
                'paymentType' => rand(0,1) == 0 ? 'B' : 'K',
                'employeeId' => 1
            ]);
            for ($s = 0; $s < rand(3,8); $s++) {
                $atTimePrice = round(rand(1000,10000)/5)*5;
                $quantity = rand(2,4);
                recToProd::create([
                    'productId' => $productIds[rand(0, count($productIds)-1)],
                    'receiptId' => $q+1,
                    'quantity' => $quantity,
                    'atTimePrice' => $atTimePrice
                ]);
                $sum += $quantity*$atTimePrice;
            }
            $receipt->update(['sumPrice' => $sum]);
        }
    }
}
