<?php

namespace Tests\Unit\Policies;

use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTest as TestCase;

class PartnerPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_partner()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Partner));
    }

    /** @test */
    public function user_can_view_partner()
    {
        $user = $this->createUser();
        $partner = Partner::factory()->create();
        $this->assertTrue($user->can('view', $partner));
    }

    /** @test */
    public function user_can_update_partner()
    {
        $user = $this->createUser();
        $partner = Partner::factory()->create();
        $this->assertTrue($user->can('update', $partner));
    }

    /** @test */
    public function user_can_delete_partner()
    {
        $user = $this->createUser();
        $partner = Partner::factory()->create();
        $this->assertTrue($user->can('delete', $partner));
    }
}
