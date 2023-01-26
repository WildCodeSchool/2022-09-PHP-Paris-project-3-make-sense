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

    public function update(Decision $decision, string $status): void
    {
        $history = new History();
        $history->setStatus($status);
        $history->setStartedAt($decision->getCreatedAt());
        $history->setEndedAt($decision->getEndAt());
        $history->setDecision($decision);

        $this->historyRepository->save($history, true);
    }
}
