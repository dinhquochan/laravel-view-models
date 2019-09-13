<?php

namespace DinhQuocHan\ViewModels\Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ViewModelMakeCommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_view_model()
    {
        $this->artisan('make:view-model', [
            'name' => 'HomeViewModel',
            '--force' => true,
        ])->expectsOutput('ViewModel created successfully.');

        $shouldOutputFilePath = $this->app['path'].'/ViewModels/HomeViewModel.php';

        $this->assertTrue(File::exists($shouldOutputFilePath), 'File exists in default app/ViewModels folder');

        $contents = File::get($shouldOutputFilePath);

        $this->assertTrue(Str::contains($contents, 'namespace App\ViewModels;'));

        $this->assertTrue(Str::contains($contents, 'class HomeViewModel extends ViewModel'));
    }

    /** @test */
    public function it_can_create_a_view_model_with_a_custom_namespace()
    {
        $this->artisan('make:view-model', [
            'name' => 'Blog/PostsViewModel',
            '--force' => true,
        ])->expectsOutput('ViewModel created successfully.');

        $shouldOutputFilePath = $this->app['path'].'/Blog/PostsViewModel.php';

        $this->assertTrue(File::exists($shouldOutputFilePath), 'File exists in custom app/Blog folder');

        $contents = File::get($shouldOutputFilePath);

        $this->assertTrue(Str::contains($contents, 'namespace App\Blog;'));

        $this->assertTrue(Str::contains($contents, 'class PostsViewModel extends ViewModel'));
    }
}
