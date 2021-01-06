<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest as TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_product_has_name_link_attribute()
    {
        $product = factory(Product::class)->create();

        $title = __('app.show_detail_title', [
            'name' => $product->name, 'type' => __('product.product'),
        ]);
        $link = '<a href="'.route('products.show', $product).'"';
        $link .= ' title="'.$title.'">';
        $link .= $product->name;
        $link .= '</a>';

        $this->assertEquals($link, $product->name_link);
    }

    /** @test */
    public function a_product_has_belongs_to_creator_relation()
    {
        $product = factory(Product::class)->make();

        $this->assertInstanceOf(User::class, $product->creator);
        $this->assertEquals($product->creator_id, $product->creator->id);
    }
}