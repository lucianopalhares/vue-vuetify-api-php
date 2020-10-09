<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    include_once '../class/messages.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $item = new Message($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $item->id = $data->id;


    if($item->deleteMessage()){
        
        $response = ['status'=>true,'msg'=>"Mensagem excluida!"];

        echo json_encode($response);
    } else{

        $response = ['status'=>false,'msg'=>"Erro ao deletar mensagem"];

        echo json_encode($response);
    }
?>