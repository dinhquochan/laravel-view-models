# Laravel View Models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dinhquochan/laravel-view-models.svg?style=flat-square)](https://packagist.org/packages/dinhquochan/laravel-view-models)
[![Build Status](https://img.shields.io/travis/dinhquochan/laravel-view-models/master.svg?style=flat-square)](https://travis-ci.org/dinhquochan/laravel-view-models)
[![Total Downloads](https://img.shields.io/packagist/dt/dinhquochan/laravel-view-models.svg?style=flat-square)](https://packagist.org/packages/dinhquochan/laravel-view-models)


Laravel View Models in [Laravel](https://laravel.com/).

Have you ever made a controller where you had to do a lot of work to prepare variables to be passed to a view? You can move that kind of work to a so called view model.  In essence, view models are simple classes that take some data, and transform it into something usable for the view.

Forked from [spatie/laravel-view-models](https://github.com/spatie/laravel-view-models). You can extending your view models in Service Provider.

## Requirements

- PHP >= 7.2.0
- Laravel >= 6.0

## Installation

You can install the package via composer:

```bash
composer require dinhquochan/laravel-view-models
```

## Usage

A view model is a class where you can put some complex logic for your views. This will make your controllers a bit lighter.  You can create a view model by extending the provided `DinhQuocHan\ViewModels\ViewModel`.

```php
class PostViewModel extends ViewModel
{
    /** @var string|null */
    public $indexUrl = null;

    /**
     * Create a new view model instance.
     *
     * @param  \App\User  $user
     * @param  \App\post|null  $post
     * @return void
     */
    public function __construct($user, $post = null)
    {
        $this->user = $user;
        $this->post = $post;

        $this->indexUrl = action([PostsController::class, 'index']);
    }

    /**
     * Get post vairable.
     *
     * @return \App\Post|null
     */
    public function post()
    {
        return $this->post ?? new Post();
    }
}
```

And used in controllers like so:

```php
use App\ViewModels\PostViewModel;

class PostController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewModel = new PostViewModel(
            Auth::user()
        );

        return view('posts.create', $viewModel);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $viewModel = new PostViewModel(
            Auth::user(),
            $post
        );

        return view('posts.edit', $viewModel);
    }
}
```

In a view you can do this:

```blade
<input type="text" value="{{ $post->title }}" />
<input type="text" value="{{ $post->body }}" />

<a href="{{ $indexUrl }}">Back</a>
```

All public methods and properties in a view model are automatically exposed to the view. If you don't want a specific method to be available in your view, you can ignore it.

```php
class PostViewModel extends ViewModel
{
    /**
     *  The ignored public methods.
     *
     * @var array
     */
    protected $ignore = ['ignoredMethod'];

    // …

    /**
     *  The ignored public method.
     *
     * @var mixed
     */
    public function ignoredMethod() { /* … */ }
}
```

All PHP's built in magic methods are ignored automatically.

#### View models as responses

It's possible to directly return a view model from a controller.
By default, a JSON response with the data is returned.

```php
use App\ViewModels\PostViewModel;

class PostController
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // …

        return new PostViewModel($post);
    }
}
```

This approach can be useful when working with AJAX submitted forms.

It's also possible to return a view directly:

```php
use App\ViewModels\PostViewModel;

class PostController
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Post $post)
    {
        // …

        return (new PostViewModel($post))->view('posts.edit');
    }
}
```

Note that when the `Content-Type` header of the request is set to JSON,
this approach will also return JSON data instead of a rendered view.

#### Exposing view functions

View models can expose functions which require extra parameters.

```php
class PostViewModel extends ViewModel
{
    /**
     * Format date variable.
     *
     * @param  \Carbon\Carbon  $date
     * @return string
     */
    public function formatDate(Carbon $date)
    {
        return $date->format('Y-m-d');
    }
}
```

You can use these functions in the view like so:

```blade
{{ $formatDate($post->created_at) }}
```

### Making a new view model

The package included an artisan command to create a new view model.

```bash
php artisan make:view-model HomepageViewModel
```

This view model will have the `App\ViewModels` namespace and will be saved in `app/ViewModels`.

or into a custom namespace, say, `App\Blog`

```bash
php artisan make:view-model "Blog/PostsViewModel"
```

This view model will have the `App\Blog\ViewModels` namespace and will be saved in `app/Blog/ViewModels`.

### Extending

The `macro` method allows add more variables to view models without edit a file. Example in `Providers/AppServiceProvider.php`:

```php
use App\ViewModels\PostViewModel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        PostViewModel::macro('relatedPosts', function () {
            return 'Some posts';
        });
    }
}
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Đinh Quốc Hân](https://github.com/dinhquochan)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
