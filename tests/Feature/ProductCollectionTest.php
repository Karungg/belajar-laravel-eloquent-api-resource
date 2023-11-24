<?php

namespace Tests\Feature;

use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductCollectionTest extends TestCase
{
    public function testProducts()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get('/api/products')
            ->assertStatus(200)
            ->assertHeader("X-Powered-By", "Miftah Fadilah");

        $names = $response->json("data.*.name");
        for ($i = 0; $i < 5; $i++) {
            self::assertContains("Product $i of Food", $names);
        }
        for ($i = 0; $i < 5; $i++) {
            self::assertContains("Product $i of Gadget", $names);
        }
    }
}
