<?php

namespace Tests\Feature;

use App\Models\ProductUnit;
use Tests\BrowserKitTest as TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProductUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_product_unit_list_in_product_unit_index_page()
    {
        $productUnit = ProductUnit::factory()->create();

        $this->loginAsUser();
        $this->visitRoute('product_units.index');
        $this->see($productUnit->title);
    }

    /** @test */
    public function user_can_create_a_product_unit()
    {
        $this->loginAsUser();
        $this->visitRoute('product_units.index');

        $this->click(__('product_unit.create'));
        $this->seeRouteIs('product_units.index', ['action' => 'create']);

        $this->submitForm(__('product_unit.create'), [
            'title'       => 'ProductUnit 1 title',
            'description' => 'ProductUnit 1 description',
        ]);

        $this->seeRouteIs('product_units.index');

        $this->seeInDatabase('product_units', [
            'title'       => 'ProductUnit 1 title',
            'description' => 'ProductUnit 1 description',
        ]);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'title'       => 'ProductUnit 1 title',
            'description' => 'ProductUnit 1 description',
        ], $overrides);
    }

    /** @test */
    public function validate_product_unit_title_is_required()
    {
        $this->loginAsUser();

        // title empty
        $this->post(route('product_units.store'), $this->getCreateFields(['title' => '']));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_product_unit_title_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // title 70 characters
        $this->post(route('product_units.store'), $this->getCreateFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_product_unit_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // description 256 characters
        $this->post(route('product_units.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_edit_a_product_unit_within_search_query()
    {
        $this->loginAsUser();
        $productUnit = ProductUnit::factory()->create(['title' => 'Testing 123']);

        $this->visitRoute('product_units.index', ['q' => '123']);
        $this->click('edit-product_unit-'.$productUnit->id);
        $this->seeRouteIs('product_units.index', ['action' => 'edit', 'id' => $productUnit->id, 'q' => '123']);

        $this->submitForm(__('product_unit.update'), [
            'title'       => 'ProductUnit 1 title',
            'description' => 'ProductUnit 1 description',
        ]);

        $this->seeRouteIs('product_units.index', ['q' => '123']);

        $this->seeInDatabase('product_units', [
            'title'       => 'ProductUnit 1 title',
            'description' => 'ProductUnit 1 description',
        ]);
    }

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'title'       => 'ProductUnit 1 title',
            'description' => 'ProductUnit 1 description',
        ], $overrides);
    }

    /** @test */
    public function validate_product_unit_title_update_is_required()
    {
        $this->loginAsUser();
        $product_unit = ProductUnit::factory()->create(['title' => 'Testing 123']);

        // title empty
        $this->patch(route('product_units.update', $product_unit), $this->getEditFields(['title' => '']));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_product_unit_title_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $product_unit = ProductUnit::factory()->create(['title' => 'Testing 123']);

        // title 70 characters
        $this->patch(route('product_units.update', $product_unit), $this->getEditFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_product_unit_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $product_unit = ProductUnit::factory()->create(['title' => 'Testing 123']);

        // description 256 characters
        $this->patch(route('product_units.update', $product_unit), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_product_unit()
    {
        $this->loginAsUser();
        $productUnit = ProductUnit::factory()->create();
        ProductUnit::factory()->create();

        $this->visitRoute('product_units.index', ['action' => 'edit', 'id' => $productUnit->id]);
        $this->click('del-product_unit-'.$productUnit->id);
        $this->seeRouteIs('product_units.index', ['action' => 'delete', 'id' => $productUnit->id]);

        $this->seeInDatabase('product_units', [
            'id' => $productUnit->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('product_units', [
            'id' => $productUnit->id,
        ]);
    }
}
