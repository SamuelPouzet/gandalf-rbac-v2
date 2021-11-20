<?php

declare(strict_types=1);

namespace User\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211120112049 extends AbstractMigration
{
    public function getDescription(): string
    {
        $description = 'This is the initial migration for the user\'s tables';
        return $description;
    }

    public function up(Schema $schema): void
    {
        //create table user
        $table = $schema->createTable('user');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'length'=>128]);
        $table->addColumn('login', 'string', ['notnull'=>true, 'length'=>128]);
        $table->addColumn('firstname', 'string', ['notnull'=>true, 'length'=>128]);
        $table->addColumn('email', 'string', ['notnull'=>true, 'length'=>225]);
        $table->addColumn('avatar', 'string', ['notnull'=>true, 'length'=>156]);
        $table->addColumn('password', 'string', ['notnull'=>true, 'length'=>225]);
        $table->addColumn('date_create', 'datetime', ['notnull'=>true]);
        $table->addColumn('status', 'integer', ['notnull'=>true, 'length'=>2]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //create table user_role
        $table = $schema->createTable('user_role');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('id_user', 'integer', ['notnull'=>true]);
        $table->addColumn('id_role', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //create table role
        $table = $schema->createTable('role');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'length'=>225]);
        $table->addColumn('description', 'text', ['notnull'=>true, 'length'=>65535]);
        $table->addColumn('is_active', 'boolean');
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //create table role_hierarchy
        $table = $schema->createTable('role_hierarchy');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('id_parent', 'integer', ['notnull'=>true]);
        $table->addColumn('id_child', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //create table role_privilege
        $table = $schema->createTable('role_privilege');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('id_role', 'integer', ['notnull'=>true]);
        $table->addColumn('id_privilege', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //create table privilege
        $table = $schema->createTable('privilege');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'length'=>128]);
        $table->addColumn('description', 'text', ['notnull'=>true, 'length'=>65535]);
        $table->addColumn('is_active', 'boolean');
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('user');
        $schema->dropTable('user_role');
        $schema->dropTable('role');
        $schema->dropTable('role_hierarchy');
        $schema->dropTable('role_privilege');
        $schema->dropTable('privilege');
    }
}
