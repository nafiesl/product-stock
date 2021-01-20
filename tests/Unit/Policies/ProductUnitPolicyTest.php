<?php

namespace Tests\Unit\Policies;

use App\Models\ProductUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest as TestCase;

class ProductUnitPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_product_unit()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new ProductUnit));
    }

    /** @test */
    public function user_can_view_product_unit()
    {
        $user = $this->createUser();
        $productUnit = ProductUnit::factory()->create();
        $this->assertTrue($user->can('view', $productUnit));
    }

    /** @test */
    public function user_can_update_product_unit()
    {
        $user = $this->createUser();
        $productUnit = ProductUnit::factory()->create();
        $this->assertTrue($user->can('update', $productUnit));
    }

    /** @test */
    public function user_can_delete_product_unit()
    {
        $user = $this->createUser();
        $productUnit = ProductUnit::factory()->create();
        $this->assertTrue($user->can('delete', $productUnit));
    }
}
