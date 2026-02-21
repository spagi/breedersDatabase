<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260221215401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial schema: user, breed, kennel, dog, litter, puppy, kennel_photo (MySQL)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE breed (id INT AUTO_INCREMENT NOT NULL, name_cz VARCHAR(200) NOT NULL, name_en VARCHAR(200) NOT NULL, name_sk VARCHAR(200) DEFAULT NULL, fic_code VARCHAR(10) DEFAULT NULL, breed_group VARCHAR(50) DEFAULT NULL, size VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE kennel (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(200) NOT NULL, slug VARCHAR(220) NOT NULL, description_cz LONGTEXT DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, description_sk LONGTEXT DEFAULT NULL, city VARCHAR(200) DEFAULT NULL, region VARCHAR(100) DEFAULT NULL, country VARCHAR(3) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, logo_filename VARCHAR(255) DEFAULT NULL, cover_filename VARCHAR(255) DEFAULT NULL, purposes JSON DEFAULT NULL, is_active TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_471D728C989D9B62 (slug), UNIQUE INDEX UNIQ_471D728C7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE kennel_breed (kennel_id INT NOT NULL, breed_id INT NOT NULL, INDEX IDX_4F5117D8F061503E (kennel_id), INDEX IDX_4F5117D8A8B4A30F (breed_id), PRIMARY KEY(kennel_id, breed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE dog (id INT AUTO_INCREMENT NOT NULL, kennel_id INT NOT NULL, breed_id INT NOT NULL, name VARCHAR(200) NOT NULL, gender VARCHAR(10) NOT NULL, born_at DATE DEFAULT NULL, died_at DATE DEFAULT NULL, chip_number VARCHAR(50) DEFAULT NULL, registration_number VARCHAR(50) DEFAULT NULL, titles VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, photo_filename VARCHAR(255) DEFAULT NULL, is_stud TINYINT(1) NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_812C397DF061503E (kennel_id), INDEX IDX_812C397DA8B4A30F (breed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE kennel_photo (id INT AUTO_INCREMENT NOT NULL, kennel_id INT NOT NULL, filename VARCHAR(255) NOT NULL, caption VARCHAR(255) DEFAULT NULL, position INT NOT NULL, uploaded_at DATETIME NOT NULL, INDEX IDX_A3491B8FF061503E (kennel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE litter (id INT AUTO_INCREMENT NOT NULL, kennel_id INT NOT NULL, mother_id INT DEFAULT NULL, father_id INT DEFAULT NULL, litter_letter VARCHAR(10) DEFAULT NULL, born_at DATE DEFAULT NULL, available_from DATE DEFAULT NULL, total_puppies INT NOT NULL, male_puppies INT NOT NULL, female_puppies INT NOT NULL, description LONGTEXT DEFAULT NULL, is_planned TINYINT(1) NOT NULL, has_puppies_available TINYINT(1) NOT NULL, cover_photo_filename VARCHAR(255) DEFAULT NULL, INDEX IDX_4BF2030BF061503E (kennel_id), INDEX IDX_4BF2030BB78A354D (mother_id), INDEX IDX_4BF2030B2055B9A2 (father_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE puppy (id INT AUTO_INCREMENT NOT NULL, litter_id INT NOT NULL, name VARCHAR(200) DEFAULT NULL, gender VARCHAR(10) NOT NULL, status VARCHAR(50) NOT NULL, color VARCHAR(100) DEFAULT NULL, photo_filename VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_31069F42128AEA69 (litter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Foreign keys
        $this->addSql('ALTER TABLE kennel ADD CONSTRAINT FK_471D728C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE kennel_breed ADD CONSTRAINT FK_4F5117D8F061503E FOREIGN KEY (kennel_id) REFERENCES kennel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE kennel_breed ADD CONSTRAINT FK_4F5117D8A8B4A30F FOREIGN KEY (breed_id) REFERENCES breed (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dog ADD CONSTRAINT FK_812C397DF061503E FOREIGN KEY (kennel_id) REFERENCES kennel (id)');
        $this->addSql('ALTER TABLE dog ADD CONSTRAINT FK_812C397DA8B4A30F FOREIGN KEY (breed_id) REFERENCES breed (id)');
        $this->addSql('ALTER TABLE kennel_photo ADD CONSTRAINT FK_A3491B8FF061503E FOREIGN KEY (kennel_id) REFERENCES kennel (id)');
        $this->addSql('ALTER TABLE litter ADD CONSTRAINT FK_4BF2030BF061503E FOREIGN KEY (kennel_id) REFERENCES kennel (id)');
        $this->addSql('ALTER TABLE litter ADD CONSTRAINT FK_4BF2030BB78A354D FOREIGN KEY (mother_id) REFERENCES dog (id)');
        $this->addSql('ALTER TABLE litter ADD CONSTRAINT FK_4BF2030B2055B9A2 FOREIGN KEY (father_id) REFERENCES dog (id)');
        $this->addSql('ALTER TABLE puppy ADD CONSTRAINT FK_31069F42128AEA69 FOREIGN KEY (litter_id) REFERENCES litter (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE kennel DROP FOREIGN KEY FK_471D728C7E3C61F9');
        $this->addSql('ALTER TABLE kennel_breed DROP FOREIGN KEY FK_4F5117D8F061503E');
        $this->addSql('ALTER TABLE kennel_breed DROP FOREIGN KEY FK_4F5117D8A8B4A30F');
        $this->addSql('ALTER TABLE dog DROP FOREIGN KEY FK_812C397DF061503E');
        $this->addSql('ALTER TABLE dog DROP FOREIGN KEY FK_812C397DA8B4A30F');
        $this->addSql('ALTER TABLE kennel_photo DROP FOREIGN KEY FK_A3491B8FF061503E');
        $this->addSql('ALTER TABLE litter DROP FOREIGN KEY FK_4BF2030BF061503E');
        $this->addSql('ALTER TABLE litter DROP FOREIGN KEY FK_4BF2030BB78A354D');
        $this->addSql('ALTER TABLE litter DROP FOREIGN KEY FK_4BF2030B2055B9A2');
        $this->addSql('ALTER TABLE puppy DROP FOREIGN KEY FK_31069F42128AEA69');
        $this->addSql('DROP TABLE breed');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE kennel');
        $this->addSql('DROP TABLE kennel_breed');
        $this->addSql('DROP TABLE dog');
        $this->addSql('DROP TABLE kennel_photo');
        $this->addSql('DROP TABLE litter');
        $this->addSql('DROP TABLE puppy');
    }
}
