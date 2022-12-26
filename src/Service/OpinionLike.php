<?php

namespace App\Service;

use App\Entity\Decision;

class OpinionLike
{
    public function calculateAllOpinion(array $decisions): array
    {
        $likeDislike = [];
        foreach ($decisions as $decision) {
            $likeDislike [] = $this->calculateOpinion($decision);
            // $decision['like'] = $likeDislike[0];
            // $decision['dislike'] = $likeDislike[1];
        }

        return $likeDislike;
    }

    private function calculateOpinion(Decision $decision): array
    {
        $like = 0;
        $dislike = 0;
        foreach ($decision->getOpinions() as $opinion) {
            $opinion->isIsLike() ? $like++ : $dislike++;
        }

        return ['like' => $like, 'dislike' => $dislike];
    }
}
