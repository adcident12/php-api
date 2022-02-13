<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');
    
    include_once $_SERVER['DOCUMENT_ROOT']. '/api/config/database.php';
    include_once $_SERVER['DOCUMENT_ROOT']. '/api/controllers/admin.php';

    $database = new Database();
    $db = $database->getConnection();

    $result = new Admin($db);

    $stmt = $result->getByUsername($_POST['username']);

    if ($stmt) {
        $resultCount = $stmt->rowCount();

        if ($resultCount > 0) {
            http_response_code(200);
            $arr = array();
            $arr['response'] = array();
            $arr['count'] = $resultCount;
    
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $e = $row;
                array_push($arr['response'], $e);
            }
            echo json_encode($arr);
        } else {
            http_response_code(404);
            echo json_encode(
                array(
                    "message" => "No records found."
                )
            );
        }
    } else {
        http_response_code(404);
        echo json_encode(
            array(
                "message" => "Your token did not macth the expected token. Please contact an administrator."
            )
        );
    }
?>