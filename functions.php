<?php
// membuat koneksi
$conn = mysqli_connect("localhost","root","","phpdatabase");
// cek koneksi jika error
if (!$conn) {
    die('Koneksi Error : '.mysql_connect_errno()
    .' - '.mysqli_connect_error());
}
// ambil data dari tabel mahasiswa/query data mahasiswa
$result = mysqli_query($conn,"SELECT * FROM mahasiswa");
// function query akan menerima isi parameter dari string query yang ada pada index2.php
function query($query_kedua)
{
    // dikarenakan $conn diluar function query maka dibutuhkan scope global $conn
    global $conn;
    $result = mysqli_query($conn,$query_kedua);
    // wadah kosong untuk menampung isi array pada saat looping
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambah ($data) 
{
    global $conn;
    $nama = htmlspecialchars($data["Nama"]);
    $nim = htmlspecialchars($data["NIM"]);
    $email = htmlspecialchars($data["Email"]);
    $jurusan = htmlspecialchars($data["Jurusan"]);
    //$gambar = $data["Gambar"];

    $gambar=upload();
    if(!gambar){
        return false;
    }

    $query = " INSERT INTO mahasiswa
                VALUES
                ('','$nama','$nim','$email','$jurusan','$gambar')";
                mysqli_query($conn,$query);

                return mysqli_affected_rows($conn);     
}

function hapus($id)
{
    global $conn;
    mysqli_query($conn,"DELETE FROM mahasiswa WHERE id =$id");
    return mysqli_affected_rows($conn);
    
}

function edit ($data)
{
    global $conn;

    $id = $data["id"];
    $nama = htmlspecialchars($data["Nama"]);
    $nim = htmlspecialchars($data["Nim"]);
    $email = htmlspecialchars($data["Email"]);
    $jurusan = htmlspecialchars($data["Jurusan"]);
    $gambar = htmlspecialchars($data["Gambar"]);

    if($_FILES['Gambar'][error]=== 4){
        $gambar = $GambarLama;
    } else {
        $gambar=upload();
    }
    $query= "   UPDATE mahasiswa SET
                Nama = '$nama',
                Nim = '$nim',
                Email = '$email',
                Jurusan = '$jurusan',
                Gambar = '$gambar'
                WHERE id= $id ";
    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function cari($keyword){
    $sql="SELECT * FROM mahasiswa
    WHERE
    Nama LIKE '%$keyword%' OR
    NIM LIKE '%$keyword%' OR
    Email LIKE '%$keyword%' OR
    Jurusan LIKE '%$keyword%'
    ";
    var_dump($sql);
    //die();
    return query($sql);
}

function upload(){
    $nama_file =$_FILES['Gambar']['name'];
    $ukuran_file =$_FILES['Gambar']['size'];
    $error =$_FILES['Gambar']['error'];
    $tmpfile =$_FILES['Gambar']['tmp_name'];

    if($error===4){
        echo "
            <script>
                alert('Tidak ada gambar yang diupload');
            <script>
        ";
        return false;
    }

    $jenis_gambar = ['jpg','jpeg','gif'];
    $pecah_gambar = explode('.',$nama_file);
    $pecah_gambar = strtolower(end($pecah_gambar));
    if(!in_array($pecah_gambar,$jenis_gambar)){
        echo "
            <script>
                alert('yang anda upload bukan file gambar');
            <script>
        ";
        return false;
    }

    if($ukuran_file > 10000000){
        echo "
            <script>
                alert('ukuran gambar terlalu besar');
            <script>
        ";
        return false;
    }

    move_uploaded_file($tmpfile,'img/'.$nama_file);

    return $nama_file;
}

function update(){
    $namafilebaru = uniqid();
    $namafilebaru .= '.';
    $namafilebaru .= $pecah_gambar;

    move_uploaded_file($tmpfile,'img/'.$namafilebaru);

    return $namafilebaru;
}

function registrasi ($data){
    global $conn;

    $username = strtolower(stripcslashes($data['username']));

    $password = mysqli_real_escape_string($conn, $data['password']);
    $password2 = mysqli_real_escape_string($conn, $data['password2']);

    $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");

    if(mysqli_fetch_assoc($result)){
        echo "
            <script>
                aler('username sudah ada');
            <script>
        ";
        return false;
    }

    if($password!==$password2){
        echo "
            <script>
                alert('password anda tidak sama')
            <script>
        ";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    myqsqli_query($conn,"INSERT INTO users VALUES ('', '$username', '$password')");

    return mysqli_affected_rows($conn);
}
?>
