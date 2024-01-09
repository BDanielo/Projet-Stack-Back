<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109113944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_user DROP CONSTRAINT fk_cefecca7979b1ad6');
        $this->addSql('ALTER TABLE company_user DROP CONSTRAINT fk_cefecca7a76ed395');
        $this->addSql('DROP TABLE company_user');
        $this->addSql('ALTER TABLE "user" ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD firstname VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD lastname VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649979B1AD6 ON "user" (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE company_user (company_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(company_id, user_id))');
        $this->addSql('CREATE INDEX idx_cefecca7a76ed395 ON company_user (user_id)');
        $this->addSql('CREATE INDEX idx_cefecca7979b1ad6 ON company_user (company_id)');
        $this->addSql('ALTER TABLE company_user ADD CONSTRAINT fk_cefecca7979b1ad6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company_user ADD CONSTRAINT fk_cefecca7a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649979B1AD6');
        $this->addSql('DROP INDEX IDX_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE "user" DROP company_id');
        $this->addSql('ALTER TABLE "user" DROP firstname');
        $this->addSql('ALTER TABLE "user" DROP lastname');
    }
}
