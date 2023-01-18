
<?php

/*
 * This file is part of ianm/oauth-amazon.
 *
 * Copyright (c) 2021 IanM.
 *
 *  For the full copyright and license information, please view the LICENSE.md
 *  file that was distributed with this source code.
 */

namespace IanM\OAuthUCLAPI\Providers;

use Flarum\Forum\Auth\Registration;
use FoF\OAuth\Provider;
use League\OAuth2\Client\Provider\AbstractProvider;

class UCLAPI extends Provider
{
    /**
     * @var AmazonProvider
     */
    protected $provider;

    public function name(): string
    {
        return 'uclapi';
    }

    public function link(): string
    {
        return 'https://uclapi.com/docs/';
    }

    public function fields(): array
    {
        return [
            'client_id'     => 'required',
            'state' => 'required',
        ];
    }

    public function provider(string $redirectUri): AbstractProvider
    {
        return $this->provider = new AmazonProvider([
            'clientId'     => $this->getSetting('client_id'),
            'state' => $this->getSetting('state'),
            'redirectUri'  => $redirectUri,
        ]);
    }

    public function suggestions(Registration $registration, $user, string $token)
    {
        /** @var AmazonResourceOwner $user */
        $this->verifyEmail($email = $user->getEmail());

        $registration
            ->provideTrustedEmail($email)
            ->setPayload($user->toArray());
    }
}
