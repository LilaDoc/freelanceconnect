<?php

namespace App\Twig;

use App\Service\FeatureFlagService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FeatureFlagExtension extends AbstractExtension
{
    public function __construct(
        private FeatureFlagService $featureFlagService
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('feature_enabled', [$this, 'isFeatureEnabled']),
        ];
    }

    public function isFeatureEnabled(string $feature): bool
    {
        return match ($feature) {
            'validation_offre_admin' => $this->featureFlagService->isValidationOffreAdminEnabled(),
            default => false,
        };
    }
}
