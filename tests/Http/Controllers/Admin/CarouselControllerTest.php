<?php

use \App\Http\Controllers\Admin\CarouselController;
use \Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Http\Controllers\Admin\Controller as AdminController;

/**
 * Class CarouselControllerTest.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CarouselControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var App\User
     */
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
        $this->assertInstanceOf(AdminController::class, new CarouselController);
    }

    /**
     * @test
     */
    public function view_method_returns_view()
    {
        $controller = new CarouselController;

        $this->assertInstanceOf(\Illuminate\View\View::class, $controller->view());
    }

    /**
     * @test
     */
    public function can_create_new_slide_with_valid_input()
    {
        $this->actingAs($this->user)
            ->visit(route('admin.carousel'))
            ->type('Test title', 'title')
            ->type('Test caption', 'caption')
            ->attach(public_path('img/logo.png'), 'image')
            ->press('Toevoegen')
            ->see('De slide is toegevoegd aan de carousel');
    }

    /**
     * @test
     */
    public function cannot_create_new_slide_with_invalid_input()
    {
        $this->actingAs($this->user)
            ->visit(route('admin.carousel'))
            ->type('Test title', 'title')
            ->type('Test caption', 'caption')
            ->press('Toevoegen')
            ->see('Een of meer velden zijn niet ingevuld');
    }
}