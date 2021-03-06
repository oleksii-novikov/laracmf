<?php

namespace App\Tests\Acceptance;

use GrahamCampbell\Credentials\Facades\Credentials;
use App\Tests\TestCase;
use App\Models\User;

class PageTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->authenticateUser(1);
    }

    public function testIndex()
    {
        $this->visit('/')->seePageIs('pages/home');
    }

    public function testCreate()
    {
        $this->visit('pages/create')->see('Create Page');
    }

    public function testShowFail()
    {
        $this->get('pages/error');

        $this->assertEquals(404, $this->response->status());
    }

    public function testShowSuccess()
    {
        $this->visit('pages/home')->see('Bootstrap CMS');
    }

    public function testEditHome()
    {
        $this->visit('pages/home/edit')->see('Edit Welcome');
    }

    public function testUpdateFail()
    {
        $this->patch('pages/home');

        $this->assertRedirectedTo('pages/home/edit');
        $this->assertSessionHasErrors();
        $this->assertHasOldInput();
    }

    public function testUpdateHomeUrl()
    {
        $this->patch('pages/home', [
            'title'      => 'New Page',
            'nav_title'  => 'Herro',
            'slug'       => 'foobar',
            'icon'       => '',
            'body'       => 'Why herro there!',
            'css'        => '',
            'js'         => '',
            'show_title' => 'on',
            'show_nav'   => 'on',

        ]);

        $this->assertRedirectedTo('pages/home/edit');
        $this->assertSessionHas('error');
        $this->assertHasOldInput();
    }

    public function testUpdateHomeNav()
    {
        $this->patch('pages/home', [
            'title'      => 'New Page',
            'nav_title'  => 'Herro',
            'slug'       => 'home',
            'icon'       => '',
            'body'       => 'Why herro there!',
            'css'        => '',
            'js'         => '',
            'show_title' => 'on',
            'show_nav'   => 'off',

        ]);

        $this->assertRedirectedTo('pages/home/edit');
        $this->assertSessionHas('error');
        $this->assertHasOldInput();
    }

    public function testUpdateSuccess()
    {
        $this->patch('pages/home', [
            'title'      => 'New Page',
            'nav_title'  => 'Herro',
            'slug'       => 'home',
            'icon'       => '',
            'body'       => 'Why hero there!',
            'css'        => '',
            'js'         => '',
            'show_title' => 'on',
            'show_nav'   => 'on',

        ]);

        $this->assertRedirectedTo('pages');
        $this->assertSessionHas('success');
    }

    public function testDestroyFail()
    {
        $this->delete('pages/home');

        $this->assertRedirectedTo('pages');
        $this->assertSessionHas('error');
    }

    public function testDestroySuccess()
    {
        $this->delete('pages/contact');

        $this->assertRedirectedTo('pages');
    }
}
