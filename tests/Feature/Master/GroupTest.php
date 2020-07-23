<?php

namespace Tests\Feature\Master;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupTest extends TestCase
{
    /**
     * A basic feature create test.
     *
     * @return void
     */
    public function test_group_create()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/master/group', [
            'name' => 'group a'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/group');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_group_browse()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/master/group');

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_group_show()
    {
        $user = User::find(1);
        factory(Group::class, 10)->create();
        $response = $this->actingAs($user)->get('/master/group/' . Group::inRandomOrder()->first()->id);

        $response->assertStatus(200);
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_group_update()
    {
        $user = User::find(1);
        factory(Group::class, 10)->create();
        $response = $this->actingAs($user)->put('/master/group/' . Group::inRandomOrder()->first()->id,[
            'name' => 'group b'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/master/group');
    }

    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_group_delete()
    {
        $user = User::find(1);
        factory(Group::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/group/' . Group::inRandomOrder()->first()->id);

        $response->assertStatus(302);
    }


    /**
     * A basic feature browse test.
     *
     * @return void
     */
    public function test_group_bulk_delete()
    {
        $user = User::find(1);
        factory(Group::class, 10)->create();
        $response = $this->actingAs($user)->delete('/master/group/bulk-destroy', [
            'ids' => Group::inRandomOrder()->get()->pluck('id')
        ]);

        $response->assertStatus(302);
    }

}