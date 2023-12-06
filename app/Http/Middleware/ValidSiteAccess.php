<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SellerSite;

class ValidSiteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {


        $site_auth_key = $request->headers->get("siteauthkey");
        $site_url = $request->headers->get("siteurl");

        if (!isset($site_auth_key) || !isset($site_url)) {
            $data = $request->all();
            $site_auth_key = $data['auth_key'];
            $site_url = $data['site_url'];
        }
        if (!isset($site_auth_key) || !isset($site_url)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if (SellerSite::where('site_url', '=', $site_url)->where('site_auth_key', '=', $site_auth_key)->where('is_active', '=', 1)->exists()) {
            return $next($request);
        }
        return response()->json(['error' => 'Forbidden'], 403);
    }
}
