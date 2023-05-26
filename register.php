<?php

include 'connection.php';

$conn = getConnection();

if( $_POST){
    $nama = $_POST["nama"];
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    try {
        $statement = $conn->prepare("SELECT * FROM user WHERE username = :username");
        $statement->bindParam(":username", $username);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $existing_user = $statement->fetchAll();

        if(!$existing_user) {
            $statement = $conn->prepare("INSERT INTO user (nama, username, password) VALUES (:nama, :username, :password)");
            $statement->bindParam(":nama", $nama);
            $statement->bindParam(":username", $username);
            $statement->bindParam(":password", $password);
            $statement->execute();

            $response["message"]= "akun berhasil didaftarkan";
        } else {
            $response["message"]= "akun $username sudah terdaftar!";
        }
    } catch (PDOException $e){
        echo $response["message"]= "error $e";
    }
    $json = json_encode($response, JSON_PRETTY_PRINT);
    echo $json;
}


