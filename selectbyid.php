<?php
include 'connection.php';

$conn = getConnection();

$id = $_GET["id"];

try {
    $statement = $conn->prepare("SELECT * FROM detail_donasi WHERE id = :id;");
    $statement->bindParam(':id', $id);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_OBJ);

    if($result){
        $result = $statement->fetch();
        echo json_encode($result, JSON_PRETTY_PRINT);
    } else {
        http_response_code(404);
        $response["message"] = "informasi donasi tidak ditemukan";
        echo json_encode($response,JSON_PRETTY_PRINT);
    }

} catch (PDOException $e) {
    echo $e;
}