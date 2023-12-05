<?php
include 'koneksi.php';

// Fungsi untuk mendapatkan data mahasiswa
function getMahasiswa($conn)
{
    $query = "SELECT * FROM mahasiswa";
    $result = $conn->query($query);
    return $result;
}

// Fungsi untuk menambahkan mahasiswa baru
function tambahMahasiswa($conn, $nama, $nim, $jurusan)
{
    $query = "INSERT INTO mahasiswa (nama, nim, jurusan) VALUES ('$nama', '$nim', '$jurusan')";
    $conn->query($query);
}

// Fungsi untuk mengedit data mahasiswa
function editMahasiswa($conn, $id, $nama, $nim, $jurusan)
{
    $query = "UPDATE mahasiswa SET nama='$nama', nim='$nim', jurusan='$jurusan' WHERE id=$id";
    $conn->query($query);
}

// Fungsi untuk menghapus data mahasiswa
function hapusMahasiswa($conn, $id)
{
    $query = "DELETE FROM mahasiswa WHERE id=$id";
    $conn->query($query);
}

// Proses form tambah/edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        tambahMahasiswa($conn, $_POST['nama'], $_POST['nim'], $_POST['jurusan']);
    } elseif (isset($_POST['edit'])) {
        editMahasiswa($conn, $_POST['id'], $_POST['nama'], $_POST['nim'], $_POST['jurusan']);
    }
    header('Location: index.php');
}

// Proses hapus
if (isset($_GET['hapus'])) {
    hapusMahasiswa($conn, $_GET['hapus']);
    header('Location: index.php');
}

// Menampilkan data mahasiswa
$result = getMahasiswa($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi CRUD Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Aplikasi CRUD Mahasiswa</h1>
    </header>
    <div class="container">

        <h2>Data Mahasiswa</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Jurusan</th>
                <th>Aksi</th>
            </tr>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['nim'] . "</td>";
                echo "<td>" . $row['jurusan'] . "</td>";
                echo "<td>
                        <a href='index.php?edit=" . $row['id'] . "'>Edit</a>
                        <a href='index.php?hapus=" . $row['id'] . "'>Hapus</a>
                    </td>";
                echo "</tr>";
            }
            ?>
        </table>

        <?php
        // Form Tambah/Edit Mahasiswa
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $query = "SELECT * FROM mahasiswa WHERE id=$id";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();
            echo "<h2>Edit Mahasiswa</h2>";
        } else {
            echo "<h2>Tambah Mahasiswa</h2>";
        }
        ?>

        <form action="index.php" method="post">
            <?php if (isset($_GET['edit'])): ?>
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <?php endif; ?>
            <label for="nama">Nama:</label>
            <input type="text" name="nama" value="<?php echo isset($row['nama']) ? $row['nama'] : ''; ?>" required><br>
            <label for="nim">NIM:</label>
            <input type="text" name="nim" value="<?php echo isset($row['nim']) ? $row['nim'] : ''; ?>" required><br>
            <label for="jurusan">Jurusan:</label>
            <input type="text" name="jurusan" value="<?php echo isset($row['jurusan']) ? $row['jurusan'] : ''; ?>" required><br>
            <?php if (isset($_GET['edit'])): ?>
                <input type="submit" name="edit" value="Simpan">
            <?php else: ?>
                <input type="submit" name="tambah" value="Tambah">
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
