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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class UniteController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_mes_unites');
    }

    #[Route('/mes-unites', name: 'app_mes_unites')]
    public function index(UniteRepository $uniteRepository): Response
    {
        $client = $this->getClientConnecte();

        return $this->render('unite/index.html.twig', [
            'unites' => $uniteRepository->findByClient($client),
        ]);
    }

    #[Route('/mes-unites/{id}/modifier', name: 'app_unite_modifier')]
    #[IsGranted(UniteVoter::MODIFIER, subject: 'unite')]
    public function modifier(Unite $unite, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(UniteType::class, $unite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'L\'unité ' . $unite->getEmplacement() . ' a été modifiée.');

            return $this->redirectToRoute('app_mes_unites');
        }

        return $this->render('unite/modifier.html.twig', [
            'unite' => $unite,
            'form' => $form,
        ]);
    }

    private function getClientConnecte(): Client
    {
        $user = $this->getUser();

        if (!$user instanceof Client) {
            throw $this->createAccessDeniedException('Cet espace est réservé aux clients.');
        }

        return $user;
    }


}
