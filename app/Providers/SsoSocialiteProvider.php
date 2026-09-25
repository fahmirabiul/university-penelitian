<?php

namespace App\Providers;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class SsoSocialiteProvider extends AbstractProvider
{
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('http://university-sso.test/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'http://university-sso.test/oauth/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('http://university-sso.test/api/v1/user', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        // Extract from 'data' wrapper if the SSO returns a successResponse() format.
        $userData = $user['data'] ?? $user;

        return (new User)->setRaw($userData)->map([
            'id'    => $userData['id'] ?? null,
            'name'  => $userData['profile']['full_name'] ?? $userData['name'] ?? null,
            'email' => $userData['email'] ?? null,
        ]);
    }
}
