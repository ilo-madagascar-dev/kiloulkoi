<?php

namespace App\Service;

use App\Entity\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class MercureCookieGenerator
{

    /**
     * @var string
     */
    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generate(User $user)
    {
        $token = (new Builder())
            ->set('mercure', ['subscribe' => [$_ENV['KILOUKOI_S_MERCURE_SUBSCRIBER_URL'] . $user->getId()]])
            ->sign(new Sha256(), $this->secret)
            ->getToken();

        return "mercureAuthorization={$token}; Path:/.well-known/mercure; HttpOnly;";
    }
}
