<?php

namespace App\Controller;

use App\Entity\Decision;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\NotificationRepository;

class NotificationController extends AbstractController
{
    public function notificationSum(NotificationRepository $notificationRep): Response
    {
        return $this->render(
            'partials/_notification.html.twig',
            ['notifications' => $notificationRep->getTotalByUser(HomeController::USERID)]
        );
    }
}
