<?php

namespace App\Service;

class FeatureFlagService
{
    public function __construct(
        private bool $validationOffreAdmin,
    ) {
    }

    /**
     * Vérifie si la validation admin des offres est activée
     */
    public function isValidationOffreAdminEnabled(): bool
    {
        return $this->validationOffreAdmin;
    }
}
