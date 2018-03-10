<?php
namespace Fuel\Migrations;

class Subjects
{

    function up()
    {
        \DBUtil::create_table('subjects', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true, 'null' => false),
            'number' => array('type' => 'int', 'constraint' => 50, 'null' => false),
            'title' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
        ), array('id'));

        //Adding UNIQUE constraint to 'type' column
        \DB::query("ALTER TABLE `subjects` ADD UNIQUE (`number`)")->execute();

    }


    function down()
    {
       \DBUtil::drop_table('subjects');
    }
}