<?php

namespace App\DataFixtures;

use App\Entity\Baie;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Intervention;
use App\Entity\LigneCommande;
use App\Entity\Offre;
use App\Entity\TypeUnite;
use App\Entity\Unite;
use App\Entity\Utilisateur;
use App\Enum\EtatUnite;
use App\Enum\TypeIntervention;
use App\Enum\TypePaiement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


/**
 * Données de test pour remplir la base.
 *
 * Commande pour les charger (attention, elle vide la base avant) :
 *     symfony console doctrine:fixtures:load
 */
class AppFixtures extends Fixture
{
    // Le service qui chiffre les mots de passe
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Les types d'unités (avec leur couleur)
        $types = [];
        foreach ([['Serveur web', 'bleu'], ['Base de données', 'vert'], ['Stockage', 'gris']] as [$libelle, $couleur]) {
            $type = new TypeUnite();
            $type->setLibelle($libelle);
            $type->setCouleur($couleur);
            $manager->persist($type);
            $types[] = $type;
        }

        // 2. Les 30 baies (B001 à B030), chacune avec 42 unités (U01 à U42)
        $unitesB001 = []; // On garde les unités de B001 pour les donner au client plus bas
        for ($b = 1; $b <= 30; $b++) {
            $baie = new Baie();
            // str_pad ajoute des 0 devant : 1 devient 001
            $baie->setNumero('B' . str_pad($b, 3, '0', STR_PAD_LEFT));
            $manager->persist($baie);

            for ($u = 1; $u <= 42; $u++) {
                $unite = new Unite();
                $unite->setNumero($u);
                $unite->setEtat(EtatUnite::OK);
                $unite->setBaie($baie);
                $manager->persist($unite);

                if ($b === 1) {
                    $unitesB001[] = $unite;
                }
            }
        }

        // 3. Les 4 offres du sujet : [nom, nombre d'unités, prix mensuel, remise en %] =====
        $offres = [];
        foreach ([
                     ['Base', 1, 100, 0],
                     ['Start-up', 10, 900, 10],
                     ['PME', 21, 1680, 20],
                     ['Entreprise', 42, 2940, 30],
                 ] as [$nom, $nbUnites, $prix, $remise]) {
            $offre = new Offre();
            $offre->setNom($nom);
            $offre->setNbUnites($nbUnites);
            $offre->setPrixMensuel($prix);
            $offre->setRemise($remise);
            $offre->setActive(true);
            $manager->persist($offre);
            $offres[$nom] = $offre; // rangées par nom pour les retrouver facilement
        }

        // 4. Le personnel : un administrateur et un comptable
        $admin = new Utilisateur();
        $admin->setEmail('admin@worktogether.fr');
        $admin->setNom('Martin');
        $admin->setPrenom('Alice');
        $admin->setRoles(['ROLE_ADMIN']);
        // Le mot de passe est chiffré avant d'être enregistré
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $admin->setDateCreation(new \DateTimeImmutable());
        $manager->persist($admin);

        $comptable = new Utilisateur();
        $comptable->setEmail('comptable@worktogether.fr');
        $comptable->setNom('Durand');
        $comptable->setPrenom('Paul');
        $comptable->setRoles(['ROLE_COMPTABLE']);
        $comptable->setPassword($this->hasher->hashPassword($comptable, 'comptable123'));
        $comptable->setDateCreation(new \DateTimeImmutable());
        $manager->persist($comptable);

        // 5. Un client de test
        // Pas besoin de lui donner ROLE_CLIENT : la classe Client l'ajoute toute seule
        $client = new Client();
        $client->setEmail('client@worktogether.fr');
        $client->setNom('Dupont');
        $client->setPrenom('Jean');
        $client->setTelephone('0601020304');
        $client->setAdresse('12 rue des Serveurs, 75000 Paris');
        $client->setSociete('Dupont SARL');
        $client->setPassword($this->hasher->hashPassword($client, 'client123'));
        $client->setDateCreation(new \DateTimeImmutable());
        $manager->persist($client);

        // 6. Une commande : le client a pris l'offre Start-up (10 unités), en annuel
        $commande = new Commande();
        $commande->setClient($client);
        $commande->setDateCommande(new \DateTimeImmutable());
        $commande->setDateDebut(new \DateTimeImmutable('today'));
        $commande->setDateFin(new \DateTimeImmutable('+1 year'));
        $commande->setTypePaiement(TypePaiement::ANNUEL);
        $commande->setReconductionTacite(true);
        $manager->persist($commande);

        // La ligne de commande : 1 offre Start-up à 900 €
        $ligne = new LigneCommande();
        $ligne->setCommande($commande);
        $ligne->setOffre($offres['Start-up']);
        $ligne->setQuantite(1);
        $ligne->setPrix(900);
        $manager->persist($ligne);

        // Les 10 premières unités de la baie B001 sont attribuées au client
        for ($i = 0; $i < 10; $i++) {
            $commande->addUnite($unitesB001[$i]);
        }

        // Le client a personnalisé deux unités
        $unitesB001[0]->setNomPersonnalise('Serveur web principal');
        $unitesB001[0]->setTypeUnite($types[0]);
        $unitesB001[1]->setNomPersonnalise('Base clients');
        $unitesB001[1]->setTypeUnite($types[1]);

        // 7. Une unité en incident, avec son intervention
        $unitesB001[2]->setEtat(EtatUnite::INCIDENT);

        $intervention = new Intervention();
        $intervention->setType(TypeIntervention::INCIDENT);
        $intervention->setDescription('Coupure électrique sur l\'unité B001-U03');
        $intervention->setDateDebut(new \DateTimeImmutable('-2 hours'));
        $intervention->addUnite($unitesB001[2]);
        $manager->persist($intervention);

        // On enregistre tout dans la base d'un coup
        $manager->flush();
    }
}
