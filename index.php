<?php
$sayfa_basligi = "Ana Sayfa";
include 'includes/config.php';
include 'includes/header.php';

// Öne çıkan tarifleri getir
$one_cikan_sorgu = $db->query("SELECT * FROM tarifler WHERE one_cikan = 1 ORDER BY id DESC LIMIT 3");
$one_cikan_tarifler = $one_cikan_sorgu->fetchAll();

// En çok görüntülenen tarifleri getir
$populer_sorgu = $db->query("SELECT * FROM tarifler ORDER BY goruntulenme DESC LIMIT 6");
$populer_tarifler = $populer_sorgu->fetchAll();

// Son eklenen tarifleri getir
$son_eklenen_sorgu = $db->query("SELECT * FROM tarifler ORDER BY eklenme_tarihi DESC LIMIT 9");
$son_eklenen_tarifler = $son_eklenen_sorgu->fetchAll();
?>

<!-- Banner Section -->
<section class="banner-section">
    <div id="banner-carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php for($i = 0; $i < count($one_cikan_tarifler); $i++): ?>
            <button type="button" data-bs-target="#banner-carousel" data-bs-slide-to="<?php echo $i; ?>" <?php echo ($i == 0) ? 'class="active"' : ''; ?> aria-current="<?php echo ($i == 0) ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
            <?php endfor; ?>
        </div>
        <div class="carousel-inner">
            <?php $first = true; foreach($one_cikan_tarifler as $tarif): ?>
            <div class="carousel-item <?php echo ($first) ? 'active' : ''; ?>">
                <img src="assets/img/tarifler/<?php echo $tarif['resim']; ?>" class="d-block w-100" alt="<?php echo $tarif['tarif_adi']; ?>">
                <div class="carousel-caption d-none d-md-block">
                    <div class="bg-dark bg-opacity-50 p-3 rounded">
                        <h2><?php echo $tarif['tarif_adi']; ?></h2>
                        <p><?php echo kisalt($tarif['aciklama'], 150); ?></p>
                        <a href="tarif_detay.php?id=<?php echo $tarif['id']; ?>" class="btn btn-primary">Tarifi Görüntüle</a>
                    </div>
                </div>
            </div>
            <?php $first = false; endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#banner-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Önceki</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#banner-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sonraki</span>
        </button>
    </div>
</section>

<!-- Kategoriler Section -->
<section class="container mt-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h2 class="section-title">Kategoriler</h2>
            <p class="section-subtitle">Damak tadınıza uygun tarifleri keşfedin</p>
        </div>
    </div>
    
    <div class="row">
        <?php foreach($kategoriler as $kategori): ?>
        <div class="col-md-4 mb-4">
            <div class="card category-card h-100">
                <img src="assets/img/kategoriler/<?php echo $kategori['resim']; ?>" class="card-img-top" alt="<?php echo $kategori['kategori_adi']; ?>">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo $kategori['kategori_adi']; ?></h5>
                    <p class="card-text"><?php echo kisalt($kategori['aciklama'], 100); ?></p>
                    <a href="kategori.php?id=<?php echo $kategori['id']; ?>" class="btn btn-outline-primary">Tarifleri Gör</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Popüler Tarifler Section -->
<section class="container mt-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h2 class="section-title">Popüler Tarifler</h2>
            <p class="section-subtitle">En çok beğenilen ve görüntülenen tariflerimiz</p>
        </div>
    </div>
    
    <div class="row">
        <?php foreach($populer_tarifler as $tarif): ?>
        <div class="col-md-4 mb-4">
            <div class="card recipe-card h-100">
                <div class="recipe-image">
                    <img src="assets/img/tarifler/<?php echo $tarif['resim']; ?>" class="card-img-top" alt="<?php echo $tarif['tarif_adi']; ?>">
                    <div class="recipe-overlay">
                        <a href="tarif_detay.php?id=<?php echo $tarif['id']; ?>" class="btn btn-sm btn-light"><i class="fas fa-eye"></i></a>
                    </div>
                    <div class="recipe-badge bg-primary"><?php echo $tarif['goruntulenme']; ?> görüntülenme</div>
                </div>
                <div class="card-body">
                    <div class="recipe-meta d-flex justify-content-between mb-2">
                        <span class="recipe-category"><i class="fas fa-tag"></i> <?php 
                            $kat_sorgu = $db->prepare("SELECT kategori_adi FROM kategoriler WHERE id = :id");
                            $kat_sorgu->execute(['id' => $tarif['kategori_id']]);
                            $kategori = $kat_sorgu->fetch();
                            echo $kategori['kategori_adi'];
                        ?></span>
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
                    <div class="recipe-time"><i class="far fa-clock"></i> <?php echo $tarif['sure']; ?> dk</div>
                    <div class="recipe-portions"><i class="fas fa-utensils"></i> <?php echo $tarif['porsiyon']; ?> kişilik</div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-4">
        <a href="tarifler.php" class="btn btn-lg btn-primary">Tüm Tarifleri Gör</a>
    </div>
</section>

<!-- Son Eklenen Tarifler Section -->
<section class="container mt-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h2 class="section-title">Son Eklenen Tarifler</h2>
            <p class="section-subtitle">En yeni tariflerimizi keşfedin</p>
        </div>
    </div>
    
    <div class="row">
        <?php foreach($son_eklenen_tarifler as $tarif): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card latest-recipe-card h-100">
                <div class="row g-0">
                    <div class="col-4">
                        <img src="assets/img/tarifler/<?php echo $tarif['resim']; ?>" class="img-fluid rounded-start h-100" alt="<?php echo $tarif['tarif_adi']; ?>" style="object-fit: cover;">
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <h5 class="card-title"><a href="tarif_detay.php?id=<?php echo $tarif['id']; ?>" class="text-decoration-none"><?php echo $tarif['tarif_adi']; ?></a></h5>
                            <p class="card-text small"><?php echo kisalt($tarif['aciklama'], 80); ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted"><i class="far fa-calendar-alt"></i> <?php echo tarih_formati($tarif['eklenme_tarihi']); ?></small>
                                <a href="tarif_detay.php?id=<?php echo $tarif['id']; ?>" class="btn btn-sm btn-outline-primary">Detaylar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Mutfak İpuçları Section -->
<section class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h2 class="section-title">Mutfak İpuçları</h2>
            <p class="section-subtitle">Lezzetli yemekler için püf noktaları</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card kitchen-tip-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-lightbulb text-warning me-2"></i> Ekmek Yapımında</h5>
                    <p class="card-text">Ekmek hamurunuzu mayalandırırken, hamurun üzerini nemli bir bezle örtün ve sıcak bir ortamda bekletin. Bu şekilde hamurunuz daha hızlı ve daha iyi kabaracaktır.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card kitchen-tip-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-lightbulb text-warning me-2"></i> Et Pişirirken</h5>
                    <p class="card-text">Eti pişirmeden önce oda sıcaklığında yaklaşık 30 dakika bekletin. Bu şekilde et daha eşit pişecek ve daha lezzetli olacaktır.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card kitchen-tip-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-lightbulb text-warning me-2"></i> Sebzeler İçin</h5>
                    <p class="card-text">Sebzeleri haşlarken suyuna bir tutam tuz ve bir çay kaşığı şeker ekleyin. Bu şekilde sebzeler hem renklerini korur hem de daha lezzetli olur.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card kitchen-tip-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-lightbulb text-warning me-2"></i> Tatlılar İçin</h5>
                    <p class="card-text">Kek ve kurabiye tarifleri için oda sıcaklığındaki yumurta kullanın. Soğuk yumurtalar hamuru kabartmak için yeterince hava tutamaz.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
