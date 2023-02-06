<?php

namespace App\Twig;

use App\Entity\User;
use App\Entity\Expertise;
use Twig\TwigFunction;
use App\Entity\Decision;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('expertDecision', [$this, 'expertDecision']),
            new TwigFunction('calculateOpinion', [$this, 'calculateOpinion']),
            new TwigFunction('calculateExpert', [$this, 'calculateExpert']),
        ];
    }

    public function expertDecision(Decision $decision, User $user): bool
    {
        /** @var \App\Entity\Expertise */
        $expertises = $user->getExpertises();

        $departments = $decision->getDepartments();

        foreach ($expertises as $expertise) {
            foreach ($departments as $department) {
                if ($expertise->getDepartment() == $department and $expertise->isIsExpert() == true) {
                    return true;
                }
            }
        }

        return false;
    }

    public function calculateOpinion(Decision $decision): array
    {
        $like = 0;
        $dislike = 0;

        foreach ($decision->getOpinions() as $opinion) {
            $opinion->isIsLike() ? $like++ : $dislike++;
        }


        return ['like' => $like, 'dislike' => $dislike];
    }

    public function calculateExpert(Decision $decision): array
    {
        $like = 0;
        $dislike = 0;

        foreach ($decision->getValidations() as $validation) {
            $validation->isIsApproved() ? $like++ : $dislike++;
        }


        return ['like' => $like, 'dislike' => $dislike];
    }
}
