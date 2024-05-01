<?php
// Assuming koneksi.php establishes a connection object named $conn
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM mahasiswa WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id); // Bind ID parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $npm = $row['npm'];
        $nama = $row['nama'];
        $jurusan = $row['jurusan'];
    } else {
        echo "Data tidak ditemukan.";
        exit();
    }

    $stmt->close(); // Close prepared statement
}

if (isset($_POST['update'])) {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];

    $sql = "UPDATE mahasiswa SET npm=?, nama=?, jurusan=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $npm, $nama, $jurusan, $id); // Bind update parameters
    if ($stmt->execute() === TRUE) {
        header("Location: index.php");  // Redirect on success
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close(); // Close prepared statement
}
?>
