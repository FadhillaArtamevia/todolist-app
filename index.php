<?php
session_start();

// Inisialisasi daftar tugas jika belum ada
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [
        ['judul' => 'Belajar PHP', 'status' => 'Belum'],
        ['judul' => 'Kerjakan tugas kuliah', 'status' => 'Selesai']
    ];
}
$tasks = $_SESSION['tasks'];

// Tambah tugas baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tugas']) && !empty(trim($_POST['tugas']))) {
    $tasks[] = ['judul' => htmlspecialchars($_POST['tugas']), 'status' => 'Belum'];
}

// Hapus tugas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus'])) {
    $index = $_POST['hapus'];
    if (isset($tasks[$index])) {
        unset($tasks[$index]);
        $tasks = array_values($tasks); // Reindex array
    }
}

// Ubah status tugas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status']) && isset($_POST['index'])) {
    $index = $_POST['index'];
    if (isset($tasks[$index])) {
        $tasks[$index]['status'] = ($tasks[$index]['status'] === 'Selesai') ? 'Belum' : 'Selesai';
    }
}

// Simpan kembali ke session
$_SESSION['tasks'] = $tasks;

// Fungsi tampilkan daftar tugas
function tampilkanDaftar($tasks) {
    foreach ($tasks as $index => $task) {
        echo "<li>";
        echo "<form method='POST' class='task-item'>";
        echo "<input type='checkbox' name='status' value='toggle' onchange='this.form.submit()' " . ($task['status'] === 'Selesai' ? 'checked' : '') . ">";
        echo "<span class='judul'>" . htmlspecialchars($task['judul']) . "</span>";
        echo "<span class='status {$task['status']}'>" . htmlspecialchars($task['status']) . "</span>";
        echo "<button type='submit' name='hapus' value='{$index}' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus tugas ini?\")'>Hapus</button>";
        echo "<input type='hidden' name='index' value='{$index}'>";
        echo "</form>";
        echo "</li>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ToDo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>ToDo List</h1>
    </header>

    <section class="form">
        <form method="post" action="">
            <input type="text" name="tugas" placeholder="Tulis tugas baru..." required>
            <button type="submit">Tambah</button>
        </form>
    </section>

    <section class="daftar">
        <h2>Daftar Tugas</h2>
        <ul>
            <?php tampilkanDaftar($tasks); ?>
        </ul>
    </section>
</body>
</html>
