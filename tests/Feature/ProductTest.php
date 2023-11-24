<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testProducts()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();

        $this->get("/api/products/$product->id")
            ->assertStatus(200)
            ->assertJson([
                "value" => [
                    "name" => $product->name,
                    "category" => [
                        "id" => $product->category->id,
                        "name" => $product->category->name
                    ],
                    "price" => $product->price,
                    "is_expensive" => $product->price > 1000,
                    "created_at" => $product->created_at->toJSON(),
                    "updated_at" => $product->updated_at->toJSON()
                ]
            ]);
    }

    public function testProductCollection()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get("/api/products")
            ->assertStatus(200);

        $names = $response->json("data.*.name");
        for ($i = 0; $i < 5; $i++) {
            self::assertContains("Product $i of Food", $names);
        }
        for ($i = 0; $i < 5; $i++) {
            self::assertContains("Product $i of Gadget", $names);
        }
    }

    public function testProductsPaging()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get("/api/products-paging")
            ->assertStatus(200);

        self::assertNotNull($response->json("links"));
        self::assertNotNull($response->json("meta"));
        self::assertNotNull($response->json("data"));
    }

    public function testAdditionalData()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();

        $this->get("/api/products-debug/$product->id")
            ->assertStatus(200)
            ->assertJson([
                "author" => "Miftah Fadilah",
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "price" => $product->price,
                ]
            ]);
    }

    public function testAdditionalDynamicParam()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();
        $response = $this->get("/api/products-debug/$product->id")
            ->assertStatus(200)
            ->assertJson([
                "author" => "Miftah Fadilah",
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "price" => $product->price,
                ]
            ]);

        self::assertNotNull($response->json("server_time"));
    }

    public function testName()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();
        $this->get("/api/products/$product->id")
            ->assertStatus(200)
            ->assertHeader("X-Powered-By", "Miftah Fadilah")
            ->assertJson([
                "value" => [
                    "name" => $product->name,
                    "category" => [
                        "id" => $product->category->id,
                        "name" => $product->category->name
                    ],
                    "price" => $product->price,
                    "is_expensive" => $product->price > 1000,
                    "created_at" => $product->created_at->toJSON(),
                    "updated_at" => $product->updated_at->toJSON()
                ]
            ]);
    }
}
