<?php


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

        try{

            $subjects = $_GET['subjects'];
            $levels = $_GET['levels'];

            foreach ($subjects as $key => $subject_number) 
            {

                $questionQuery = Model_Question::find('first', array(
                    'where' => array(
                        array('id_subject', $subject_number)
                    )
                ));

                if($questionQuery != null){
                    $questions[] = $questionQuery;
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

            return $this->createResponse(200, 'Preguntas devueltas', array('questions' => $questionsOK));

        }catch(Exception $e){

            return $this->createResponse(500, $e->getMessage() . "Línea ". $e->getLine());

        }

    }

    function post_uplevel(){
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

}