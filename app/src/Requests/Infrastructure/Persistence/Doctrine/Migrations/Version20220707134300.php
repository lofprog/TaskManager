<?php

declare(strict_types=1);

namespace App\Requests\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220707134300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add version to requests';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE request_managers ADD COLUMN version INT NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE request_managers DROP COLUMN version');
    }
}