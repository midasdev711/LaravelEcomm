<?php

namespace Tests\Feature\Modules\Notification\Http\Controllers;

use Tests\TestCase;

/**
 * @see \Modules\Notification\Http\Controllers\NotificationController
 */
class NotificationControllerTest extends TestCase
{
    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->delete(route('notification.delete', ['id' => $id]));

        $response->assertRedirect(back());
        $this->assertModelMissing($notification);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get(route('all.notification'));

        $response->assertOk();
        $response->assertViewIs('notification::index');
        $response->assertViewHas('notifications');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get(route('admin.notification', ['id' => $id]));

        $response->assertRedirect($data->actionURL);

        // TODO: perform additional assertions
    }

    // test cases...
}
