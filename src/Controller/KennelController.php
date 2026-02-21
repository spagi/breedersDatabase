<?php

namespace App\Controller;

use App\Entity\Dog;
use App\Entity\Kennel;
use App\Entity\Litter;
use App\Entity\Puppy;
use App\Form\DogFormType;
use App\Form\KennelFormType;
use App\Form\LitterFormType;
use App\Repository\KennelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class KennelController extends AbstractController
{
    #[Route('/kennel/{slug}', name: 'app_kennel_show')]
    public function show(string $slug, KennelRepository $repo): Response
    {
        $kennel = $repo->findOneBy(['slug' => $slug, 'isActive' => true]);
        if (!$kennel) {
            throw $this->createNotFoundException();
        }

        return $this->render('kennel/show.html.twig', ['kennel' => $kennel]);
    }

    #[Route('/kennel/my/profile', name: 'app_kennel_my')]
    #[IsGranted('ROLE_USER')]
    public function myProfile(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user->hasKennel()) {
            return $this->redirectToRoute('app_kennel_new');
        }
        return $this->redirectToRoute('app_kennel_show', ['slug' => $user->getKennel()->getSlug()]);
    }

    #[Route('/kennel/new', name: 'app_kennel_new')]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if ($user->hasKennel()) {
            return $this->redirectToRoute('app_kennel_edit');
        }

        $kennel = new Kennel();
        $kennel->setOwner($user);
        $kennel->setEmail($user->getEmail());

        $form = $this->createForm(KennelFormType::class, $kennel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = strtolower($slugger->slug($kennel->getName())->toString());
            $kennel->setSlug($slug . '-' . uniqid());
            $kennel->setIsActive(true);
            $em->persist($kennel);
            $em->flush();

            $this->addFlash('success', 'flash.kennel_created');
            return $this->redirectToRoute('app_kennel_show', ['slug' => $kennel->getSlug()]);
        }

        return $this->render('kennel/new.html.twig', ['form' => $form]);
    }

    #[Route('/kennel/edit', name: 'app_kennel_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user->hasKennel()) {
            return $this->redirectToRoute('app_kennel_new');
        }

        $kennel = $user->getKennel();
        $form = $this->createForm(KennelFormType::class, $kennel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kennel->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', 'flash.kennel_updated');
            return $this->redirectToRoute('app_kennel_show', ['slug' => $kennel->getSlug()]);
        }

        return $this->render('kennel/edit.html.twig', ['form' => $form, 'kennel' => $kennel]);
    }

    // --- Dogs ---

    #[Route('/kennel/dogs/new', name: 'app_dog_new')]
    #[IsGranted('ROLE_USER')]
    public function newDog(Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user->hasKennel()) {
            return $this->redirectToRoute('app_kennel_new');
        }

        $dog = new Dog();
        $dog->setKennel($user->getKennel());
        $form = $this->createForm(DogFormType::class, $dog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($dog);
            $em->flush();
            $this->addFlash('success', 'flash.dog_added');
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('kennel/dog_form.html.twig', ['form' => $form, 'title' => 'dog.add_new']);
    }

    #[Route('/kennel/dogs/{id}/edit', name: 'app_dog_edit')]
    #[IsGranted('ROLE_USER')]
    public function editDog(Dog $dog, Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if ($dog->getKennel() !== $user->getKennel()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(DogFormType::class, $dog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'flash.dog_updated');
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('kennel/dog_form.html.twig', ['form' => $form, 'title' => 'dog.edit', 'dog' => $dog]);
    }

    // --- Litters ---

    #[Route('/kennel/litters/new', name: 'app_litter_new')]
    #[IsGranted('ROLE_USER')]
    public function newLitter(Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user->hasKennel()) {
            return $this->redirectToRoute('app_kennel_new');
        }

        $kennel = $user->getKennel();
        $litter = new Litter();
        $litter->setKennel($kennel);

        $form = $this->createForm(LitterFormType::class, $litter, ['kennel' => $kennel]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($litter);
            $em->flush();
            $this->addFlash('success', 'flash.litter_added');
            return $this->redirectToRoute('app_litter_show', ['id' => $litter->getId()]);
        }

        return $this->render('kennel/litter_form.html.twig', ['form' => $form, 'title' => 'litter.add_new']);
    }

    #[Route('/litter/{id}', name: 'app_litter_show')]
    public function showLitter(Litter $litter): Response
    {
        return $this->render('kennel/litter_show.html.twig', ['litter' => $litter]);
    }

    #[Route('/kennel/litters/{id}/edit', name: 'app_litter_edit')]
    #[IsGranted('ROLE_USER')]
    public function editLitter(Litter $litter, Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if ($litter->getKennel() !== $user->getKennel()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(LitterFormType::class, $litter, ['kennel' => $litter->getKennel()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'flash.litter_updated');
            return $this->redirectToRoute('app_litter_show', ['id' => $litter->getId()]);
        }

        return $this->render('kennel/litter_form.html.twig', ['form' => $form, 'title' => 'litter.edit', 'litter' => $litter]);
    }
}
