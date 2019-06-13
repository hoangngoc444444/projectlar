<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Product::class, 50)->create()->each(function($product) {
//            Tag
            $arr = [];
            for ($i = 0; $i <= rand(0,2); $i++) {
                if ($i == 0) {
                    $arr[] = rand(1,15);
                }
                if ($i == 1) {
                    $arr[] = rand(16,35);
                }
                if ($i == 2) {
                    $arr[] = rand(36,50);
                }
            }
            $product->tags()->sync($arr);



//            Order
            $arr = [];
            for ($i = 0; $i <= rand(0,2); $i++) {
                if ($i == 0) {
                    $arr[rand(1,15)] = [
                        'quantity' => rand(1,99)
                    ];
                }
                if ($i == 1) {
                    $arr[rand(16,35)] = [
                        'quantity' => rand(1,99)
                    ];
                }
                if ($i == 2) {
                    $arr[rand(36,50)] = [
                        'quantity' => rand(1,99)
                    ];
                }
            }
            $product->orders()->sync($arr);
        });
    }
}
