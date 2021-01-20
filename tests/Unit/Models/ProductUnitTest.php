<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\ProductUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest as TestCase;

class ProductUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_product_unit_has_title_link_attribute()
    {
        $productUnit = ProductUnit::factory()->create();

        $this->assertEquals(
            link_to_route('product_units.show', $productUnit->title, [$productUnit], [
                'title' => __(
                    'app.show_detail_title',
                    ['title' => $productUnit->title, 'type' => __('product_unit.product_unit')]
                ),
            ]), $productUnit->title_link
        );
    }

    /** @test */
    public function a_product_unit_has_belongs_to_creator_relation()
    {
        $productUnit = ProductUnit::factory()->make();

        $this->assertInstanceOf(User::class, $productUnit->creator);
        $this->assertEquals($productUnit->creator_id, $productUnit->creator->id);
    }
}
