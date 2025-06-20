<?php
$sayfa_basligi = "Tüm Tarifler";
include 'includes/header.php';

// Arama işlemi
$aranan = isset($_GET['q']) ? guvenlik($_GET['q']) : '';
$zorluk = isset($_GET['zorluk']) ? guvenlik($_GET['zorluk']) : '';
$sure = isset($_GET['sure']) ? (int)$_GET['sure'] : 0;

// Sayfalama
$sayfa = isset($_GET['sayfa']) && is_numeric($_GET['sayfa']) ? (int)$_GET['sayfa'] : 1;
$limit = 12;
$baslangic = ($sayfa - 1) * $limit;

// Filtreleme koşullarını oluştur
$where = [];
$params = [];

if(!empty($aranan)) {
    $where[] = "(tarif_adi LIKE :aranan OR aciklama LIKE :aranan OR malzemeler LIKE :aranan)";
    $params['aranan'] = "%$aranan%";
}

if(!empty($zorluk)) {
    $where[] = "zorluk = :zorluk";
    $params['zorluk'] = $zorluk;
}

if($sure > 0) {
    $where[] = "sure <= :sure";
    $params['sure'] = $sure;
}

// SQL sorgusunu oluştur
$sql = "SELECT * FROM tarifler";
if(!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY eklenme_tarihi DESC LIMIT :baslangic, :limit";

// Sorguyu hazırla ve çalıştır
$sorgu = $db->prepare($sql);

// Parametreleri bağla
foreach($params as $key => $value) {
    $sorgu->bindValue(':' . $key, $value);
}
$sorgu->bindValue(':baslangic', $baslangic, PDO::PARAM_INT);
$sorgu->bindValue(':limit', $limit, PDO::PARAM_INT);

$sorgu->execute();
$tarifler = $sorgu->fetchAll();

// Toplam tarif sayısını hesapla
$toplam_sql = "SELECT COUNT(*) FROM tarifler";
if(!empty($where)) {
    $toplam_sql .= " WHERE " . implode(' AND ', $where);
}
$toplam_sorgu = $db->prepare($toplam_sql);
foreach($params as $key => $value) {
    $toplam_sorgu->bindValue(':' . $key, $value);
}
$toplam_sorgu->execute();
$toplam_tarif = $toplam_sorgu->fetchColumn();

// Toplam sayfa sayısını hesapla
$toplam_sayfa = ceil($toplam_tarif / $limit);
?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 text-center">Tüm Tarifler</h1>
            <p class="lead text-center">Sizin için özenle hazırlanmış lezzetli ve pratik tarifler</p>
        </div>
    </div>
    
    <!-- Filtreler -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="tarifler.php" method="GET" class="row g-3">
                <div class="col-md-6">
                    <label for="q" class="form-label">Ara</label>
                    <input type="text" class="form-control" id="q" name="q" placeholder="Tarif adı, malzeme vb." value="<?php echo $aranan; ?>">
                </div>
                <div class="col-md-3">
                    <label for="zorluk" class="form-label">Zorluk Seviyesi</label>
                    <select class="form-select" id="zorluk" name="zorluk">
                        <option value="">Tümü</option>
                        <option value="Kolay" <?php echo ($zorluk == 'Kolay') ? 'selected' : ''; ?>>Kolay</option>
                        <option value="Orta" <?php echo ($zorluk == 'Orta') ? 'selected' : ''; ?>>Orta</option>
                        <option value="Zor" <?php echo ($zorluk == 'Zor') ? 'selected' : ''; ?>>Zor</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sure" class="form-label">Hazırlama Süresi</label>
                    <select class="form-select" id="sure" name="sure">
                        <option value="">Tümü</option>
                        <option value="15" <?php echo ($sure == 15) ? 'selected' : ''; ?>>15 dk ve altı</option>
                        <option value="30" <?php echo ($sure == 30) ? 'selected' : ''; ?>>30 dk ve altı</option>
                        <option value="45" <?php echo ($sure == 45) ? 'selected' : ''; ?>>45 dk ve altı</option>
                        <option value="60" <?php echo ($sure == 60) ? 'selected' : ''; ?>>60 dk ve altı</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <?php if(!empty($aranan) || !empty($zorluk) || $sure > 0): ?>
                    <a href="tarifler.php" class="btn btn-outline-secondary me-2">Filtreleri Temizle</a>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Filtrele</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tarifler -->
    <div class="row">
        <?php if(count($tarifler) > 0): ?>
            <?php foreach($tarifler as $tarif): ?>
            <div class="col-lg-4 col-md-6 mb-4">
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
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Aradığınız kriterlere uygun tarif bulunamadı.
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Sayfalama -->
    <?php if($toplam_sayfa > 1): ?>
    <div class="row">
        <div class="col-12">
            <nav aria-label="Tarifler Sayfalama">
                <ul class="pagination justify-content-center">
                    <?php if($sayfa > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="tarifler.php?sayfa=<?php echo $sayfa - 1; ?><?php echo (!empty($aranan)) ? '&q='.$aranan : ''; ?><?php echo (!empty($zorluk)) ? '&zorluk='.$zorluk : ''; ?><?php echo ($sure > 0) ? '&sure='.$sure : ''; ?>" aria-label="Önceki">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for($i = max(1, $sayfa - 2); $i <= min($sayfa + 2, $toplam_sayfa); $i++): ?>
                    <li class="page-item <?php echo ($i == $sayfa) ? 'active' : ''; ?>">
                        <a class="page-link" href="tarifler.php?sayfa=<?php echo $i; ?><?php echo (!empty($aranan)) ? '&q='.$aranan : ''; ?><?php echo (!empty($zorluk)) ? '&zorluk='.$zorluk : ''; ?><?php echo ($sure > 0) ? '&sure='.$sure : ''; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if($sayfa < $toplam_sayfa): ?>
                    <li class="page-item">
                        <a class="page-link" href="tarifler.php?sayfa=<?php echo $sayfa + 1; ?><?php echo (!empty($aranan)) ? '&q='.$aranan : ''; ?><?php echo (!empty($zorluk)) ? '&zorluk='.$zorluk : ''; ?><?php echo ($sure > 0) ? '&sure='.$sure : ''; ?>" aria-label="Sonraki">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>