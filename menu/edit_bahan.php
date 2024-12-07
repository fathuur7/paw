<?php
// Koneksi ke database
require_once '../functions.php';

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID bahan dari URL
$id_bahan = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_bahan) {
    // Query untuk mengambil data bahan berdasarkan ID
    $sql = "SELECT * FROM bahan WHERE id_bahan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_bahan);
    $stmt->execute();
    $result = $stmt->get_result();
    $bahan = $result->fetch_assoc();

    // Jika bahan ditemukan
    if ($bahan) {
        $nama_bahan = $bahan['nama_bahan'];
        $stok = $bahan['stok'];
        $harga_beli = $bahan['harga_beli'];
    } else {
        // Alihkan ke halaman daftar bahan jika bahan tidak ditemukan
        header("Location: bahan_list.php");
        exit;
    }
} else {
    // Alihkan ke halaman daftar bahan jika ID tidak valid
    header("Location: bahan_list.php");
    exit;
}

// Tangani form edit bahan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_bahan = $_POST['nama_bahan'];
    $stok = $_POST['stok'];
    $harga_beli = $_POST['harga_beli'];

    // Query untuk memperbarui data bahan
    $sql = "UPDATE bahan SET nama_bahan = ?, stok = ?, harga_beli = ? WHERE id_bahan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidi", $nama_bahan, $stok, $harga_beli, $id_bahan);

    if ($stmt->execute()) {
        echo "<script>alert('Bahan berhasil diperbarui.'); window.location.href='../order/chef.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui bahan.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bahan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Judul Halaman -->
        <h1 class="text-4xl font-bold text-center text-gray-900 mb-8">Edit Bahan</h1>

        <!-- Tombol Kembali -->
        <a href="../order/chef.php" class="bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 transition duration-300 mb-6 inline-block">Kembali</a>

        <!-- Form Edit Bahan -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id_bahan); ?>" class="space-y-4 bg-white p-6 rounded-lg shadow-lg">
            <div>
                <label for="nama_bahan" class="block text-gray-700 font-medium">Nama Bahan</label>
                <input type="text" name="nama_bahan" id="nama_bahan" value="<?php echo htmlspecialchars($nama_bahan); ?>" required class="w-full border rounded px-4 py-2 mt-1">
            </div>
            <div>
                <label for="stok" class="block text-gray-700 font-medium">Stok</label>
                <input type="number" name="stok" id="stok" value="<?php echo htmlspecialchars($stok); ?>" required class="w-full border rounded px-4 py-2 mt-1">
            </div>
            <div>
                <label for="harga_beli" class="block text-gray-700 font-medium">Harga Beli</label>
                <input type="number" name="harga_beli" id="harga_beli" value="<?php echo htmlspecialchars($harga_beli); ?>" min="1" step="0.01" required class="w-full border rounded px-4 py-2 mt-1">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition duration-300 mt-4 w-full">Perbarui Bahan</button>
        </form>
    </div>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>