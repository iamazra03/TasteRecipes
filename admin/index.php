<?php

require_once '../includes/config.php'; 
admin_kontrol(); 

// Giriş yapan admin'in adı
$admin_adi = isset($_SESSION['admin_kullanici']) ? $_SESSION['admin_kullanici'] : 'Admin';

?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetici Paneli</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <!-- 3 saniye sonra anasayfaya yönlendirme -->
    <meta http-equiv="refresh" content="3;url=http://localhost/yemek_tarifleri/index.php">

    <style>
        .user-info {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4a3aff;
            color: white;
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 12px rgba(74, 58, 255, 0.5);
            z-index: 1000;
        }

        .user-info button {
            background: #fff;
            color: #4a3aff;
            border: none;
            border-radius: 20px;
            padding: 6px 12px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .user-info button:hover {
            background-color: #e6e6ff;
        }
    </style>
</head>
<body>
    <div class="user-info">
        Hoşgeldin, <?php echo htmlspecialchars($admin_adi); ?>
        <form action="logout.php" method="post" style="margin:0;">
            <button type="submit" name="logout">Çıkış Yap</button>
        </form>
    </div>

    <h1>Yönetici Paneline Hoş Geldiniz, <?php echo htmlspecialchars($_SESSION['admin_kullanici']); ?>!</h1>
    <p>3 saniye içinde anasayfaya yönlendirileceksiniz...</p>

</body>
</html>
