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
