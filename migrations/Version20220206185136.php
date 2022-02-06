<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220206185136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adiciona usuario a postagem e categorias/album';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1DB38439E ON category (usuario_id)');
        $this->addSql('ALTER TABLE postagem ADD usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE postagem ADD CONSTRAINT FK_D0E38451DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D0E38451DB38439E ON postagem (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1DB38439E');
       // $this->addSql('DROP INDEX IDX_64C19C1DB38439E ON category');
        $this->addSql('ALTER TABLE category DROP usuario_id, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE imagem CHANGE titulo titulo VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE postagem DROP FOREIGN KEY FK_D0E38451DB38439E');
       // $this->addSql('DROP INDEX IDX_D0E38451DB38439E ON postagem');
        $this->addSql('ALTER TABLE postagem DROP usuario_id, CHANGE titulo titulo VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE descricao descricao VARCHAR(800) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE imagem imagem VARCHAR(300) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE autor autor VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
