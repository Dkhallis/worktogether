<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261022180945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE baie (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(10) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date_commande DATE NOT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL, reconduction_tacite TINYINT NOT NULL, type_paiement VARCHAR(255) NOT NULL, client_id INT NOT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande_unite (commande_id INT NOT NULL, unite_id INT NOT NULL, INDEX IDX_240C1EDB82EA2E54 (commande_id), INDEX IDX_240C1EDBEC4A74AB (unite_id), PRIMARY KEY (commande_id, unite_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, date_creation DATETIME NOT NULL, type VARCHAR(255) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, societe VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE intervention_unite (intervention_id INT NOT NULL, unite_id INT NOT NULL, INDEX IDX_6801DD9C8EAE3863 (intervention_id), INDEX IDX_6801DD9CEC4A74AB (unite_id), PRIMARY KEY (intervention_id, unite_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, commande_id INT NOT NULL, offre_id INT NOT NULL, INDEX IDX_3170B74B82EA2E54 (commande_id), INDEX IDX_3170B74B4CC8505A (offre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, nb_unites INT NOT NULL, prix_mensuel DOUBLE PRECISION NOT NULL, remise DOUBLE PRECISION NOT NULL, active TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_unite (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, couleur VARCHAR(20) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE unite (id INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, nom_personnalise VARCHAR(100) DEFAULT NULL, etat VARCHAR(255) NOT NULL, baie_id INT NOT NULL, type_unite_id INT DEFAULT NULL, INDEX IDX_1D64C11843375062 (baie_id), INDEX IDX_1D64C11821B55357 (type_unite_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE commande_unite ADD CONSTRAINT FK_240C1EDB82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_unite ADD CONSTRAINT FK_240C1EDBEC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_unite ADD CONSTRAINT FK_6801DD9C8EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_unite ADD CONSTRAINT FK_6801DD9CEC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C11843375062 FOREIGN KEY (baie_id) REFERENCES baie (id)');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C11821B55357 FOREIGN KEY (type_unite_id) REFERENCES type_unite (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande_unite DROP FOREIGN KEY FK_240C1EDB82EA2E54');
        $this->addSql('ALTER TABLE commande_unite DROP FOREIGN KEY FK_240C1EDBEC4A74AB');
        $this->addSql('ALTER TABLE intervention_unite DROP FOREIGN KEY FK_6801DD9C8EAE3863');
        $this->addSql('ALTER TABLE intervention_unite DROP FOREIGN KEY FK_6801DD9CEC4A74AB');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B4CC8505A');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C11843375062');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C11821B55357');
        $this->addSql('DROP TABLE baie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_unite');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE intervention_unite');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE type_unite');
        $this->addSql('DROP TABLE unite');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
