<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_add_to_cart_rejects_invalid_phone_is_not_relevant_but_invalid_product_is(): void
    {
        $response = $this->postJson('/en/cart/add', [
            'product_id' => 999999,
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('product_id');
    }

    public function test_add_to_cart_stores_item_in_session(): void
    {
        $product = Product::create([
            'name_ar' => 'منتج',
            'name_en' => 'Product',
            'slug' => 'product',
            'cost_price' => 50,
            'selling_price' => 90,
            'is_flower' => false,
            'is_customizable' => false,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->postJson('/en/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true, 'count' => 1]);

        $cart = session()->get('cart', []);

        $this->assertCount(1, $cart);
        $this->assertSame($product->id, $cart[0]['product_id']);
        $this->assertSame(2, $cart[0]['quantity']);
    }

    public function test_cart_update_route_increments_and_decrements_quantity(): void
    {
        session()->put('cart', [[
            'product_id' => 1,
            'name' => 'Item',
            'image' => null,
            'selling_price' => 90,
            'cost_price' => 50,
            'quantity' => 2,
            'is_flower' => false,
            'addons' => [],
        ]]);

        $increaseResponse = $this->patch('/en/cart/update/0/1');
        $increaseResponse->assertRedirect();
        $this->assertSame(3, session()->get('cart')[0]['quantity']);

        $decreaseResponse = $this->patch('/en/cart/update/0/-1');
        $decreaseResponse->assertRedirect();
        $this->assertSame(2, session()->get('cart')[0]['quantity']);
    }

    public function test_cart_update_route_clamps_quantity_between_one_and_ninety_nine(): void
    {
        session()->put('cart', [[
            'product_id' => 1,
            'name' => 'Item',
            'image' => null,
            'selling_price' => 90,
            'cost_price' => 50,
            'quantity' => 1,
            'is_flower' => false,
            'addons' => [],
        ]]);

        $this->patch('/en/cart/update/0/-1')->assertRedirect();
        $this->assertSame(1, session()->get('cart')[0]['quantity']);

        session()->put('cart.0.quantity', 99);

        $this->patch('/en/cart/update/0/1')->assertRedirect();
        $this->assertSame(99, session()->get('cart')[0]['quantity']);
    }
}
