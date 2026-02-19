<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'profile/update-details',
        'profile/update-password',
        'profile/delete',
        'profile/*',
        'api/*',
        'sanctum/*',
        'broadcasting/*',
        'livewire/*',
        'telescope/*',
        'horizon/*',
        'vendor/*',
        '_debugbar/*',
        'storage/*',
    ];

    /**
     * Get the CSRF token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (! $token && $header = $request->header('X-XSRF-TOKEN')) {
            $token = $this->encrypter->decrypt($header, static::serialized() === false);
        }

        return $token;
    }
}
