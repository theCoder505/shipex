<?php

namespace App\Http\Middleware;

use App\Models\Statistic;
use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class StatisticsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $agent = new Agent();
            $agent->setUserAgent($request->userAgent());

            // Basic bot detection
            $isBot = $agent->isRobot();

            // Device type
            $device = 'desktop';
            if ($agent->isMobile()) $device = 'mobile';
            elseif ($agent->isTablet()) $device = 'tablet';

            Statistic::create([
                'page_name'          => $this->resolvePageName($request),
                'page_url'           => $request->fullUrl(),
                'visitor_ip_address' => $request->ip(),
                'session_id'         => session()->getId(),
                'user_id'            => auth()->id(),
                'visitor_device'     => $device,
                'visitor_os'         => $agent->platform() ?? 'Unknown',
                'visitor_browser'    => $this->resolveBrowser($request, $agent),
                'visitor_country'    => $this->resolveCountry($request->ip()),
                'visitor_city'       => null,
                'referrer_url'       => $request->headers->get('referer'),
                'is_bot'             => $isBot,
            ]);
        } catch (\Exception $e) {
            logger()->error('Statistics middleware error: ' . $e->getMessage());
        }

        return $response;
    }

    private function resolveBrowser(Request $request, Agent $agent): string
    {
        // Brave sends Sec-CH-UA client hint header with its real identity
        $clientHint = strtolower($request->headers->get('Sec-CH-UA', ''));

        if (str_contains($clientHint, 'brave')) {
            return 'Brave';
        }

        $browser = $agent->browser() ?? 'Unknown';

        // Chrome on desktop could be Brave since Brave masks itself as Chrome
        if ($browser === 'Chrome' && $agent->isDesktop()) {
            return 'Chrome (or Brave)';
        }

        return $browser;
    }

    private function resolvePageName(Request $request): string
    {
        return $request->attributes->get('page_name')
            ?? $request->route()?->getName()
            ?? $request->path();
    }

    private function resolveCountry(string $ip): ?string
    {
        try {
            if (in_array($ip, ['127.0.0.1', '::1'])) return 'Local';
            $geo = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=country"), true);
            return $geo['country'] ?? null;
        } catch (\Exception) {
            return null;
        }
    }
}