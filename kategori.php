<?php
// Kategori ID kontrolü
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$kategori_id = (int)$_GET['id'];

include 'includes/header.php';

// Kategoriyi getir
$kategori_sorgu = $db->prepare("SELECT * FROM kategoriler WHERE id = :id");
$kategori_sorgu->execute(['id' => $kategori_id]);
$kategori = $kategori_sorgu->fetch();

// Kategori bulunamadıysa ana sayfaya yönlendir
if(!$kategori) {
    header('Location: index.php');
    exit;
}

// Sayfa başlığını ayarla
$sayfa_basligi = $kategori['kategori_adi'];

// Sayfalama
$sayfa = isset($_GET['sayfa']) && is_numeric($_GET['sayfa']) ? (int)$_GET['sayfa'] : 1;
$limit = 9;
$baslangic = ($sayfa - 1) * $limit;

// Kategorideki tarifleri getir
$tarif_sorgu = $db->prepare("SELECT * FROM tarifler WHERE kategori_id = :kategori_id ORDER BY eklenme_tarihi DESC LIMIT :baslangic, :limit");
$tarif_sorgu->bindParam(':kategori_id', $kategori_id, PDO::PARAM_INT);
$tarif_sorgu->bindParam(':baslangic', $baslangic, PDO::PARAM_INT);
$tarif_sorgu->bindParam(':limit', $limit, PDO::PARAM_INT);
$tarif_sorgu->execute();
$tarifler = $tarif_sorgu->fetchAll();

// Toplam tarif sayısını hesapla
$toplam_sorgu = $db->prepare("SELECT COUNT(*) FROM tarifler WHERE kategori_id = :kategori_id");
$toplam_sorgu->execute(['kategori_id' => $kategori_id]);
$toplam_tarif = $toplam_sorgu->fetchColumn();

// Toplam sayfa sayısını hesapla
$toplam_sayfa = ceil($toplam_tarif / $limit);
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt-2 mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Ana Sayfa</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $kategori['kategori_adi']; ?></li>
        </ol>
    </nav>
    
    <!-- Kategori Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card category-banner">
                <img src="assets/img/kategoriler/<?php echo $kategori['resim']; ?>" class="card-img" alt="<?php echo $kategori['kategori_adi']; ?>">
                <div class="card-img-overlay d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 col-lg-6">
                                <div class="bg-dark bg-opacity-75 p-4 rounded text-white">
                                    <h1 class="card-title"><?php echo $kategori['kategori_adi']; ?></h1>
                                    <p class="card-text"><?php echo $kategori['aciklama']; ?></p>
                                    <p class="card-text"><small>Bu kategoride <?php echo $toplam_tarif; ?> tarif bulunmaktadır.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tarifler -->
    <div class="row">
        <?php if(count($tarifler) > 0): ?>
            <?php foreach($tarifler as $tarif): ?>
            <div class="col-md-4 mb-4">
                <div class="card recipe-card h-100">
                    <div class="recipe-image">
                        <img src="assets/img/tarifler/<?php echo $tarif['resim']; ?>" class="card-img-top" alt="<?php echo $tarif['tarif_adi']; ?>">
                        <div class="recipe-overlay">
                            <a href="tarif_detay.php?id=<?php echo $tarif['id']; ?>" class="btn btn-sm btn-light"><i class="fas fa-eye"></i></a>
                        </div>
                        <?php if($tarif['one_cikan']): ?>
                        <div class="recipe-featured"><i class="fas fa-star"></i> Öne Çıkan</div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="recipe-meta d-flex justify-content-between mb-2">
                            <span class="recipe-time"><i class="far fa-clock"></i> <?php echo $tarif['sure']; ?> dk</span>
                            <span class="recipe-difficulty badge <?php 
                                if($tarif['zorluk'] == 'Kolay') echo 'bg-success';
                                elseif($tarif['zorluk'] == 'Orta') echo 'bg-warning text-dark';
                                else echo 'bg-danger';
                            ?>"><?php echo $tarif['zorluk']; ?></span>
                        </div>
                        <h5 class="card-title"><a href="tarif_detay.php?id=<?php echo $tarif['id']; ?>" class="text-decoration-none"><?php echo $tarif['tarif_adi']; ?></a></h5>
                        <p class="card-text"><?php echo kisalt($tarif['aciklama'], 100); ?></p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <span><i class="far fa-calendar-alt"></i> <?php echo tarih_formati($tarif['eklenme_tarihi']); ?></span>
                        <a href="tarif_detay.php?id=<?php echo $tarif['id']; ?>" class="btn btn-sm btn-outline-primary">İncele</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Bu kategoride henüz tarif bulunmamaktadır.
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Sayfalama -->
    <?php if($toplam_sayfa > 1): ?>
    <div class="row">
        <div class="col-12">
            <nav aria-label="Kategori Tarifleri Sayfalama">
                <ul class="pagination justify-content-center">
                    <?php if($sayfa > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="kategori.php?id=<?php echo $kategori_id; ?>&sayfa=<?php echo $sayfa - 1; ?>" aria-label="Önceki">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for($i = max(1, $sayfa - 2); $i <= min($sayfa + 2, $toplam_sayfa); $i++): ?>
                    <li class="page-item <?php echo ($i == $sayfa) ? 'active' : ''; ?>">
                        <a class="page-link" href="kategori.php?id=<?php echo $kategori_id; ?>&sayfa=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if($sayfa < $toplam_sayfa): ?>
                    <li class="page-item">
                        <a class="page-link" href="kategori.php?id=<?php echo $kategori_id; ?>&sayfa=<?php echo $sayfa + 1; ?>" aria-label="Sonraki">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Diğer Kategoriler -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="text-center mb-4">Diğer Kategoriler</h3>
        </div>
        
        <?php 
        // Diğer kategorileri getir
        $diger_kategoriler_sorgu = $db->prepare("SELECT * FROM kategoriler WHERE id != :id ORDER BY RAND() LIMIT 3");
        $diger_kategoriler_sorgu->execute(['id' => $kategori_id]);
        $diger_kategoriler = $diger_kategoriler_sorgu->fetchAll();
        
        foreach($diger_kategoriler as $diger_kat): 
        ?>
        <div class="col-md-4 mb-4">
            <div class="card other-category-card">
                <img src="assets/img/kategoriler/<?php echo $diger_kat['resim']; ?>" class="card-img-top" alt="<?php echo $diger_kat['kategori_adi']; ?>">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo $diger_kat['kategori_adi']; ?></h5>
                    <p class="card-text"><?php echo kisalt($diger_kat['aciklama'], 80); ?></p>
                    <a href="kategori.php?id=<?php echo $diger_kat['id']; ?>" class="btn btn-primary">Tarifleri Gör</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>