<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Un membre du personnel de WorkTogether (administrateur ou comptable).
 *
 * Il hérite de Compte : il récupère donc l'email, le mot de passe,
 * le nom, le prénom et les rôles. Il n'a rien de plus à stocker,
 * c'est pour ça que la classe est vide.
 *
 * Son rôle (ROLE_ADMIN ou ROLE_COMPTABLE) est stocké dans la propriété
 * "roles" de Compte.
 */

#[ORM\Entity]
class Utilisateur extends Compte
{
}
