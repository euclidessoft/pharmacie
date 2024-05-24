<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Entity\Fournisseur;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
//        $commande = new Commande();
//        $form = $this->createForm(CommandeType::class, $commande);
        $session->remove("commande");
//       $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($commande);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
//        }

        return $this->render('commande/new.html.twig', [
            'produits' => $produitRepository->findAll(),
            'commande' => $session->get('commande', []),
        //
          //  'form' => $form,
        ]);
    }

    #[Route('/commande_fournisseur', name: 'commande_produits', methods: ['GET', 'POST'])]
    public function commande_produits(Request $request, EntityManagerInterface $entityManager): Response
    {
        $varfournisseur = $request->get('fournisseur');
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($varfournisseur);
        $res = [];
        foreach ($fournisseur->getProduits() as $produit) {
            $pro['id'] = $produit->getId();
            $pro['reference'] = $produit->getReference();
            $pro['designation'] = $produit->getDesignation();
            $res[] = $pro;
        }
        $response = new Response();
        $response->headers->set('content-type', 'application/json');
        $re = json_encode($res);
        $response->setContent($re);
        return $response;
    }


    #[Route("/valider/", name:"app_commande_valider")]
    public function valider(SessionInterface $session, ProduitRepository $produitRepository, EntityManagerInterface $em)
    {
        // if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {
        //$produits = $produitRepository->findAll();
        $commandesession = $session->get("commande", []);
        $commande = new  Commande();
        $em->persist($commande);

        if (count($commandesession) >= 1) {


            $i = 1;
            foreach ($commandesession as $ligne) {
                $product = explode("/",$ligne);
                $id = $product[0];
                $quantite= $product[1];
                $produit = $produitRepository->find($id);
                $commandeproduit = new CommandeProduit();
                $commandeproduit->setCommande($commande);
                $commandeproduit->setProduit($produit);
                $commandeproduit->setQuantite($quantite);
                $em->persist($commandeproduit);
                $em->flush();
            }
            $em->flush();
            $session->remove("commande");
        }
        $this->addFlash('notice', 'Commande effectue avec succes');
        $response = $this->redirectToRoute('app_commande_index');
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

    #[Route('/Fournisseur/', name: 'commande_produit_fournisseur', methods: [ 'GET','POST'])]
    public function fournisseurs(Request $request, ProduitRepository $produitRepository, SessionInterface $session)
    {

        //if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $id = $request->get('produit');// recuperation de id produit

            $produit = $produitRepository->find($id); // recuperation de id produit dans la db
//

            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($produit->getFournisseur());
            $response->setContent($re);
            return $response;
        //}

    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/Add/', name: 'commande_produit_add', methods: [ 'POST'])]
    public function add(Request $request, ProduitRepository $produitRepository, SessionInterface $session)
    {
        // On récupère le panier actuel
        $commande = $session->get("commande", []);
        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $id = $request->get('produit');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde
            foreach ($commande as $key => $item) {
                $ligne = explode("/",$item);
                if ($ligne[0] == $id) {
                    $res['id'] = 'Un produit avec les même reference a été ajouté';
                    goto suite;
                }
            }
            $produit = $produitRepository->find($id); // recuperation de id produit dans la db
//            if (empty($approv[$id])) {//verification existance produit dans le panier

            $chaine = $id."/".$quantite;

            $commande[] = $chaine;

            // On sauvegarde dans la session
            $session->set("commande", $commande);

            //preparation valeur de retour ajax
            $res['id'] = 'ok';
            $res['ref'] = $produit->getReference();
            $res['designation'] = $produit->getDesignation();
            $res['quantite'] = $quantite;//$produit->getQuantite();

            suite:
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }

    }



    #[Route('/Delete/', name: 'commande_produit_delete', methods: [ 'POST'])]
    public function deleteproduit(Request $request, ProduitRepository $repository, SessionInterface $session)
    {
        // On récupère le panier actuel
        $commande = $session->get("commande", []);
        $id = $request->get('produit');
        foreach ($commande as $key => $item) {
            $ligne = explode("/",$item);
            if ($ligne[0] == $id) {
                unset($commande[$key]);
            }
        }

        // On sauvegarde dans la session
        $session->set("commande", $commande);


        $res['id'] = 'ok';
        $res['nb'] = count($commande);
        $response = new Response();
        $response->headers->set('content-type', 'application/json');
        $re = json_encode($res);
        $response->setContent($re);
        return $response;
    }



}
