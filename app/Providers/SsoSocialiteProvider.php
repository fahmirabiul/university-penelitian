<?php

namespace App\Providers;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class SsoSocialiteProvider extends AbstractProvider
{
    /**
     * URL untuk melempar user ke halaman Login SSO.
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('http://university-sso.test/oauth/authorize', $state);
    }

    /**
     * URL untuk menukarkan authorization_code dengan Access Token di balik layar.
     */
    protected function getTokenUrl()
    {
        return 'http://university-sso.test/oauth/token';
    }

    /**
     * URL untuk meminta data profil user dari SSO setelah mendapatkan Access Token.
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('http://university-sso.test/api/user', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Standarisasi format data user yang didapat dari SSO agar dimengerti oleh Socialite.
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'    => $user['id'] ?? null,
            'name'  => $user['name'] ?? null,
            'email' => $user['email'] ?? null,
        ]);
    }
}
