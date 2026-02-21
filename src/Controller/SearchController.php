<?php

namespace App\Controller;

use App\Entity\Kennel;
use App\Repository\BreedRepository;
use App\Repository\KennelRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(
        Request $request,
        KennelRepository $kennelRepo,
        BreedRepository $breedRepo,
        PaginatorInterface $paginator,
    ): Response {
        $filters = [
            'name' => $request->query->get('name', ''),
            'breed' => $request->query->get('breed', ''),
            'country' => $request->query->get('country', ''),
            'region' => $request->query->get('region', ''),
            'purpose' => $request->query->get('purpose', ''),
        ];

        $qb = $kennelRepo->createSearchQueryBuilder(array_filter($filters));

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('search/index.html.twig', [
            'pagination' => $pagination,
            'filters' => $filters,
            'breeds' => $breedRepo->findAllOrderedByName(),
            'purposes' => Kennel::PURPOSES,
            'countries' => ['CZE' => 'Česká republika', 'SVK' => 'Slovensko', 'POL' => 'Polsko', 'DEU' => 'Německo', 'AUT' => 'Rakousko'],
        ]);
    }
}
