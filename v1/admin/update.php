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
    $param['username'] = isset($_POST['username']) ? $_POST['username'] : '';
    $param['password'] = isset($_POST['password']) ? $_POST['password'] : '';
    $data = json_encode($param);
    $data = json_decode($data);
    $result->id = $data->id;
    $result->username = $data->username;
    $result->password = $data->password;

    if ($data->username == '') {
        http_response_code(404);
        echo json_encode(
            array(
                "message" => "You didn't enter your uername. Please try again."
            )
        );
    } else if ($data->password == '') {
        http_response_code(404);
        echo json_encode(
            array(
                "message" => "You didn't enter your password. Please try again."
            )
        );
    } else if ($data->id == '') {
        http_response_code(404);
        echo json_encode(
            array(
                "message" => "You didn't enter your id. Please try again."
            )
        );
    } else {
        if ($result->update()) {
            http_response_code(200);
            echo json_encode(
                array(
                    "message" => "You was update successfully."
                )
            );
        } else {
            http_response_code(404);
            echo json_encode(
                array(
                    "message" => "You was not update successfully. Please try again or recheck token."
                )
            );
        }
    }
?>