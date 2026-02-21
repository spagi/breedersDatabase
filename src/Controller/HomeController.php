<?php

namespace App\Controller;

use App\Repository\BreedRepository;
use App\Repository\KennelRepository;
use App\Repository\LitterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        KennelRepository $kennelRepo,
        BreedRepository $breedRepo,
        LitterRepository $litterRepo,
    ): Response {
        return $this->render('home/index.html.twig', [
            'latestKennels' => $kennelRepo->findLatest(6),
            'totalKennels' => $kennelRepo->countActive(),
            'totalLitters' => $litterRepo->countTotal(),
            'puppiesAvailable' => $litterRepo->findWithPuppiesAvailable(),
            'breeds' => $breedRepo->findAllOrderedByName(),
        ]);
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->render('home/dashboard.html.twig', [
            'user' => $user,
            'kennel' => $user->getKennel(),
        ]);
    }
}
