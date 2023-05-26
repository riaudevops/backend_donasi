<?php
include 'connection.php';

$conn = getConnection();

try {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        $statement = $conn->prepare("SELECT * FROM detail_donasi WHERE id = :id;");
        $statement->bindParam(':id', $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $result = $statement->fetch();

        if ($result) {
            $statement = $conn->prepare("DELETE FROM detail_donasi WHERE id = :id");
            $statement->bindParam("id", $id);
            $statement->execute();

            $response['message'] = "Delete Data Berhasil";
        } else {
            http_response_code(404);
            $response['message'] = "informasi donasi tidak ditemukan";
        }

    } else {
        $response['message'] = "Delete Data Gagal";
    }
} catch (PDOException $e) {
    echo $e;
}

$json = json_encode($response, JSON_PRETTY_PRINT);
echo $json;