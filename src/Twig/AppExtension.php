<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('isNotificationDecisionExpert', [$this, 'isNotificationDecisionExpert']),
        ];
    }

    public function isNotificationDecisionExpert(int $decisionId, array $experts): bool
    {
        foreach ($experts as $expert) {
            foreach ($expert as $key => $valeur) {
                if (($key == 'decisionid') && ($valeur == $decisionId)) {
                    return true;
                }
            }
        }

        return false;
    }
}
