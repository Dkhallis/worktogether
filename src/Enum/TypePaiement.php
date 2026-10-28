<?php

namespace App\Enum;

/**
 * Les façons dont un client peut payer sa commande.
 * Le paiement annuel donne droit à 10 % de remise supplémentaire.
 */

enum TypePaiement: string
{
    // Le client paie tous les mois
    case MENSUEL = 'mensuel';

    // Le client paie une fois par an
    case ANNUEL = 'annuel';
}
