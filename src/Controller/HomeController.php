<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Decision;
use App\Service\OpinionLike;
use App\Form\SearchTitleType;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_login');
    }
}
