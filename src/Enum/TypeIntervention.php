<?php

namespace App\Enum;

/**
 * Les types possibles d'une intervention sur une ou plusieurs unités.
 */

enum TypeIntervention: string
{
    // Intervention suite à un problème
    case INCIDENT = 'incident';

    // Intervention prévue pour entretenir le matériel
    case MAINTENANCE = 'maintenance';
}
