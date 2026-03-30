<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRbssTables extends Migration
{
    public function up()
    {
        // 1. Categories (RBSS Specific)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'month_year' => ['type' => 'VARCHAR', 'constraint' => 7],
            'total' => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories');

        // 2. Tickets (RBSS Issues)
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'unsigned' => true],
            'category_id'   => ['type' => 'INT', 'unsigned' => true],
            'subject'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'description'   => ['type' => 'TEXT'],
            'screenshot'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Received', 'In Progress', 'Closed'], 'default' => 'Received'],
            'admin_comment' => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tickets');

        //Monthly Archive Table (Optional)
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'month_year'  => ['type' => 'VARCHAR', 'constraint' => 7],
            'total'    => ['type' => 'INT', 'default' => 0],
            //'message'    => ['type' => 'TEXT'],
            // 'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('monthly_archives');

        // 3. Threaded Replies
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'ticket_id'  => ['type' => 'INT', 'unsigned' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true],
            'message'    => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('replies');
    }

    public function down() { /* Drop logic */ 
    
        $this->forge->dropTable('replies');
        $this->forge->dropTable('tickets');
        $this->forge->dropTable('categories');
        $this->forge->dropTable('monthly_archives');
    }
}