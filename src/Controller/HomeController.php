<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public const USERID = 41;

    #[Route('/', name: 'app_home')]
    public function index()
    {
        return $this->redirectToRoute('app_login');
    }
}
