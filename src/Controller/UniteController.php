<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Unite;
use App\Form\UniteType;
use App\Repository\UniteRepository;
use App\Security\Voter\UniteVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;


/**
 * Pages de l'espace client pour gérer ses unités.
 *
 * Toutes les pages de ce contrôleur sont réservées aux clients :
 * un visiteur non connecté est renvoyé vers la page de connexion,
 * un administrateur ou un comptable reçoit une erreur 403.
 */
#[IsGranted('ROLE_CLIENT')]
class UniteController extends AbstractController
{
    /**
     * Page d'accueil : redirige directement vers la liste des unités.
     */
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_mes_unites');
    }

    /**
     * Liste des unités du client connecté.
     * #[CurrentUser] donne directement le client connecté à la méthode.
     */
    #[Route('/mes-unites', name: 'app_mes_unites')]
    public function index(#[CurrentUser] Client $client, UniteRepository $uniteRepository): Response
    {
        return $this->render('unite/index.html.twig', [
            'unites' => $uniteRepository->findByClient($client),
        ]);
    }

    /**
     * Modification du nom et du type d'une unité.
     *
     * L'unité est retrouvée automatiquement grâce à l'{id} de l'adresse.
     * Le UniteVoter vérifie avant tout qu'elle appartient au client connecté,
     * sinon on obtient une erreur 403.
     */
    #[Route('/mes-unites/{id}/modifier', name: 'app_unite_modifier')]
    #[IsGranted(UniteVoter::MODIFIER, subject: 'unite')]
    public function modifier(Unite $unite, Request $request, EntityManagerInterface $entityManager): Response
    {

        // On crée le formulaire, pré-rempli avec les informations de l'unité
        $form = $this->createForm(UniteType::class, $unite);
        $form->handleRequest($request);

        // Si le formulaire a été envoyé et qu'il est correct
        if ($form->isSubmitted() && $form->isValid()) {
            // On enregistre les modifications en base
            $entityManager->flush();

            // Message de confirmation affiché sur la page suivante
            $this->addFlash('success', 'L\'unité ' . $unite->getEmplacement() . ' a été modifiée.');

            return $this->redirectToRoute('app_mes_unites');
        }
        // Sinon, on affiche le formulaire
        return $this->render('unite/modifier.html.twig', [
            'unite' => $unite,
            'form' => $form,
        ]);
    }
}
