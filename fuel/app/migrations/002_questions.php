<?php
namespace Fuel\Migrations;

class Questions
{

    function up()
    {
        \DBUtil::create_table('questions', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true, 'null' => false),
            'title' => array('type' => 'varchar', 'constraint' => 500, 'null' => false),
            'answer' => array('type' => 'varchar', 'constraint' => 500, 'null' => false),
            'level' => array('type' => 'int', 'constratint' => 5, 'null' => false),
            'id_subject' => array('type' => 'int', 'constraint' => 50, 'null' => false),

        ), array('id'),
        true,
        'InnoDB',
        'utf8_unicode_ci',
        array(
            array(
                'constraint' => 'claveAjenaQuestionsASubject',
                'key' => 'id_subject',
                'reference' => array(
                    'table' => 'subjects',
                    'column' => 'number',
                ),
                'on_update' => 'CASCADE',
                'on_delete' => 'RESTRICT'
            )
        ));

    }

    function down()
    {
       \DBUtil::drop_table('questions');
    }
}