<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111095510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX user_id ON candidat');
        $this->addSql('ALTER TABLE recruteur DROP INDEX UNIQ_2BD3678CA76ED395, ADD INDEX IDX_2BD3678CA76ED395 (user_id)');
        $this->addSql('ALTER TABLE recruteur DROP FOREIGN KEY FK_2BD3678CA76ED395');
        $this->addSql('ALTER TABLE recruteur ADD CONSTRAINT FK_2BD3678CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX user_id ON candidat (user_id)');
        $this->addSql('ALTER TABLE recruteur DROP INDEX IDX_2BD3678CA76ED395, ADD UNIQUE INDEX UNIQ_2BD3678CA76ED395 (user_id)');
        $this->addSql('ALTER TABLE recruteur DROP FOREIGN KEY FK_2BD3678CA76ED395');
        $this->addSql('ALTER TABLE recruteur ADD CONSTRAINT FK_2BD3678CA76ED395 FOREIGN KEY (user_id) REFERENCES recruteur (id)');
    }
}
