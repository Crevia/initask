<?php

namespace App\Http\Controllers;

use App\Domains\UrlShortener\Repositories\UrlFastRepository as RepositoriesUrlFastRepository;
use App\Domains\UrlShortener\Repositories\UrlRepository;
use App\Domains\UrlShortener\Services\UrlRedirectService;
use App\Domains\UrlShortener\Services\UrlShortenerService;
use App\Domains\UrlShortener\UrlValidations\SafeUrl\GoogleSafeUrlValidation;
use App\Http\Requests\UrlRequest;
use App\Models\DefinedUrl;
use Faker\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Ulid;
use UrlFastRepository;

class UrlShortenerController extends Controller
{
    protected $sessionKey;


    public function index(Request $request)
    {

        $sessionKey = substr(Session::getId(), 0, 26);

        $request->validate([
            "items" => "integer"
        ]);
        $urls = DefinedUrl::where("session_id", $sessionKey)->select("defined_urls.*")
            ->join("url_owners", "url_owners.defined_url_id", "=", "defined_urls.id")->get()
            ->toArray();
        //pagination implemetation

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
        //get url
        $url = $request->get("url");
        $validators = [];
        $sessionKey = substr(Session::getId(), 0, 26);
        $shortnerservice = new UrlShortenerService(new UrlRepository(new DefinedUrl));
        $safeurlgoogle = new GoogleSafeUrlValidation;
        $safeurlgoogle->setApiKey(env("GOOGLE_ACCESS_KEY_ID"));
        $safeurlgoogle->setRepository(new RepositoriesUrlFastRepository(new Cache));
        $validators[] = $safeurlgoogle;
        $shortnerservice->setUrlValidators($validators);
        $urlEntity =   $shortnerservice->shortenUrl($url);
        $data = ['session_id' => $sessionKey, "defined_url_id" => $urlEntity->getKey()];
        //bind it to user session
        DB::table("url_owners")->updateOrInsert($data, $data);

        return redirect()->back()->with("success");
    }

    public function redirectUrl(Request $request)
    {
        ///implement the redirection
        //in such away that the url is redirected depending on the specifications specified by the handler
        $path = $request->path();
        $rawhash = array_reverse(explode("/", $path));

        throw_if(empty($rawhash[0]), new NotFoundHttpException);

        $responseservice = (new UrlRedirectService())->setRepository(new UrlRepository(new DefinedUrl));
        $tt = $responseservice->handleRedirectRequest($rawhash[0]);
        throw_if(empty($tt), new NotFoundHttpException);

        return  redirect($tt->getTo());
    }
}
