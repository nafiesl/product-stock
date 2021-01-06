<?php

namespace Tests\Unit\Policies;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest as TestCase;

class ProductPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_product()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Product));
    }

    /** @test */
    public function user_can_view_product()
    {
        $user = $this->createUser();
        $product = factory(Product::class)->create();
        $this->assertTrue($user->can('view', $product));
    }

    /** @test */
    public function user_can_update_product()
    {
        $user = $this->createUser();
        $product = factory(Product::class)->create();
        $this->assertTrue($user->can('update', $product));
    }

    /** @test */
    public function user_can_delete_product()
    {
        $user = $this->createUser();
        $product = factory(Product::class)->create();
        $this->assertTrue($user->can('delete', $product));
    }
}
