<?php

class Model_Question extends Orm\Model
{

   	protected static $_table_name = 'questions'; 
	protected static $_properties = array('id','title', 'answer', 'id_subject', 'level');

}
