<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Controller as AdminController;

class DashboardControllerTest extends TestCase
{
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->createUser(true, true, '11111', '11111');
    }

    /**
     * @test
     */
    public function extends_admin_controller()
    {
        $this->assertInstanceOf(AdminController::class, new DashboardController);
    }

    /**
     * @test
     */
    public function view_method_returns_view()
    {
        $controller = new DashboardController;

        $this->assertInstanceOf(\Illuminate\View\View::class, $controller->view());
    }

    /**
     * @test
     */
    public function stats_method_returns_json()
    {
        /* @var Illuminate\Http\Response $response */
        $this->get(route('admin.dashboard::stats'))
            ->isJson();
    }

    /**
     * @test
     */
    public function sends_valid_order_chart_data()
    {
        $this->actingAs($this->user)
            ->get(route('admin.dashboard::chart', ['type' => 'orders']))
            ->seeJson([
                'message' => "Chart data for chart 'orders'",
            ]);
    }

    /**
     * @test
     */
    public function sends_400_error_on_invalid_chart_type()
    {
        $this->actingAs($this->user)
            ->get(route('admin.dashboard::chart', ['type' => 'invalid']))
            ->seeJson([
                'message' => 'Unknown chart type',
            ])
            ->assertResponseStatus(400);
    }

    /**
     * @test
     */
    public function dashboard_has_server_stats_card()
    {
        $this->actingAs($this->user)
            ->get(route('admin.dashboard'))
            ->see('Server stats');
    }

    /**
     * @test
     */
    public function dashboard_shows_latest_import_status()
    {
        $this->actingAs($this->user)
            ->get(route('admin.dashboard'))
            ->see('Korting import')
            ->see('Product import');
    }

    /**
     * @test
     */
    public function dashboard_shows_order_count_chart()
    {
        $this->actingAs($this->user)
            ->get(route('admin.dashboard'))
            ->see('Orders per maand');
    }
}
