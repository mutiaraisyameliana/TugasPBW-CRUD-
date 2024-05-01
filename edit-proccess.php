<?php
include 'koneksi.php';

// Fungsi untuk menampilkan data mahasiswa
function tampilkanDataMahasiswa()
{
    global $koneksi;
    // Query untuk mendapatkan daftar mahasiswa
    $result = $koneksi->query("SELECT * FROM mahasiswa");
    while ($row_mahasiswa = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row_mahasiswa['npm'] . "</td>";
        echo "<td>" . $row_mahasiswa['nama'] . "</td>";
        echo "<td>" . $row_mahasiswa['jurusan'] . "</td>";
        echo "<td>TODO: Tampilkan KRS</td>";
        echo "<td>
                <a href='edit.php?npm=" . $row_mahasiswa['npm'] . "' class='btn btn-primary'>Edit</a> 
                <a href='aksi.php?hapus=" . $row_mahasiswa['npm'] . "' class='btn btn-danger' onclick=\"return confirm('Apakah Anda yakin ingin menghapus?');\">Hapus</a>
              </td>";
        echo "</tr>";
    }
}

// Fungsi untuk menambahkan data mahasiswa dan KRS
if (isset($_POST['tambah'])) {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $kodemk = $_POST['kodemk'];
    // Cek apakah NPM sudah ada dalam database
    $cek_data = $koneksi->query("SELECT * FROM mahasiswa WHERE npm='$npm'");
    if ($cek_data->num_rows > 0) {
        // Jika NPM sudah ada, lakukan operasi edit
        $sql_edit = "UPDATE mahasiswa SET nama='$nama', jurusan='$jurusan' WHERE npm='$npm'";
        if ($koneksi->query($sql_edit) === TRUE) {
            // Redirect kembali ke halaman utama setelah berhasil edit
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql_edit . "<br>" . $koneksi->error;
        }
    } else {
        // Jika NPM belum ada, lakukan operasi tambah
        $sql_tambah = "INSERT INTO mahasiswa (npm, nama, jurusan) VALUES ('$npm', '$nama', '$jurusan')";
        if ($koneksi->query($sql_tambah) === TRUE) {
            // Ambil id mahasiswa yang baru ditambahkan
            $mahasiswa_id = $koneksi->insert_id;
            // Tambahkan data KRS
            $sql_krs = "INSERT INTO krs (mahasiswa_npm, matakuliah_kodemk) VALUES ('$npm', '$kodemk')";
            if ($koneksi->query($sql_krs) === TRUE) {
                // Redirect kembali ke halaman utama setelah berhasil tambah mahasiswa dan KRS
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $sql_krs . "<br>" . $koneksi->error;
            }
        } else {
            echo "Error: " . $sql_tambah . "<br>" . $koneksi->error;
        }
    }
}

// Fungsi untuk menghapus data mahasiswa
if (isset($_GET['hapus'])) {
    $npm_hapus = $_GET['hapus'];
    // Hapus data mahasiswa dari tabel mahasiswa
    $sql_hapus_mahasiswa = "DELETE FROM mahasiswa WHERE npm='$npm_hapus'";
    if ($koneksi->query($sql_hapus_mahasiswa) === TRUE) {
        // Hapus data KRS dari tabel krs yang terkait dengan mahasiswa yang dihapus
        $sql_hapus_krs = "DELETE FROM krs WHERE mahasiswa_npm='$npm_hapus'";
        if ($koneksi->query($sql_hapus_krs) === TRUE) {
            // Redirect kembali ke halaman utama setelah berhasil hapus
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql_hapus_krs . "<br>" . $koneksi->error;
        }
    } else {
        echo "Error: " . $sql_hapus_mahasiswa . "<br>" . $koneksi->error;
    }
}
?>