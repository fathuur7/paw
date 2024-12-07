<?php
// Koneksi ke database
require_once '../functions.php';

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi untuk menampilkan daftar bahan
function displayBahans() {
    global $conn;

    // Query untuk mengambil data dari tabel bahan
    $sql = "SELECT * FROM bahan";
    $result = $conn->query($sql);

    // Mulai struktur HTML
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Daftar Bahan</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Judul Halaman -->
            <h1 class="text-4xl font-bold text-center text-gray-900 mb-8">Daftar Bahan</h1>

            <!-- Tombol Kembali -->
            <a href="../order/chef.php" class="bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 transition duration-300 mb-6 inline-block">Kembali</a>

            <!-- Tabel Data Bahan -->
            <div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
                <table class="table-auto w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">ID Bahan</th>
                            <th class="px-4 py-2 text-left">Nama Bahan</th>
                            <th class="px-4 py-2 text-left">Stok</th>
                            <th class="px-4 py-2 text-left">Harga Beli</th>
                            <!-- <th class="px-4 py-2 text-left">Stok</th> -->
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='border-t'>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["id_bahan"]) . "</td>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["nama_bahan"]) . "</td>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["stok"]) . "</td>";
                                echo "<td class='px-4 py-2'>Rp " . number_format($row["harga_beli"], 2, ',', '.') . "</td>";
                                // echo "<td class='px-4 py-2'>
                                //         <form method='POST' action='sentok_bahan.php?id=" . $row["id_bahan"] . "' class='flex gap-2'>
                                //             <input type='number' name='sentok_jumlah' min='1' max='" . $row["stok"] . "' required class='border rounded px-2 py-1 w-24'>
                                //             <button type='submit' class='bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700'>Sentok</button>
                                //         </form>
                                //       </td>";
                                echo "<td class='px-4 py-2'>
                                        <a href='edit_bahan.php?id=" . $row["id_bahan"] . "' class='bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600'>Edit</a> | 
                                        <a href='delete_bahan.php?id=" . $row["id_bahan"] . "' class='bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600'>Hapus</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4'>Tidak ada bahan yang tersedia.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Form Tambah Bahan -->
            <h2 class="text-3xl font-bold text-gray-900 mt-12 mb-6">Tambah Bahan Baru</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-4 bg-white p-6 rounded-lg shadow-lg">
                <div>
                    <label for="nama_bahan" class="block text-gray-700 font-medium">Nama Bahan</label>
                    <input type="text" name="nama_bahan" id="nama_bahan" required class="w-full border rounded px-4 py-2 mt-1">
                </div>
                <div>
                    <label for="stok" class="block text-gray-700 font-medium">Stok</label>
                    <input type="number" name="stok" id="stok" required placeholder="Masukkan jumlah stok" class="w-full border rounded px-4 py-2 mt-1">
                </div>
                <div>
                    <label for="harga_beli" class="block text-gray-700 font-medium">Harga Beli</label>
                    <input type="number" name="harga_beli" id="harga_beli" min="1" step="0.01" required class="w-full border rounded px-4 py-2 mt-1">
                </div>
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition duration-300 mt-4 w-full">Tambah Bahan</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}

// Tangani form tambah bahan baru
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_bahan = $_POST['nama_bahan'];
    $stok = $_POST['stok'];
    $harga_beli = $_POST['harga_beli'];

    $sql = "INSERT INTO bahan (nama_bahan, stok, harga_beli) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sid", $nama_bahan, $stok, $harga_beli);

    if ($stmt->execute()) {
        echo "<script>alert('Bahan baru berhasil ditambahkan.'); window.location.href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan bahan baru.');</script>";
    }

    $stmt->close();
}

// Panggil fungsi untuk menampilkan daftar bahan
displayBahans();

// Tutup koneksi
$conn->close();
?>