<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\DefinedUrl;
use Faker\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Faker\Generator as Faker;

class UrlShortenerController extends Controller
{



    public function index(Request $request)
    {
        $request->validate([
            "items" => "integer"
        ]);
        $urls = DefinedUrl::factory(4)->make()->toArray();

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'data' => $urls
        ]);
    }

    public function minify(UrlRequest $request)
    {
        return redirect()->back()->with("success",);
    }
}
