<?php

namespace App\Controller\Admin;

use App\Entity\Breed;
use App\Entity\Kennel;
use App\Entity\User;
use App\Repository\BreedRepository;
use App\Repository\KennelRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin_')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'dashboard')]
    public function dashboard(
        UserRepository $userRepo,
        KennelRepository $kennelRepo,
        BreedRepository $breedRepo,
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => count($userRepo->findAll()),
            'totalKennels' => $kennelRepo->countActive(),
            'totalBreeds' => count($breedRepo->findAll()),
        ]);
    }

    // ---- Users ----

    #[Route('/users', name: 'users')]
    public function users(UserRepository $repo): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }

    #[Route('/users/{id}/toggle-admin', name: 'user_toggle_admin', methods: ['POST'])]
    public function toggleAdmin(User $user, EntityManagerInterface $em): Response
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            $user->setRoles(array_filter($roles, fn($r) => $r !== 'ROLE_ADMIN'));
        } else {
            $user->setRoles(array_merge($roles, ['ROLE_ADMIN']));
        }
        $em->flush();
        $this->addFlash('success', 'flash.user_updated');
        return $this->redirectToRoute('admin_users');
    }

    #[Route('/users/{id}/delete', name: 'user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'flash.user_deleted');
        return $this->redirectToRoute('admin_users');
    }

    // ---- Kennels ----

    #[Route('/kennels', name: 'kennels')]
    public function kennels(KennelRepository $repo): Response
    {
        return $this->render('admin/kennels.html.twig', [
            'kennels' => $repo->findAll(),
        ]);
    }

    #[Route('/kennels/{id}/toggle-active', name: 'kennel_toggle_active', methods: ['POST'])]
    public function toggleKennelActive(Kennel $kennel, EntityManagerInterface $em): Response
    {
        $kennel->setIsActive(!$kennel->isActive());
        $em->flush();
        $this->addFlash('success', 'flash.kennel_updated');
        return $this->redirectToRoute('admin_kennels');
    }

    #[Route('/kennels/{id}/toggle-verified', name: 'kennel_toggle_verified', methods: ['POST'])]
    public function toggleKennelVerified(Kennel $kennel, EntityManagerInterface $em): Response
    {
        $kennel->setIsVerified(!$kennel->isVerified());
        $em->flush();
        $this->addFlash('success', 'flash.kennel_updated');
        return $this->redirectToRoute('admin_kennels');
    }

    #[Route('/kennels/{id}/delete', name: 'kennel_delete', methods: ['POST'])]
    public function deleteKennel(Kennel $kennel, EntityManagerInterface $em): Response
    {
        $em->remove($kennel);
        $em->flush();
        $this->addFlash('success', 'flash.kennel_deleted');
        return $this->redirectToRoute('admin_kennels');
    }

    // ---- Breeds ----

    #[Route('/breeds', name: 'breeds')]
    public function breeds(BreedRepository $repo): Response
    {
        return $this->render('admin/breeds.html.twig', [
            'breeds' => $repo->findAllOrderedByName(),
        ]);
    }

    #[Route('/breeds/new', name: 'breed_new')]
    public function breedNew(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $breed = new Breed();
            $breed->setNameCz($request->request->get('nameCz'));
            $breed->setNameEn($request->request->get('nameEn'));
            $breed->setNameSk($request->request->get('nameSk'));
            $breed->setFicCode($request->request->get('ficCode'));
            $breed->setGroup($request->request->get('group'));
            $breed->setSize($request->request->get('size'));
            $em->persist($breed);
            $em->flush();
            $this->addFlash('success', 'flash.breed_created');
            return $this->redirectToRoute('admin_breeds');
        }
        return $this->render('admin/breed_form.html.twig', ['breed' => null]);
    }

    #[Route('/breeds/{id}/edit', name: 'breed_edit')]
    public function breedEdit(Breed $breed, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $breed->setNameCz($request->request->get('nameCz'));
            $breed->setNameEn($request->request->get('nameEn'));
            $breed->setNameSk($request->request->get('nameSk'));
            $breed->setFicCode($request->request->get('ficCode'));
            $breed->setGroup($request->request->get('group'));
            $breed->setSize($request->request->get('size'));
            $em->flush();
            $this->addFlash('success', 'flash.breed_updated');
            return $this->redirectToRoute('admin_breeds');
        }
        return $this->render('admin/breed_form.html.twig', ['breed' => $breed]);
    }

    #[Route('/breeds/{id}/delete', name: 'breed_delete', methods: ['POST'])]
    public function breedDelete(Breed $breed, EntityManagerInterface $em): Response
    {
        $em->remove($breed);
        $em->flush();
        $this->addFlash('success', 'flash.breed_deleted');
        return $this->redirectToRoute('admin_breeds');
    }
}
