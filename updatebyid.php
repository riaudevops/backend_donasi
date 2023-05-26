<?php

include 'connection.php';

$conn = getConnection();

try {
    if($_POST) {
        $id = $_POST["id"];
        $nama = $_POST["nama"];
        $tanggal = $_POST["tanggal"];
        $jumlah = $_POST["jumlah"];
        $nomor_hp = $_POST["nomor_hp"];

        $statement = $conn->prepare("SELECT * FROM detail_donasi WHERE id = :id");
        $statement->bindParam(':id', $id);
        $statement->execute();
        $result = $statement->fetch();

        if($result){

            if(isset($_FILES['keterangan']['name'])){
                $image_name = $_FILES['keterangan']['name'];
                $extension_file = ["jpg", "png", "jpeg"];
                $extension = pathinfo($image_name, PATHINFO_EXTENSION);

                if (in_array($extension, $extension_file)){
                    $upload_path = 'upload/' . $image_name;

                    if(move_uploaded_file($_FILES['keterangan']['tmp_name'], $upload_path)){
                        $message = "berhasil";
                        $keterangan = "https://lutproject.my.id/donasi/".$upload_path;
                      
                      	echo $image;

                        $statement = $conn->prepare("UPDATE detail_donasi SET nama = :nama, jumlah = :jumlah, tanggal = :tanggal, nomor_hp = :nomor_hp, keterangan = :keterangan, UPDATED_AT = now() WHERE id = :id");

                        $statement->bindParam(':id', $id);
                        $statement->bindParam(':keterangan', $keterangan);
                        $statement->bindParam(':nomor_hp', $nomor_hp);
                        $statement->bindParam(':nama', $nama);
                        $statement->bindParam(':tanggal', $tanggal);
                        $statement->bindParam(':jumlah', $jumlah);

                    } else {
                        $message = "Terjadi kesalahan saat mengupload gambar";
                    }
                } else {
                    $message = "Hanya diperbolehkan mengupload file gambar!";
                    $response["message"] = $message;
                    $json = json_encode($response, JSON_PRETTY_PRINT);

                    echo $json;
                    die();
                }
            } else {
                $statement = $conn->prepare("UPDATE detail_donasi SET nama = :nama, jumlah = :jumlah, tanggal = :tanggal,  nomor_hp = :nomor_hp WHERE id = :id");

                $statement->bindParam(':id', $id);
                $statement->bindParam(':nama', $nama);
                $statement->bindParam(':jumlah', $jumlah);
                $statement->bindParam(':tanggal', $tanggal);
                $statement->bindParam(':nomor_hp', $nomor_hp);
            }
            $statement->execute();
            $response["message"] = "Data berhasil di ubah!";
        } else {
            $response["message"] = "Data tidak ditemukan!";
        }
    }
} catch(PDOException $e) {
    $response["message"] = "Error . $e";
}
$json = json_encode($response, JSON_PRETTY_PRINT);

//print json
echo $json;