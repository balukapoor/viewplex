<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Browser;

class BrowserCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        $browser = new Browser();
        $notCompatible = false;

        $platforms = [
            'windows' => [
                'code' => Browser::PLATFORM_WINDOWS,
                'supported' => 'supported'
            ],
            'windowsce' => [
                'code' => Browser::PLATFORM_WINDOWS_CE,
                'supported' => 'unknown'
            ],
            'apple' => [
                'code' => Browser::PLATFORM_APPLE,
                'supported' => 'supported'
            ],
            'linux' => [
                'code' => Browser::PLATFORM_LINUX,
                'supported' => 'supported'
            ],
            'android' => [
                'code' => Browser::PLATFORM_ANDROID,
                'supported' => 'unsupported'
            ],
            'os2' => [
                'code' => Browser::PLATFORM_OS2,
                'supported' => 'unknown'
            ],
            'BEOS' => [
                'code' => Browser::PLATFORM_BEOS,
                'supported' => 'unknown'
            ],
            'iphone' => [
                'code' => Browser::PLATFORM_IPHONE,
                'supported' => 'unsupported'
            ],
            'ipod' => [
                'code' => Browser::PLATFORM_IPOD,
                'supported' => 'unsupported'
            ],
            'blackberry' => [
                'code' => Browser::PLATFORM_BLACKBERRY,
                'supported' => 'unknown'
            ],
            'freebsd' => [
                'code' => Browser::PLATFORM_FREEBSD,
                'supported' => 'unknown'
            ],
            'openbsd' => [
                'code' => Browser::PLATFORM_OPENBSD,
                'supported' => 'unknown'
            ],
            'netbsd' => [
                'code' => Browser::PLATFORM_NETBSD,
                'supported' => 'unknown'
            ],
            'sunos' => [
                'code' => Browser::PLATFORM_SUNOS,
                'supported' => 'unknown'
            ],
            'opensolaris' => [
                'code' => Browser::PLATFORM_OPENSOLARIS,
                'supported' => 'unknown'
            ],
            'ipad' => [
                'code' => Browser::PLATFORM_IPAD,
                'supported' => 'supported'
            ],
        ];

        $browserList = [
            'opera' => [
                'code' => Browser::BROWSER_OPERA,
                'supported' => 'supported',
                'supported_from' => '36'
            ],
            'webtv' => [
                'code' => Browser::BROWSER_WEBTV,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'netpositive' => [
                'code' => Browser::BROWSER_NETPOSITIVE,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'edge' => [
                'code' => Browser::BROWSER_EDGE,
                'supported' => 'supported',
                'supported_from' => '25'
            ],
            'ie' => [
                'code' => Browser::BROWSER_IE,
                'supported' => 'supported',
                'supported_from' => '11'
            ],
            'pocketie' => [
                'code' => Browser::BROWSER_POCKET_IE,
                'supported' => 'unsupported',
                'supported_from' => null
            ],
            'galeon' => [
                'code' => Browser::BROWSER_GALEON,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'konqueror' => [
                'code' => Browser::BROWSER_KONQUEROR,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'icab' => [
                'code' => Browser::BROWSER_ICAB,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'omniweb' => [
                'code' => Browser::BROWSER_OMNIWEB,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'phoenix' => [
                'code' => Browser::BROWSER_PHOENIX,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'firebird' => [
                'code' => Browser::BROWSER_FIREBIRD,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'firefox' => [
                'code' => Browser::BROWSER_FIREFOX,
                'supported' => 'supported',
                'supported_from' => '38'
            ],
            'mozilla' => [
                'code' => Browser::BROWSER_MOZILLA,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'amaya' => [
                'code' => Browser::BROWSER_AMAYA,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'lynx' => [
                'code' => Browser::BROWSER_LYNX,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'safari' => [
                'code' => Browser::BROWSER_SAFARI,
                'supported' => 'supported',
                'supported_from' => '9'
            ],
            'iphone' => [
                'code' => Browser::BROWSER_IPHONE,
                'supported' => 'unsupported',
                'supported_from' => null
            ],
            'ipod' => [
                'code' => Browser::BROWSER_IPOD,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'android' => [
                'code' => Browser::BROWSER_ANDROID,
                'supported' => 'unsupported',
                'supported_from' => null
            ],
            'chrome' => [
                'code' => Browser::BROWSER_CHROME,
                'supported' => 'supported',
                'supported_from' => '40'
            ],
            'googlebot' => [
                'code' => Browser::BROWSER_GOOGLEBOT,
                'supported' => 'supported',
                'supported_from' => '0'
            ],
            'slurp' => [
                'code' => Browser::BROWSER_SLURP,
                'supported' => 'unknown',
                'supported_from' => null
            ],
            'w3c' => [
                'code' => Browser::BROWSER_W3CVALIDATOR,
                'supported' => 'supported',
                'supported_from' => '0'
            ],
            'blackberry' => [
                'code' => Browser::BROWSER_BLACKBERRY,
                'supported' => 'unknown',
                'supported_from' => null
            ],
        ];

        foreach ($browserList as $item) {

            if ($browser->getBrowser() == $item['code']) {

                if ($item['supported'] == 'unsupported') {
                    $notCompatible = true;
                    break;
                }

                if ($browser->getVersion() < $item['supported_from']) {
                    $notCompatible = true;
                    break;
                }

            }
        }




        if ($notCompatible) {
            return redirect('not-compatible');
        } else {
            return $next($request);
        }
    }
}
