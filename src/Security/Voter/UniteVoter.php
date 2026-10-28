<?php

namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\Unite;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Voter qui décide si un client a le droit de modifier une unité.
 *
 * Règle : un client peut modifier une unité seulement si elle lui appartient,
 * c'est-à-dire si elle fait partie d'une de ses commandes.
 *
 * Utilisation dans un contrôleur :
 *     #[IsGranted(UniteVoter::MODIFIER, subject: 'unite')]
 */

final class UniteVoter extends Voter
{
    // Nom de la permission vérifiée par ce voter
    public const MODIFIER = 'UNITE_MODIFIER';

    /**
     * Est-ce que ce voter est concerné par la demande ?
     * Oui seulement si on demande la permission MODIFIER sur une Unite.
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::MODIFIER && $subject instanceof Unite;
    }

    /**
     * Est-ce qu'on autorise ? Renvoie true (oui) ou false (non).
     * $subject est l'unité concernée.
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, Vote|\Symfony\Component\Security\Core\Authorization\Voter\Vote|null $vote = null): bool    {

        // La personne connectée
        $user = $token->getUser();

        // Si ce n'est pas un client (admin, comptable ou personne), on refuse
        if (!$user instanceof Client) {
            return false;
        }

        // On regarde les commandes qui contiennent cette unité :
        // si l'une d'elles appartient au client connecté, on autorise
        foreach ($subject->getCommandes() as $commande) {
            if ($commande->getClient() === $user) {
                return true;
            }
        }
        // Aucune commande du client ne contient cette unité : on refuse
        return false;
    }
}
