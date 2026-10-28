<?php

namespace App\Enum;

/**
 * Les états possibles d'une unité.
 * Une unité ne peut avoir qu'un seul de ces trois états.
 */

enum EtatUnite: string
{
    // L'unité fonctionne normalement
    case OK = 'ok';

    // L'unité a un problème (panne, coupure...)
    case INCIDENT = 'incident';

    // L'unité est en cours d'entretien
    case MAINTENANCE = 'maintenance';
}
