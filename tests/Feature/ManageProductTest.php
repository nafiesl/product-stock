<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest as TestCase;

class ManageProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_product_list_in_product_index_page()
    {
        $product = Product::factory()->create();

        $this->loginAsUser();
        $this->visitRoute('products.index');
        $this->see($product->name);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'name'        => 'Product 1 name',
            'description' => 'Product 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_create_a_product()
    {
        $this->loginAsUser();
        $this->visitRoute('products.index');

        $this->click(__('product.create'));
        $this->seeRouteIs('products.create');

        $this->submitForm(__('product.create'), $this->getCreateFields());

        $this->seeRouteIs('products.show', Product::first());

        $this->seeInDatabase('products', $this->getCreateFields());
    }

    /** @test */
    public function validate_product_name_is_required()
    {
        $this->loginAsUser();

        // name empty
        $this->post(route('products.store'), $this->getCreateFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_product_name_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // name 70 characters
        $this->post(route('products.store'), $this->getCreateFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_product_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // description 256 characters
        $this->post(route('products.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'name'        => 'Product 1 name',
            'description' => 'Product 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_product()
    {
        $this->loginAsUser();
        $product = Product::factory()->create(['name' => 'Testing 123']);

        $this->visitRoute('products.show', $product);
        $this->click('edit-product-'.$product->id);
        $this->seeRouteIs('products.edit', $product);

        $this->submitForm(__('product.update'), $this->getEditFields());

        $this->seeRouteIs('products.show', $product);

        $this->seeInDatabase('products', $this->getEditFields([
            'id' => $product->id,
        ]));
    }

    /** @test */
    public function validate_product_name_update_is_required()
    {
        $this->loginAsUser();
        $product = Product::factory()->create(['name' => 'Testing 123']);

        // name empty
        $this->patch(route('products.update', $product), $this->getEditFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_product_name_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $product = Product::factory()->create(['name' => 'Testing 123']);

        // name 70 characters
        $this->patch(route('products.update', $product), $this->getEditFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_product_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $product = Product::factory()->create(['name' => 'Testing 123']);

        // description 256 characters
        $this->patch(route('products.update', $product), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_product()
    {
        $this->loginAsUser();
        $product = Product::factory()->create();
        Product::factory()->create();

        $this->visitRoute('products.edit', $product);
        $this->click('del-product-'.$product->id);
        $this->seeRouteIs('products.edit', [$product, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('products', [
            'id' => $product->id,
        ]);
    }
}
