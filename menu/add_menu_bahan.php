<?php
require_once '../functions.php';
ob_start();
session_start();

// Inisialisasi array bahan_terpilih di session jika belum ada
if (!isset($_SESSION['bahan_terpilih'])) {
    $_SESSION['bahan_terpilih'] = [];
}

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi untuk mencari bahan
function cariBahan($bahan) {
    global $conn;
    $bahan = $conn->real_escape_string($bahan); // Prevent SQL injection
    $sql = "SELECT * FROM bahan WHERE nama_bahan LIKE '%$bahan%'";
    return $conn->query($sql);
}

// Fungsi untuk menambah menu baru
function tambahMenu($nama_menu, $harga, $kategori, $stok, $bahan_list) {
    global $conn;
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Prepare statement untuk menu
        $query = "INSERT INTO menu (nama_menu, harga_menu, jenis_menu, stok) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        // Bind parameters dan execute
        if (!$stmt->bind_param("sisi", $nama_menu, $harga, $kategori, $stok)) {
            throw new Exception("Binding parameters failed: " . $stmt->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $id_menu = $conn->insert_id;
        
        // Prepare statement untuk detail_menu
        $detail_query = "INSERT INTO menu_bahan (id_menu, id_bahan, jumlah_bahan) VALUES (?, ?, ?)";
        $detail_stmt = $conn->prepare($detail_query);
        
        if ($detail_stmt === false) {
            throw new Exception("Prepare detail failed: " . $conn->error);
        }
    
        foreach ($bahan_list as $id_bahan => $detail) {
            $jumlah = $detail['jumlah'];
            if (!$detail_stmt->bind_param("iii", $id_menu, $id_bahan, $jumlah)) {
                throw new Exception("Binding parameters failed: " . $detail_stmt->error);
            }
            if (!$detail_stmt->execute()) {
                throw new Exception("Execute failed: " . $detail_stmt->error);
            }

            // Update stok bahan
            $update_query = "UPDATE bahan SET stok = stok - ? WHERE id_bahan = ?";
            $update_stmt = $conn->prepare($update_query);
            if ($update_stmt === false) {
                throw new Exception("Prepare update failed: " . $conn->error);
            }
            if (!$update_stmt->bind_param("ii", $jumlah, $id_bahan)) {
                throw new Exception("Binding parameters for update failed: " . $update_stmt->error);
            }
            if (!$update_stmt->execute()) {
                throw new Exception("Execute update failed: " . $update_stmt->error);
            }
        }
        
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Handle tambah bahan ke daftar
if (isset($_POST['tambah']) && isset($_POST['id_bahan'])) {
    $id_bahan = $_POST['id_bahan'];
    $query = "SELECT * FROM bahan WHERE id_bahan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_bahan);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Cek apakah bahan sudah ada di daftar
        if (!isset($_SESSION['bahan_terpilih'][$id_bahan])) {
            $_SESSION['bahan_terpilih'][$id_bahan] = [
                'nama_bahan' => $row['nama_bahan'],
                'stok' => $row['stok'],
                'jumlah' => 0
            ];
        }
    }
}

// Handle pembuatan menu baru
if (isset($_POST['buat_menu'])) {
    $nama_menu = $_POST['nama_menu'];
    $harga_menu = $_POST['harga_menu'];
    $kategori_menu = $_POST['kategori_menu'];
    $stok_menu = $_POST['stok_menu'];

    // Validasi input
    if (empty($_SESSION['bahan_terpilih'])) {
        $error = "Pilih bahan terlebih dahulu!";
    } elseif (empty($nama_menu) || empty($harga_menu) || empty($kategori_menu) || empty($stok_menu)) {
        $error = "Semua field harus diisi!";
    } else {
        // Cek apakah jumlah bahan sudah diisi semua
        $valid = true;
        foreach ($_SESSION['bahan_terpilih'] as $bahan) {
            if ($bahan['jumlah'] <= 0) {
                $valid = false;
                break;
            }
        }
        
        if ($valid) {
            if (tambahMenu($nama_menu, $harga_menu, $kategori_menu, $stok_menu, $_SESSION['bahan_terpilih'])) {
                $_SESSION['bahan_terpilih'] = []; // Reset daftar bahan
                $success = "Menu berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan menu!";
            }
        } else {
            $error = "Isi jumlah untuk semua bahan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">
            <a href="../order/chef.php" class="text-blue-600 hover:underline">
                &#8592; Kembali
            </a>
        </h1>
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Tambah Menu Baru</h2>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $success; ?>
            </div>
            <script>
                // Menampilkan alert berhasil menggunakan JavaScript
                alert('Menu berhasil ditambahkan!');
            </script>
        <?php endif; ?>

        <!-- Form pencarian bahan -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="POST" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label for="bahan" class="block text-sm font-medium text-gray-700 mb-2">Cari Bahan:</label>
                    <input type="text" name="bahan" id="bahan" required 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <button type="submit" name="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Cari
                </button>
            </form>
        </div>

        <!-- Hasil pencarian -->
        <?php if (isset($_POST['submit']) && !empty($_POST['bahan'])): ?>
            <?php $bahan = cariBahan($_POST['bahan']); ?>
            <?php if ($bahan->num_rows > 0): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <form method="POST" class="flex gap-4">
                        <select name="id_bahan" required 
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <?php while ($row = $bahan->fetch_assoc()): ?>
                                <option value="<?php echo $row['id_bahan']; ?>">
                                    <?php echo $row['nama_bahan'] . ' (Stok: ' . $row['stok'] . ')'; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit" name="tambah" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                            Tambah ke Daftar
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-8">
                    Tidak ada bahan ditemukan.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Daftar bahan yang dipilih -->
        <?php if (!empty($_SESSION['bahan_terpilih'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Bahan-bahan yang Dipilih:</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Bahan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stok Tersedia
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah yang Dibutuhkan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($_SESSION['bahan_terpilih'] as $id => $bahan): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $bahan['nama_bahan']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $bahan['stok']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="POST" class="flex gap-2">
                                            <input type="number" name="jumlah[<?php echo $id; ?>]" 
                                                   value="<?php echo $bahan['jumlah']; ?>" 
                                                   min="1" max="<?php echo $bahan['stok']; ?>" required
                                                   class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <button type="submit" name="update_jumlah" 
                                                    class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                                Ambil
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="hapus_id" value="<?php echo $id; ?>">
                                            <button type="submit" name="hapus" 
                                                    onclick="return confirm('Yakin ingin menghapus?')"
                                                    class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition-colors text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form tambah menu -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Detail Menu:</h3>
                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_menu" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Menu:
                            </label>
                            <input type="text" name="nama_menu" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="harga_menu" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga:
                            </label>
                            <input type="number" name="harga_menu" min="0" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="kategori_menu" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori:
                            </label>
                            <select name="kategori_menu" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="stok_menu" class="block text-sm font-medium text-gray-700 mb-2">
                                Stok Awal:
                            </label>
                            <input type="number" name="stok_menu" min="1" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" name="buat_menu"
                                class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors">
                            Buat Menu
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php
    // Handle update jumlah
    if (isset($_POST['update_jumlah']) && isset($_POST['jumlah'])) {
        foreach ($_POST['jumlah'] as $id => $jumlah) {
            if (isset($_SESSION['bahan_terpilih'][$id])) {
                $_SESSION['bahan_terpilih'][$id]['jumlah'] = $jumlah;
            }
        }
        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Handle hapus bahan
    if (isset($_POST['hapus']) && isset($_POST['hapus_id'])) {
        unset($_SESSION['bahan_terpilih'][$_POST['hapus_id']]);
        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    ?>
</body>
</html>