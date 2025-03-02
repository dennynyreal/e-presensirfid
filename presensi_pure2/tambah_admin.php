<?php
include "koneksi.php";

if (isset($_POST['btnSimpan'])) {
    // Mengambil dan membersihkan data dari form
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password'])); // Assuming raw password input
    $role = htmlspecialchars(trim($_POST['role'])); // Assuming role is a field

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $stmt = $konek->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    if ($data['count'] > 0) {
        echo "<script>
        alert('Username sudah ada. Mohon gunakan username yang berbeda.');
        window.location.href = 'tambah_admin.php';
        </script>";
        exit;
    }
    $stmt->close();

    // Jika validasi lolos, masukkan data ke dalam database
    $stmt = $konek->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password_hash, $role);

    if ($stmt->execute()) {
        echo "<script>
        alert('Data admin berhasil disimpan');
        window.location.href = 'data_admin.php';
        </script>";
    } else {
        echo "<script>
        alert('Gagal menyimpan data: " . $stmt->error . "');
        window.location.href = 'tambah_admin.php';
        </script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "header.php"; ?>

    <!-- Link Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript">
        function confirmLogout() {
            if (confirm('Anda yakin ingin logout?')) {
                window.location.href = 'logout.php';
            }
        }
    </script>

    <title>Tambah Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;700&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        body {
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .header-container {
            width: calc(100% - 269px);
            height: 70px;
            position: fixed;
            top: 0;
            left: 269px;
            background: white;
            border-bottom: 1px #EFF3FF solid;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .header-content {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 16.5px;
            font-weight: 600;
            color: #98A6AD;
        }

        .logout-button {
            background: #FBF2EF;
            border-radius: 7px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            border: none;
            cursor: pointer;
            font-size: 14.5px;
            font-weight: 700;
            color: #DC3545;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-button i {
            margin-right: 8px;
            transition: color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #F8D7DA;
            transform: scale(1.05);
        }

        .logout-button:hover i {
            color: #C82333;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            margin-top: 32px;
            margin-left: 269px;
            background: #f9f9f9;
            padding-bottom: 60px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .headline {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            border-bottom: 3px solid #5D87FF;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 16px;
        }

        .form-group input,
        .form-group select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
            width: 100%;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #5D87FF;
            outline: none;
            box-shadow: 0 0 5px rgba(93, 135, 255, 0.2);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            margin-top: 30px;
        }

        .btn-save {
            background-color: #5D87FF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 12px 24px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            text-align: center;
        }

        .btn-save:hover {
            background-color: #4a6ee0;
        }

        .footer {
            height: 60px;
            padding: 20px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #98A6AD;
            font-size: 13px;
            line-height: 19.5px;
            font-weight: 400;
            box-shadow: 0 -1px 2px rgba(0, 0, 0, 0.1);
            border-top: 1px solid #e0e0e0;
            margin-top: auto;
        }

        .footer .left,
        .footer .right {
            flex: 1;
        }

        .footer .left {
            text-align: left;
        }

        .footer .right {
            text-align: right;
        }
    </style>
</head>
<body>
    <?php include "menu.php"; ?>

    <div class="header-container">
        <div class="header-content">
            <div class="header-title">Tambah Admin</div>
            <button class="logout-button" onclick="confirmLogout()">
                <i class="fas fa-sign-out-alt"></i> LOG OUT
            </button>
        </div>
    </div>

    <div class="main-content">
        <div class="container">
            <h4 class="headline">Tambah Admin</h4>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" required>
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save" name="btnSimpan" id="btnSimpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer">
        <div class="left">2024 © van Derren</div>
        <div class="right">Design & Develop by van Derren</div>
    </div>
</body>
</html>
