<?php

declare(strict_types=1);

namespace User\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto_generated Migration: Please modify to your needs!
 */
final class Version20211120113856 extends AbstractMigration
{
    public function getDescription(): string
    {
        $description = 'This migration adds indexes and foreignkeys';
        return $description;
    }

    public function up(Schema $schema): void
    {
        // Add index to user table
        $table = $schema->getTable('user');
        $table->addIndex(['login'], 'idx_user_login');
        $table->addIndex(['email'], 'idx_user_email');
        $table->addIndex(['status'], 'idx_user_status');

        // Add indexes and foreign keys to user_role table
        $table = $schema->getTable('user_role');
        $table->addIndex(['id_user'], 'idx_user_role_user_id');
        $table->addIndex(['id_role'], 'idx_user_role_role_id');
        $table->addForeignKeyConstraint('user', ['id_user'], ['id'], [], 'fk_user_role_user_user_id');
        $table->addForeignKeyConstraint('role', ['id_role'], ['id'], [], 'fk_user_role_role_role_id');

        // Add index to role table
        $table = $schema->getTable('role');
        $table->addIndex(['name'], 'idx_role_name');
        $table->addIndex(['is_active'], 'idx_role_is_active');

        // Add indexes to role_hierarchy table
        $table = $schema->getTable('role_hierarchy');
        $table->addIndex(['id_parent'], 'idx_role_hierarchy_parent_id');
        $table->addIndex(['id_child'], 'idx_role_hierarchy_child_id');

        // Add indexes and foreign keys to role_privilege table
        $table = $schema->getTable('role_privilege');
        $table->addIndex(['id_role'], 'idx_role_privilege_role_id');
        $table->addIndex(['id_privilege'], 'idx_role_privilege_privilege_id');
        $table->addForeignKeyConstraint('privilege', ['id_privilege'], ['id'], [], 'fk_role_privilege_privilege_privilege_id');
        $table->addForeignKeyConstraint('role', ['id_role'], ['id'], [], 'fk_role_privilege_role_role_id');

        // Add indexes and foreign keys to privilege table
        $table = $schema->getTable('privilege');
        $table->addIndex(['name'], 'idx_privilege_name');
        $table->addUniqueIndex(['name'], 'idx_privilege_unique_name');
        $table->addIndex(['is_active'], 'idx_privilege_is_active');
    }

    public function down(Schema $schema): void
    {
        //drop for user
        $table = $schema->getTable('user');
        $table->dropIndex('idx_user_login');
        $table->dropIndex('idx_user_email');
        $table->dropIndex('idx_user_status');

        //drop for user_role
        $table = $schema->getTable('user_role');
        $table->dropIndex('idx_user_role_user_id');
        $table->dropIndex('idx_user_role_role_id');
        $table->removeForeignKey('fk_user_role_user_user_id');
        $table->removeForeignKey('fk_user_role_role_role_id');

        //drop for role
        $table = $schema->getTable('role');
        $table->dropIndex('idx_role_name');
        $table->dropIndex('idx_role_is_active');

        //drop for role_hierarchy
        $table = $schema->getTable('role_hierarchy');
        $table->dropIndex('idx_role_hierarchy_parent_id');
        $table->dropIndex('idx_role_hierarchy_child_id');

        //drop for role_hierarchy
        $table = $schema->getTable('role_privilege');
        $table->dropIndex('idx_role_privilege_role_id');
        $table->dropIndex('idx_role_privilege_privilege_id');
        $table->removeForeignKey('fk_role_privilege_privilege_privilege_id');
        $table->removeForeignKey('fk_role_privilege_role_role_id');

        //drop for privilege
        $table = $schema->getTable('privilege');
        $table->dropIndex('idx_privilege_parent_id');
        $table->dropIndex('idx_privilege_child_id');

    }
}
