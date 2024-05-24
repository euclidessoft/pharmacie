<?php

namespace App\Controller;


use App\Entity\Vente;
use App\Entity\VenteProduit;
use App\Form\VenteType;
use App\Repository\ProduitRepository;
use App\Repository\VenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/vente')]
class VenteController extends AbstractController
{
    #[Route('/', name: 'app_vente_index', methods: ['GET'])]
    public function index(VenteRepository $venteRepository): Response
    {
        return $this->render('vente/index.html.twig', [
            'ventes' => $venteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $session->remove("commande");
        $session->remove("total");

        return $this->render('vente/new.html.twig', [
            'produits' => $produitRepository->findAll(),
            'commande' => $session->get('commande', []),
        ]);
    }

    #[Route("/valider/", name:"app_vente_valider")]
    public function valider(SessionInterface $session, ProduitRepository $produitRepository, EntityManagerInterface $em)
    {
        // if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {
        //$produits = $produitRepository->findAll();
        $commandesession = $session->get("commande", []);
        $vente = new  Vente();
        $em->persist($vente);

        if (count($commandesession) >= 1) {


            $i = 1;
            foreach ($commandesession as $ligne) {
                $product = explode("/",$ligne);
                $id = $product[0];
                $quantite= $product[1];
                $produit = $produitRepository->find($id);
                $venteProduit = new VenteProduit();
                $venteProduit->setVente($vente);
                $venteProduit->setProduit($produit);
                $venteProduit->setQuantite($quantite);
                $em->persist($venteProduit);
                $em->flush();
            }
            $em->flush();
            $session->remove("commande");
        }
        $this->addFlash('notice', 'Vente effectue avec succes');
        $response = $this->redirectToRoute('app_vente_new');
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setCache([
            'max_age' => 0,
            'private' => true,
        ]);
        return $response;
//        } else {
//            $response = $this->redirectToRoute('security_login');
//            $response->setSharedMaxAge(0);
//            $response->headers->addCacheControlDirective('no-cache', true);
//            $response->headers->addCacheControlDirective('no-store', true);
//            $response->headers->addCacheControlDirective('must-revalidate', true);
//            $response->setCache([
//                'max_age' => 0,
//                'private' => true,
//            ]);
//            return $response;
//        }
    }

    #[Route('/{id}', name: 'app_vente_show', methods: ['GET'])]
    public function show(Vente $vente): Response
    {
        return $this->render('vente/show.html.twig', [
            'vente' => $vente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vente $vente, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vente/edit.html.twig', [
            'vente' => $vente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vente_delete', methods: ['POST'])]
    public function delete(Request $request, Vente $vente, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vente->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($vente);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vente_index', [], Response::HTTP_SEE_OTHER);
    }
}
