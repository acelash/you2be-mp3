<?php
namespace App\Extensions;


use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;
use Symfony\Component\HttpFoundation\Response;

class CustomCacheProfile extends CacheAllSuccessfulGetRequests
{
    /**
     * Determine if the given request should be cached;.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function shouldCacheRequest(Request $request)
    {
        if(!isset($request->route()->action)
            ||
            !array_key_exists('as',$request->route()->action)
            ||
            !in_array($request->route()->action['as'],["show_song_en",'show_song_ru']))
            return false;

        if ($request->ajax()) {
            return false;
        }

        if ($this->isRunningInConsole()) {
            return false;
        }

        if(auth()->check()){
            return false;
        }

        return $request->isMethod('get');
    }

    /**
     * Determine if the given response should be cached.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function shouldCacheResponse(Response $response)
    {
        if(auth()->check()){
            return false;
        }

        return $response->isSuccessful() || $response->isRedirection();
    }
}
