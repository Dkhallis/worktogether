Projet WorkTogether

Ce repository contient mon travail pour la première étape du projet WorkTogether.

Ce qui était demandé

Pour ce devoir, il fallait :

- Installer le projet Symfony
- Mettre en place le repository GitHub
- Organiser le projet avec un Kanban et un planning
- Créer les entités Doctrine et les fixtures à partir de la correction du diagramme UML
- Mettre en place la connexion, la déconnexion et la création de compte
- Créer quelques pages pour gérer les unités d'un utilisateur connecté

Ce que j'ai fait : 

- J'ai installé le projet Symfony et je l'ai mis sur GitHub. La base de données MySQL tourne avec Docker.
- J'ai organisé le projet dans l'onglet Projects du repository, avec un Kanban pour suivre les tâches et une Roadmap pour le planning.
- J'ai créé toutes les entités du diagramme UML corrigé : Compte, Client, Utilisateur, Baie, Unite, TypeUnite, Offre, Commande, LigneCommande et Intervention, ainsi que les énumérations EtatUnite, TypePaiement et TypeIntervention. Client et Utilisateur héritent de Compte, et les rôles sont stockés dans Compte comme demandé dans la correction.
- J'ai créé des fixtures qui ajoutent les 30 baies avec leurs 42 unités, les 4 offres du sujet, un client, un administrateur et un comptable.
- J'ai mis en place la connexion, la déconnexion et la création de compte. Quand on crée un compte, c'est un compte client.
- J'ai créé une page où le client connecté voit la liste de ses unités, avec leur emplacement, leur nom, leur type et leur état. Il peut aussi modifier le nom et le type de chaque unité. J'ai ajouté une vérification pour qu'un client ne puisse pas voir ou modifier les unités d'un autre client.


Ce qu'il reste à faire pour la suite du projet : 

Les pages de présentation de l'entreprise et des offres, la commande et le paiement, l'espace client complet, l'API et l'outil de gestion pour le personnel.

Pour tester

Pour lancer le projet :

bash
-> composer install
-> docker compose up -d
-> symfony console doctrine:migrations:migrate
-> symfony console doctrine:fixtures:load
-> symfony serve -d

Ensuite, aller sur https://127.0.0.1:8000 et se connecter avec le compte client@worktogether.fr et le mot de passe client123.
