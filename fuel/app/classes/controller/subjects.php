<?php


class Controller_Subjects extends Controller_Rest{

    function post_create(){

        try{

            $number = $_POST['number'];
            $title = $_POST['title'];

            $subject = Model_Subject::find('first', array(
                'where' => array(
                    array('number', $number)
                )
            ));

            if($subject != null){
                return $this->createResponse(400, "El número de tema ya existe");
            }

            $newSubject = new Model_Subject(array('number' => $number, 'title' => $title));

            $newSubject->save();

            return $this->createResponse(200, 'Tema creado', array('subject' => $newSubject));

        }catch(Exception $e){

            return $this->createResponse(500, $e->getMessage . "Línea ". $e->getLine());

        }

    }

    function get_subjects(){

        header("Access-Control-Allow-Origin: *");

        try{

            $subjects = Model_Subject::find('all');

            if($subjects == null){
                return $this->createResponse(400, "No existe ningún tema creado");
            }

            return $this->createResponse(200, 'Temas devueltos', array('subjects' => Arr::reindex($subjects)));

        }catch(Exception $e){

            return $this->createResponse(500, $e->getMessage . "Línea ". $e->getLine());

        }

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