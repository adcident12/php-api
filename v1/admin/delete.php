<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Methods: POST');

    include_once $_SERVER['DOCUMENT_ROOT']. '/api/config/database.php';
    include_once $_SERVER['DOCUMENT_ROOT']. '/api/controllers/admin.php';

    $database = new Database();
    $db = $database->getConnection();

    $result = new Admin($db);
    $param = array();
    $param['id'] = isset($_POST['id']) ? $_POST['id'] : '';
    $data = json_encode($param);
    $data = json_decode($data);
    $result->id = $data->id;

    if ($data->id == '') {
        http_response_code(404);
        echo json_encode(
            array(
                "message" => "You didn't enter your id. Please try again."
            )
        );
    } else {
        if ($result->delete()) {
            http_response_code(200);
            echo json_encode(
                array(
                    "message" => "You was delete successfully."
                )
            );
        } else {
            http_response_code(404);
            echo json_encode(
                array(
                    "message" => "You was not delete successfully. Please try again or recheck token."
                )
            );
        }
    }
?>