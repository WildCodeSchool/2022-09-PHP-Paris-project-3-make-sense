<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\History;
use App\Entity\Decision;
use App\Entity\Notification;
use App\Repository\HistoryRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */

class Workflow
{
    private HistoryRepository $historyRepository;
    private NotificationRepository $notificationRepository;
    private UserRepository $userRepository;

    public function __construct(
        HistoryRepository $historyRepository,
        NotificationRepository $notificationRepository,
        UserRepository $userRepository
    ) {
        $this->historyRepository = $historyRepository;
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
    }

    public function addHistory(Decision $decision): void
    {
        $history = new History();
        $history->setStatus($decision->getStatus());
        $history->setStartedAt($decision->getCreatedAt());
        $history->setEndedAt($decision->getEndAt());
        $history->setDecision($decision);

        $this->historyRepository->save($history, true);
    }

    public function addOneNotification(Decision $decision, User $user): void
    {
        $notification = $this->notificationRepository->findOneBy(
            [
                'user' => $user,
                'decision' => $decision
            ]
        );

        if (is_null($notification)) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setDecision($decision);
            $this->notificationRepository->save($notification, true);
        }
    }

    public function addNotifications(Decision $decision): void
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->addOneNotification($decision, $user);
        }
    }
}
