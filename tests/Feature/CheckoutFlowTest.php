<?php

namespace Tests\Feature;

use App\Models\AddOn;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_checkout_rejects_non_egyptian_phone(): void
    {
        $product = Product::create([
            'name_ar' => 'زهور',
            'name_en' => 'Flowers',
            'slug' => 'flowers',
            'cost_price' => 100,
            'selling_price' => 150,
            'is_flower' => false,
            'is_customizable' => false,
            'is_active' => true,
            'stock' => 10,
        ]);

        session()->put('cart', [[
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => null,
            'selling_price' => 150,
            'cost_price' => 100,
            'quantity' => 1,
            'is_flower' => false,
            'addons' => [],
        ]]);

        $response = $this->post('/en/checkout', [
            'guest_name' => 'Test User',
            'phone' => '12345',
            'city' => 'Cairo',
            'address' => 'Some address',
        ]);

        $response->assertSessionHasErrors('phone');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_places_order_and_clears_cart(): void
    {
        $product = Product::create([
            'name_ar' => 'هدية',
            'name_en' => 'Gift',
            'slug' => 'gift',
            'cost_price' => 200,
            'selling_price' => 350,
            'is_flower' => false,
            'is_customizable' => false,
            'is_active' => true,
            'stock' => 5,
        ]);

        session()->put('cart', [[
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => null,
            'selling_price' => 350,
            'cost_price' => 200,
            'quantity' => 2,
            'is_flower' => false,
            'addons' => [],
        ]]);

        $response = $this->post('/en/checkout', [
            'guest_name' => 'Valid User',
            'phone' => '01012345678',
            'city' => 'Cairo',
            'address' => 'Address',
            'notes' => 'note',
        ]);

        $response->assertRedirect('/en/track');
        $this->assertDatabaseCount('orders', 1);

        $order = Order::first();

        $this->assertSame('Valid User', $order->guest_name);
        $this->assertSame('01012345678', $order->phone);
        $this->assertSame('pending_confirmation', $order->status);
        $this->assertSame([], session()->get('cart', []));
    }

    public function test_checkout_includes_addon_prices_per_unit_and_saves_snapshots(): void
    {
        $product = Product::create([
            'name_ar' => 'باقة',
            'name_en' => 'Bouquet',
            'slug' => 'bouquet',
            'cost_price' => 80,
            'selling_price' => 100,
            'is_flower' => false,
            'is_customizable' => false,
            'is_active' => true,
            'stock' => 20,
        ]);

        $addonA = AddOn::create([
            'name_ar' => 'بالون',
            'name_en' => 'Balloon',
            'price' => 20,
            'description_ar' => null,
            'description_en' => null,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $addonB = AddOn::create([
            'name_ar' => 'كارت',
            'name_en' => 'Card',
            'price' => 10,
            'description_ar' => null,
            'description_en' => null,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        session()->put('cart', [[
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => null,
            'selling_price' => 100,
            'cost_price' => 80,
            'quantity' => 2,
            'is_flower' => false,
            'addons' => [],
        ]]);

        $response = $this->post('/en/checkout', [
            'guest_name' => 'Addon User',
            'phone' => '01012345678',
            'city' => 'Cairo',
            'address' => 'Address',
            'addons' => [$addonA->id, $addonB->id],
        ]);

        $response->assertRedirect('/en/track');

        $order = Order::query()->firstOrFail();
        $orderItem = OrderItem::query()->firstOrFail();

        $this->assertSame('260.00', $order->subtotal);
        $this->assertSame('260.00', $order->total_price);
        $this->assertSame('100.00', $order->net_profit);

        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseCount('order_item_addons', 2);

        $this->assertDatabaseHas('order_item_addons', [
            'order_item_id' => $orderItem->id,
            'add_on_id' => $addonA->id,
            'add_on_name_snapshot' => $addonA->name,
            'price_snapshot' => '20.00',
        ]);

        $this->assertDatabaseHas('order_item_addons', [
            'order_item_id' => $orderItem->id,
            'add_on_id' => $addonB->id,
            'add_on_name_snapshot' => $addonB->name,
            'price_snapshot' => '10.00',
        ]);

        $orderItem->load('addons');
        $this->assertSame(260.0, $orderItem->line_total);
    }
}
