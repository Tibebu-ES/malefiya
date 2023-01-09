<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMalefiyaTables extends Migration
{
    public function up()
    {
        //
        // User Info Table
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'first_name'           => ['type' => 'varchar', 'constraint' => 50, 'null' => false],
            'last_name'           => ['type' => 'varchar', 'constraint' => 50, 'null' => false],
            'timezone'       => ['type' => 'varchar', 'constraint' => 50, 'null' => false],
            'locale'         => ['type' => 'varchar', 'constraint' => 10, 'null' => false],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
        $this->forge->createTable('malefiya_user_infos');
        // Calendars Table
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'name'           => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'active'         => ['type' => 'BOOLEAN',  'default' => false],
            'about'          => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'timezone'       => ['type' => 'varchar', 'constraint' => 50, 'null' => true],
            'locale'         => ['type' => 'varchar', 'constraint' => 10, 'null' => true],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
        $this->forge->createTable('malefiya_calendars');

        // Sub Calendars Table
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'calendar_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'name'           => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'active'         => ['type' => 'BOOLEAN',  'default' => false],
            'overlap'        => ['type' => 'BOOLEAN',  'default' => false],
            'color'          => ['type' => 'varchar', 'constraint' => 7, 'null' => false],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('calendar_id', 'malefiya_calendars', 'id', '', 'CASCADE');
        $this->forge->createTable('malefiya_sub_calendars');

        // Events Table
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'sub_calendar_id'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'title'              => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'all_day'           => ['type' => 'BOOLEAN',  'default' => false],
            'start_date'         => ['type' => 'datetime', 'null' => false],
            'end_date'     => ['type' => 'datetime', 'null' => false],
            'duration'    => ['type' => 'int', 'constraint' => 5, 'unsigned' => true],//in sec
            'rrule'           => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'about'           => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'where'           => ['type' => 'varchar', 'constraint' => 50, 'null' => true],
            'who'           => ['type' => 'varchar', 'constraint' => 50, 'null' => true],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('sub_calendar_id', 'malefiya_sub_calendars', 'id', '', 'CASCADE');
        $this->forge->createTable('malefiya_events');

        // Custom Event Fields Table
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'calendar_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'name'           => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'type'         => ['type' => 'ENUM',  'constraint' => ['text', 's_select', 'm_select'],'default' => 'text','null' => false],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('calendar_id', 'malefiya_calendars', 'id', '', 'CASCADE');
        $this->forge->createTable('malefiya_custom_event_fields');

        // Custom Event Field Options Table
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'custom_event_field_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'name'           => ['type' => 'varchar', 'constraint' => 50, 'null' => false],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('custom_event_field_id', 'malefiya_custom_event_fields', 'id', '', 'CASCADE');
        $this->forge->createTable('malefiya_cef_options');

        // Custom Event Field Values Table
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'custom_event_field_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'event_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'value'           => ['type' => 'varchar', 'constraint' => 50, 'null' => true],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('event_id', 'malefiya_events', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('custom_event_field_id', 'malefiya_custom_event_fields', 'id', '', 'CASCADE');
        $this->forge->createTable('malefiya_custom_event_field_values');

        // Access Keys Table
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'calendar_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'name'           => ['type' => 'varchar', 'constraint' => 50, 'null' => false],
            'key'           => ['type' => 'varchar', 'constraint' => 18, 'null' => false],
            'active'           => ['type' => 'BOOLEAN',  'default' => false],
            'has_password'           => ['type' => 'BOOLEAN',  'default' => false],
            'password'      => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('calendar_id', 'malefiya_calendars', 'id', '', 'CASCADE');
        $this->forge->createTable('malefiya_access_keys');

        // Sub Calendar Permissions Table
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'sub_calendar_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'access_key_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'access_type'         => ['type' => 'ENUM',  'constraint' => ['read_only', 'modify'],'default' => 'read_only','null' => false],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('sub_calendar_id', 'malefiya_sub_calendars', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('access_key_id', 'malefiya_access_keys', 'id', '', 'CASCADE');
        $this->forge->createTable('malefiya_sub_calendar_permissions');
    }

    public function down()
    {
        //
        $this->db->disableForeignKeyChecks();

        $this->forge->dropTable('malefiya_sub_calendar_permissions', true);
        $this->forge->dropTable('malefiya_access_keys', true);
        $this->forge->dropTable('malefiya_custom_event_field_values', true);
        $this->forge->dropTable('malefiya_cef_options', true);
        $this->forge->dropTable('malefiya_custom_event_fields', true);
        $this->forge->dropTable('malefiya_events', true);
        $this->forge->dropTable('malefiya_sub_calendars', true);
        $this->forge->dropTable('malefiya_calendars', true);
        $this->forge->dropTable('malefiya_user_infos', true);

        $this->db->enableForeignKeyChecks();
    }
}
