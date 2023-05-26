<?php

include 'connection.php';

// INSERT INTO `detail_donasi`(`nama`, `jumlah`, `tanggal`, `nomor_hp`, `keterangan`) VALUES ('fulan', '100000', '2020-10-22', '082111111111', 'sukses');

// prepare > bind > execute

$conn = getConnection();

try {
    if($_POST){
        $nama = $_POST["nama"];
        $jumlah = $_POST["jumlah"];
        $tanggal = $_POST["tanggal"];
        $nomor_hp = $_POST["nomor_hp"];

        if(isset($_FILES["keterangan"]["name"])){
            $image_name = $_FILES["keterangan"]["name"];
            $extensions = ["jpg", "png", "jpeg"];
            $extension = pathinfo($image_name, PATHINFO_EXTENSION);
            
            if (in_array($extension, $extensions)){
                $upload_path = 'upload/' . $image_name;

                if(move_uploaded_file($_FILES["keterangan"]["tmp_name"], $upload_path)){

                    $keterangan = "http://localhost/donasi/" . $upload_path; 

                    $statement = $conn->prepare("INSERT INTO `detail_donasi`( `nama`, `jumlah`, `tanggal`, `nomor_hp`, `keterangan`) VALUES (:nama, :jumlah, :tanggal, :nomor_hp, :keterangan);");

                    $statement->bindParam(':nama', $nama);
                    $statement->bindParam(':jumlah',$jumlah);
                    $statement->bindParam(':tanggal',$tanggal);
                    $statement->bindParam(':nomor_hp',$nomor_hp);
                    $statement->bindParam(':keterangan',$keterangan);

                    $statement->execute();

                    $response["message"] = "Data Berhasil Direcord!";
                    
                } else {
                    echo "gagal memindahkan file";
                }
            } else {
                $response["message"] = "Hanya diperbolehkan menginput keterangan gambar!";
            }

        } else {
            $statement = $conn->prepare("INSERT INTO `detail_donasi`(`nama`, `jumlah`, `tanggal`, `nomor_hp`) VALUES (:nama, :jumlah, :tanggal, :nomor_hp)");

            $statement->bindParam(':nama', $nama);
            $statement->bindParam(':jumlah',$jumlah);
            $statement->bindParam(':tanggal',$tanggal);
            $statement->bindParam(':nomor_hp',$nomor_hp);
    
            $statement->execute();
            $response["message"] = "Data berhasil direcord";
        }
    }
} catch (PDOException $e){
    $response["message"] = "error $e";
}
echo json_encode($response, JSON_PRETTY_PRINT);