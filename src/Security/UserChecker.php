<?php
namespace App\Security;

use App\Entity\User as AppUser;
use App\Exception\AccountDeletedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->getActivationToken()) {
            throw new LockedException('Vous devez d\'abord activer votre compte.');
        }
        
        // user is deleted, show a generic Account Not Found message.
        /*if ($user->isDeleted()) {
            throw new AccountDeletedException();
        }*/
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        /*if ($user->isExpired()) {
            throw new AccountExpiredException('...');
        }*/
    }
}