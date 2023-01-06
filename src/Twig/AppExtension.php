<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Repository\NotificationRepository;

// class AppExtension extends AbstractExtension
// {
//     public NotificationRepository $notificationRep;

//     public function __construct(NotificationRepository $notification)
//     {
//         $this->notificationRep = $notification;
//     }

//     public function getFunctions()
//     {
//         return [
//             new TwigFunction('notificationSum', [$this, 'notificationSum']),
//         ];
//     }

//     public function notificationSum(int $userId): int
//     {
//         return ($this->notificationRep->getTotalByUser($userId));
//     }
// }
