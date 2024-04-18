<?php

namespace App\Controller;

use App\Entity\Mutuelles;
use App\Form\MutuellesType;
use App\Repository\MutuellesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mutuelles')]
class MutuellesController extends AbstractController
{
    #[Route('/', name: 'app_mutuelles_index', methods: ['GET'])]
    public function index(MutuellesRepository $mutuellesRepository): Response
    {
        return $this->render('mutuelles/index.html.twig', [
            'mutuelles' => $mutuellesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mutuelles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mutuelle = new Mutuelles();
        $form = $this->createForm(MutuellesType::class, $mutuelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mutuelle);
            $entityManager->flush();

            return $this->redirectToRoute('app_mutuelles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mutuelles/new.html.twig', [
            'mutuelle' => $mutuelle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mutuelles_show', methods: ['GET'])]
    public function show(Mutuelles $mutuelle): Response
    {
        return $this->render('mutuelles/show.html.twig', [
            'mutuelle' => $mutuelle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mutuelles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mutuelles $mutuelle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MutuellesType::class, $mutuelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mutuelles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mutuelles/edit.html.twig', [
            'mutuelle' => $mutuelle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mutuelles_delete', methods: ['POST'])]
    public function delete(Request $request, Mutuelles $mutuelle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mutuelle->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($mutuelle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mutuelles_index', [], Response::HTTP_SEE_OTHER);
    }
}
