<?php
require_once 'includes/config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Güvenlik için kullanıcı giriş verilerini temizle
    $kullanici = guvenlik($_POST['kullanici']);
    $sifre = $_POST['sifre']; // Şifreyi direkt al, guvenlik() aşırı olabilir

    // Kullanıcı adıyla admin kaydını sorgula
    $sorgu = $db->prepare("SELECT * FROM admin WHERE kullanici_adi = :kullanici");
    $sorgu->execute(['kullanici' => $kullanici]);
    $admin = $sorgu->fetch();

    // Kullanıcı varsa ve şifre doğruysa
    if ($admin && password_verify($sifre, $admin['sifre'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_kullanici'] = $admin['kullanici_adi'];
        yonlendir("admin/index.php");
    } else {
        $hata = "Kullanıcı adı veya şifre yanlış.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
<title>Yönetici Girişi</title>
<link rel="stylesheet" href="assets/css/style.css" />
<style>
  /* Sayfa genel ayarları */
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #FFB347, #FFE066);
height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #333;
  }

  /* Giriş kutusu konteyneri */
  .container {
    background: #fff;
    padding: 40px 60px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    width: 100%;
    max-width: 400px;
    text-align: center;
    transition: transform 0.3s ease;
  }

  .container:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
  }

  /* Başlık */
  .container h1 {
    margin-bottom: 30px;
    font-weight: 700;
    font-size: 28px;
    color: #4a3aff;
  }

  input {
  width: 100%;
  padding: 14px 5px 14px 5px; /* sağ padding küçültüldü */
  margin: 12px 0 20px 0;
  border: 2px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 0.3s ease;
  outline: none;
}

input:focus {
  border-color: #4a3aff;
  box-shadow: 0 0 8px rgba(74, 58, 255, 0.5);
}


  button {
  width: auto;          
  padding: 14px 40px;   
  margin: 0 auto;       
  display: block;      
  background: #4a3aff;
  border: none;
  border-radius: 8px;
  color: white;
  font-weight: 600;
  font-size: 18px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  box-shadow: 0 6px 15px rgba(74, 58, 255, 0.4);
}


  button:hover {
    background: #3a2bdb;
    box-shadow: 0 8px 20px rgba(58, 43, 219, 0.6);
  }

  /* Hata mesajı */
  .error {
    color: #e74c3c;
    background: #fceae9;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(231, 76, 60, 0.3);
  }

  /* Responsive */
  @media (max-width: 480px) {
    .container {
      margin: 20px;
      padding: 30px 20px;
    }

    .container h1 {
      font-size: 24px;
    }
  }
</style>

</head>
<body>
    <div class="container">
        <h2>Yönetici Girişi</h2>
        <?php if(isset($hata)) echo "<p class='error'>$hata</p>"; ?>
        <form action="" method="POST" autocomplete="off">
            <input type="text" name="kullanici" placeholder="Kullanıcı Adı" required autofocus>
            <input type="password" name="sifre" placeholder="Şifre" required>
            <button type="submit">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
