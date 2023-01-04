<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ExpertiseRepository;
use App\Repository\NotificationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotificationController extends AbstractController
{
    public const NOTIFICATIONS_MESSAGE =
    [
        "En cours" => "veuillez donner votre avis sur cette décision",
        "1ère décision" => "cette décision est en attente de la décision finale",
        "Conflit" => "cette décision est en attente de la décision des experts",
        "Aboutie" => "La décision a été prise",
        "Non Aboutie" => "La décision a été prise",
    ];

    public const NOTIFICATIONS_BUTTON =
    [
        "En cours" => "Donner son avis",
        "1ère décision" => "Compte-rendu",
        "Conflit" => "Accord ou refus",
        "Aboutie" => "",
        "Non Aboutie" => "",
    ];

    #[Route('/notification', name: 'app_notification')]
    public function index(
        NotificationRepository $notificationRepository,
        UserRepository $userRepository,
        ExpertiseRepository $expertiseRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $userId = 200;

        $user = $userRepository->findOneBy(['id' => $userId]);
        // dd($user);
        // dd($expertiseRepository->countExpertiseByDecision($userId));
        // dd($notificationRepository->findAllNotification($userId));

        $notifications = $paginator->paginate(
            $notificationRepository->findAllNotification($userId), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );

        return $this->render(
            'notification/index.html.twig',
            [
                'notifications' => $notifications,
                'experts' => $expertiseRepository->countExpertiseByDecision($userId),
                'messages' => self::NOTIFICATIONS_MESSAGE,
                'buttons' => self::NOTIFICATIONS_BUTTON,
                'user' => $user
            ]
        );
    }
}
