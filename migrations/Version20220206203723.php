<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220206203723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_postagem (category_id INT NOT NULL, postagem_id INT NOT NULL, INDEX IDX_6799002A12469DE2 (category_id), INDEX IDX_6799002A6DD36FEA (postagem_id), PRIMARY KEY(category_id, postagem_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_postagem ADD CONSTRAINT FK_6799002A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_postagem ADD CONSTRAINT FK_6799002A6DD36FEA FOREIGN KEY (postagem_id) REFERENCES postagem (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE postagem DROP FOREIGN KEY FK_D0E3845112469DE2');
        $this->addSql('DROP INDEX IDX_D0E3845112469DE2 ON postagem');
        $this->addSql('ALTER TABLE postagem DROP category_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category_postagem');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE imagem CHANGE titulo titulo VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE postagem ADD category_id INT DEFAULT NULL, CHANGE titulo titulo VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE descricao descricao VARCHAR(800) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE imagem imagem VARCHAR(300) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE autor autor VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE postagem ADD CONSTRAINT FK_D0E3845112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D0E3845112469DE2 ON postagem (category_id)');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
