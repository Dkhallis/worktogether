<?php

namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\Unite;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UniteVoter extends Voter
{
    public const MODIFIER = 'UNITE_MODIFIER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::MODIFIER && $subject instanceof Unite;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, Vote|\Symfony\Component\Security\Core\Authorization\Voter\Vote|null $vote = null): bool    {
        $user = $token->getUser();

        if (!$user instanceof Client) {
            return false;
        }

        foreach ($subject->getCommandes() as $commande) {
            if ($commande->getClient() === $user) {
                return true;
            }
        }

        return false;
    }
}
