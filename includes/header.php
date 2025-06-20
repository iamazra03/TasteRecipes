<?php
require_once 'includes/config.php';
$admin_adi = $_SESSION['admin_kullanici'] ?? null;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?> - <?php echo isset($sayfa_basligi) ? $sayfa_basligi : 'Türk ve Dünya Mutfağından Lezzetli Tarifler'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- Theme Color -->
    <meta name="theme-color" content="#712cf9">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php" style="display: inline-flex; align-items: center; gap: 10px;">
                <img src="assets/img/tariflezzetleri.png" alt="<?php echo SITE_TITLE; ?>" style="max-height: 60px; width:auto;">
                <span style="color: #800000; font-family: 'Georgia', serif; font-weight: 700; font-size: 1.5rem;">
                    <?php echo SITE_TITLE; ?>
                </span>
            </a>

            <!-- Mobil menü butonu -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Menüyü Aç">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menü içeriği -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo (basename($_SERVER['PHP_SELF']) == 'kategori.php') ? 'active' : ''; ?>" href="#" id="kategorilerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kategoriler
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="kategorilerDropdown">
                            <?php 
                            $kategoriler = kategorileri_getir();
                            foreach($kategoriler as $kategori): 
                            ?>
                            <li><a class="dropdown-item" href="kategori.php?id=<?php echo $kategori['id']; ?>"><?php echo $kategori['kategori_adi']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'tarifler.php') ? 'active' : ''; ?>" href="tarifler.php">Tüm Tarifler</a>
                    </li>
                </ul>

                <!-- Yönetici Giriş / Hoşgeldin -->
                <div class="d-flex align-items-center ms-3">
                    <?php if ($admin_adi): ?>
                        <span class="me-2 text-dark fw-bold">Hoşgeldin, <?php echo htmlspecialchars($admin_adi); ?></span>
                        <a href="cikis.php" class="btn btn-outline-danger btn-sm">Çıkış Yap</a>
                    <?php else: ?>
                        <a href="giris.php" class="btn btn-outline-primary btn-sm">Yönetici Girişi</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="py-5">
