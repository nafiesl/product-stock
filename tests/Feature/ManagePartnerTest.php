<?php

namespace Tests\Feature;

use App\Models\Partner;
use Tests\BrowserKitTest as TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagePartnerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_partner_list_in_partner_index_page()
    {
        $partner = Partner::factory()->create();

        $this->loginAsUser();
        $this->visitRoute('partners.index');
        $this->see($partner->name);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'name'        => 'Partner 1 name',
            'description' => 'Partner 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_create_a_partner()
    {
        $this->loginAsUser();
        $this->visitRoute('partners.index');

        $this->click(__('partner.create'));
        $this->seeRouteIs('partners.create');

        $this->submitForm(__('partner.create'), $this->getCreateFields());

        $this->seeRouteIs('partners.show', Partner::first());

        $this->seeInDatabase('partners', $this->getCreateFields());
    }

    /** @test */
    public function validate_partner_name_is_required()
    {
        $this->loginAsUser();

        // name empty
        $this->post(route('partners.store'), $this->getCreateFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_partner_name_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // name 70 characters
        $this->post(route('partners.store'), $this->getCreateFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_partner_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // description 256 characters
        $this->post(route('partners.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'name'        => 'Partner 1 name',
            'description' => 'Partner 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_partner()
    {
        $this->loginAsUser();
        $partner = Partner::factory()->create(['name' => 'Testing 123']);

        $this->visitRoute('partners.show', $partner);
        $this->click('edit-partner-'.$partner->id);
        $this->seeRouteIs('partners.edit', $partner);

        $this->submitForm(__('partner.update'), $this->getEditFields());

        $this->seeRouteIs('partners.show', $partner);

        $this->seeInDatabase('partners', $this->getEditFields([
            'id' => $partner->id,
        ]));
    }

    /** @test */
    public function validate_partner_name_update_is_required()
    {
        $this->loginAsUser();
        $partner = Partner::factory()->create(['name' => 'Testing 123']);

        // name empty
        $this->patch(route('partners.update', $partner), $this->getEditFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_partner_name_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $partner = Partner::factory()->create(['name' => 'Testing 123']);

        // name 70 characters
        $this->patch(route('partners.update', $partner), $this->getEditFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_partner_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $partner = Partner::factory()->create(['name' => 'Testing 123']);

        // description 256 characters
        $this->patch(route('partners.update', $partner), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_partner()
    {
        $this->loginAsUser();
        $partner = Partner::factory()->create();
        Partner::factory()->create();

        $this->visitRoute('partners.edit', $partner);
        $this->click('del-partner-'.$partner->id);
        $this->seeRouteIs('partners.edit', [$partner, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('partners', [
            'id' => $partner->id,
        ]);
    }
}
