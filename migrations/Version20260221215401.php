<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260221215401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial schema: user, breed, kennel, dog, litter, puppy, kennel_photo';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE breed (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name_cz VARCHAR(200) NOT NULL, name_en VARCHAR(200) NOT NULL, name_sk VARCHAR(200) DEFAULT NULL, fic_code VARCHAR(10) DEFAULT NULL, breed_group VARCHAR(50) DEFAULT NULL, size VARCHAR(50) DEFAULT NULL)');
        $this->addSql('CREATE TABLE dog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(200) NOT NULL, gender VARCHAR(10) NOT NULL, born_at DATE DEFAULT NULL, died_at DATE DEFAULT NULL, chip_number VARCHAR(50) DEFAULT NULL, registration_number VARCHAR(50) DEFAULT NULL, titles VARCHAR(100) DEFAULT NULL, description CLOB DEFAULT NULL, photo_filename VARCHAR(255) DEFAULT NULL, is_stud BOOLEAN NOT NULL, is_active BOOLEAN NOT NULL, kennel_id INTEGER NOT NULL, breed_id INTEGER NOT NULL, CONSTRAINT FK_812C397DF061503E FOREIGN KEY (kennel_id) REFERENCES kennel (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_812C397DA8B4A30F FOREIGN KEY (breed_id) REFERENCES breed (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_812C397DF061503E ON dog (kennel_id)');
        $this->addSql('CREATE INDEX IDX_812C397DA8B4A30F ON dog (breed_id)');
        $this->addSql('CREATE TABLE kennel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(200) NOT NULL, slug VARCHAR(220) NOT NULL, description_cz CLOB DEFAULT NULL, description_en CLOB DEFAULT NULL, description_sk CLOB DEFAULT NULL, city VARCHAR(200) DEFAULT NULL, region VARCHAR(100) DEFAULT NULL, country VARCHAR(3) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, logo_filename VARCHAR(255) DEFAULT NULL, cover_filename VARCHAR(255) DEFAULT NULL, purposes CLOB DEFAULT NULL, is_active BOOLEAN NOT NULL, is_verified BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, owner_id INTEGER NOT NULL, CONSTRAINT FK_471D728C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_471D728C989D9B62 ON kennel (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_471D728C7E3C61F9 ON kennel (owner_id)');
        $this->addSql('CREATE TABLE kennel_breed (kennel_id INTEGER NOT NULL, breed_id INTEGER NOT NULL, PRIMARY KEY (kennel_id, breed_id), CONSTRAINT FK_4F5117D8F061503E FOREIGN KEY (kennel_id) REFERENCES kennel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4F5117D8A8B4A30F FOREIGN KEY (breed_id) REFERENCES breed (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4F5117D8F061503E ON kennel_breed (kennel_id)');
        $this->addSql('CREATE INDEX IDX_4F5117D8A8B4A30F ON kennel_breed (breed_id)');
        $this->addSql('CREATE TABLE kennel_photo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, caption VARCHAR(255) DEFAULT NULL, position INTEGER NOT NULL, uploaded_at DATETIME NOT NULL, kennel_id INTEGER NOT NULL, CONSTRAINT FK_A3491B8FF061503E FOREIGN KEY (kennel_id) REFERENCES kennel (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A3491B8FF061503E ON kennel_photo (kennel_id)');
        $this->addSql('CREATE TABLE litter (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, litter_letter VARCHAR(10) DEFAULT NULL, born_at DATE DEFAULT NULL, available_from DATE DEFAULT NULL, total_puppies INTEGER NOT NULL, male_puppies INTEGER NOT NULL, female_puppies INTEGER NOT NULL, description CLOB DEFAULT NULL, is_planned BOOLEAN NOT NULL, has_puppies_available BOOLEAN NOT NULL, cover_photo_filename VARCHAR(255) DEFAULT NULL, kennel_id INTEGER NOT NULL, mother_id INTEGER DEFAULT NULL, father_id INTEGER DEFAULT NULL, CONSTRAINT FK_4BF2030BF061503E FOREIGN KEY (kennel_id) REFERENCES kennel (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4BF2030BB78A354D FOREIGN KEY (mother_id) REFERENCES dog (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4BF2030B2055B9A2 FOREIGN KEY (father_id) REFERENCES dog (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4BF2030BF061503E ON litter (kennel_id)');
        $this->addSql('CREATE INDEX IDX_4BF2030BB78A354D ON litter (mother_id)');
        $this->addSql('CREATE INDEX IDX_4BF2030B2055B9A2 ON litter (father_id)');
        $this->addSql('CREATE TABLE puppy (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(200) DEFAULT NULL, gender VARCHAR(10) NOT NULL, status VARCHAR(50) NOT NULL, color VARCHAR(100) DEFAULT NULL, photo_filename VARCHAR(255) DEFAULT NULL, notes CLOB DEFAULT NULL, litter_id INTEGER NOT NULL, CONSTRAINT FK_31069F42128AEA69 FOREIGN KEY (litter_id) REFERENCES litter (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_31069F42128AEA69 ON puppy (litter_id)');
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE breed');
        $this->addSql('DROP TABLE dog');
        $this->addSql('DROP TABLE kennel');
        $this->addSql('DROP TABLE kennel_breed');
        $this->addSql('DROP TABLE kennel_photo');
        $this->addSql('DROP TABLE litter');
        $this->addSql('DROP TABLE puppy');
        $this->addSql('DROP TABLE "user"');
    }
}
