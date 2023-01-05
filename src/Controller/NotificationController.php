<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\NotificationRepository;

class NotificationController extends AbstractController
{
    public function notificationSum(NotificationRepository $notificationRep): Response
    {
        $userId = 200;

        // dd($notificationRep->notificationSum($userId));

        return $this->render(
            'partials/_notification.html.twig',
            ['notifications' => $notificationRep->notificationSum($userId)]
        );
    }
}
