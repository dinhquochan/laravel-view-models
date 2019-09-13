<?php

namespace DinhQuocHan\ViewModels\Tests;

class ViewModelTest extends TestCase
{
    /** @var \DinhQuocHan\ViewModels\ViewModel */
    protected $viewModel;

    protected function setUp(): void
    {
        parent::setUp();

        DummyViewModel::macro('product', function () {
            return [
                'title' => 'title',
                'body' => 'body',
            ];
        });

        $this->viewModel = new DummyViewModel();
    }

    /** @test */
    public function public_methods_are_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayHasKey('post', $array);
        $this->assertArrayHasKey('categories', $array);
    }

    /** @test */
    public function public_properties_are_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayHasKey('property', $array);
    }

    /** @test */
    public function macro_methods_are_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayHasKey('product', $array);
    }

    /** @test */
    public function values_are_kept_as_they_are()
    {
        $array = $this->viewModel->toArray();

        $this->assertEquals('title', $array['post']->title);
    }

    /** @test */
    public function callables_can_be_stored()
    {
        $array = $this->viewModel->toArray();

        $this->assertEquals('foo', $array['callableMethod']('foo'));
    }

    /** @test */
    public function ignored_methods_are_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('ignoredMethod', $array);
    }

    /** @test */
    public function to_array_is_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('toArray', $array);
    }

    /** @test */
    public function magic_methods_are_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('__construct', $array);
    }
}
