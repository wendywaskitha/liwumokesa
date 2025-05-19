<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;

class TrackVisits
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track GET requests to avoid tracking form submissions
        if ($request->isMethod('GET')) {
            // Track based on the route
            $route = $request->route();

            if ($route) {
                $routeName = $route->getName();

                // Track destination visits
                if ($routeName === 'destinations.show' && $route->parameter('destination')) {
                    $destinationId = $route->parameter('destination')->id;
                    Visit::recordDestinationVisit($destinationId);
                }
                // Track other page visits
                else {
                    $path = $request->path();
                    Visit::recordPageVisit($path);
                }
            }
        }

        return $response;
    }
}
