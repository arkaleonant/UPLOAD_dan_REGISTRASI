<?php
    require 'functions.php';
    //cek apakah button submit sudah di tekan atau belum
    if(isset($_POST['submit']))
    {
        var_dump($_POST);
        var_dump($_FILES);
        die();

    //cek sukses data ditambah menggunakan function tambah pada functions.php
    if(tambah($_POST)>0)
    {
        echo "
        <script>
            alert('data berhasil disimpan');
            document.location.href='index.php';
        </script>;
        ";
    }else{
        echo "
        <script>
            alert('data gagal disimpan');
            document.location.href='tambah_data.php';
        </script>";
            echo "</br>";
            echo mysqli_error($conn);
        }
    }
?>
