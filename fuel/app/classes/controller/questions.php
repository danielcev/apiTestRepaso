<?php

header("Access-Control-Allow-Origin: *");
class Controller_Questions extends Controller_Rest{

    function post_create(){

        try{
            $title = $_POST['title'];
            $answer = $_POST['answer'];
            $subject_number = $_POST['subject_number'];

            $questionQuery = Model_Question::find('first', array(
                'where' => array(
                    array('title', $title)
                )
            ));

            if($questionQuery != null){
                return $this->createResponse(400, "Ya existe una pregunta con ese título");
            }

            $subjectQuery = Model_Subject::find('first', array(
                'where' => array(
                    array('number', $subject_number)
                )
            ));

            if($subjectQuery == null){
                return $this->createResponse(400, 'No existe el tema introducido');
            }

            $newQuestion = new Model_Question(array('title' => $title, 'answer' => $answer, 'id_subject' => $subject_number, 'level' => 1));

            $newQuestion->save();

            return $this->createResponse(200, 'Pregunta creada', array('question' => $newQuestion));

        }catch(Exception $e){

            return $this->createResponse(500, $e->getMessage . "Línea ". $e->getLine());

        }

    }

    function get_questions(){

        header("Access-Control-Allow-Origin: *");

        try{

            $subjects = $_GET['subjects'];
            $levels = $_GET['levels'];

            foreach ($subjects as $key => $subject_number) 
            {

                $questionQuery = Model_Question::find('all', array(
                    'where' => array(
                        array('id_subject', $subject_number)
                    )
                ));

                if($questionQuery != null){

                    if(gettype($questionQuery) == "array"){
                        foreach ($questionQuery as $key => $questionAdd){

                            $questions[] = $questionAdd;

                        }
                    }else{
                        $questions[] = $questionQuery;
                    }

                }

            }

            if(!isset($questions)){
                return $this->createResponse(200, 'No hay preguntas con esas condiciones');
            }

            foreach ($levels as $key => $level) 
            {

                foreach ($questions as $key => $question) {

                    if($question->level == $level){
                        $questionsOK[] = $question;
                    }
                }   

            }

            if(!isset($questionsOK)){
                return $this->createResponse(200, 'No hay preguntas con esas condiciones');
            }

            return $this->createResponse(200, 'Preguntas devueltas', array('questions' => $questionsOK));

        }catch(Exception $e){

            return $this->createResponse(500, $e->getMessage() . "Línea ". $e->getLine());

        }

    }

    function get_allquestions(){

        header("Access-Control-Allow-Origin: *");

        try{

            $levels = $_GET['levels'];

            $questions = Model_Question::find('all');

            if($questions == null){
                return $this->createResponse(200, 'No hay ninguna pregunta creada');
            }

            foreach ($levels as $key => $level) 
            {

                foreach ($questions as $key => $question) {

                    if($question->level == $level){
                        $questionsOK[] = $question;
                    }
                }   

            }

            if(!isset($questionsOK)){
                return $this->createResponse(200, 'No hay ninguna pregunta con esas condiciones');
            }

            return $this->createResponse(200, 'Preguntas devueltas', array('questions' => $questionsOK));

        }catch(Exception $e){

            return $this->createResponse(500, $e->getMessage() . "Línea ". $e->getLine());

        }

    }

    function post_uplevel(){
        $this->cors();
        $id_question = $_POST['id_question'];

        $questionQuery = Model_Question::find('first', array(
            'where' => array(
                array('id', $id_question)
            )
        ));

        if($questionQuery == null){
            return $this->createResponse(400, 'No existe la pregunta con ese id');
        }

        if($questionQuery->level >= 5){
            return $this->createResponse(400, 'La pregunta ya está en el nivel máximo');
        }

        $questionQuery->level += 1;
        $questionQuery->save();

        return $this->createResponse(200, 'Nivel subido a la pregunta', array('question' => $questionQuery));

    }

    function createResponse($code, $message, $data = []){

        $json = $this->response(array(
            'code' => $code,
            'message' => $message,
            'data' => $data
            ));

        return $json;

    }

    function cors() {

        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
    
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
    
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
            exit(0);
        }
    
        echo "You have CORS!";
    }

}