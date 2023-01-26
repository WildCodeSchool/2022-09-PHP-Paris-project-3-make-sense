<?php

namespace App\Service;

use App\Entity\Decision;
use App\Entity\History;
use App\Repository\HistoryRepository;

class UpdateHistory
{
    private HistoryRepository $historyRepository;

    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    public function updateHistory(Decision $decision): void
    {
        $history = new History();
        $history->setStatus($decision->getStatus());
        $history->setStartedAt($decision->getCreatedAt());
        $history->setEndedAt($decision->getEndAt());
        $history->setDecision($decision);

        $this->historyRepository->save($history, true);
    }
}
