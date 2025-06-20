<?php
// Tarif ID'sini kontrol et
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

include 'includes/header.php';

// Tarifi getir
$tarif = tarifi_getir($id);

// Tarif bulunamadıysa ana sayfaya yönlendir
if(!$tarif) {
    header('Location: index.php');
    exit;
}

// Sayfa başlığını ayarla
$sayfa_basligi = $tarif['tarif_adi'];

// Görüntülenme sayısını artır
$goruntu_guncelle = $db->prepare("UPDATE tarifler SET goruntulenme = goruntulenme + 1 WHERE id = :id");
$goruntu_guncelle->execute(['id' => $id]);

// Tarif kategorisini getir
$kategori_sorgu = $db->prepare("SELECT * FROM kategoriler WHERE id = :id");
$kategori_sorgu->execute(['id' => $tarif['kategori_id']]);
$kategori = $kategori_sorgu->fetch();

// Benzer tarifleri getir (aynı kategoriden)
$benzer_sorgu = $db->prepare("SELECT * FROM tarifler WHERE kategori_id = :kategori_id AND id != :id ORDER BY RAND() LIMIT 3");
$benzer_sorgu->execute([
    'kategori_id' => $tarif['kategori_id'],
    'id' => $id
]);
$benzer_tarifler = $benzer_sorgu->fetchAll();

// Tarif yorumlarını getir
$yorum_sorgu = $db->prepare("SELECT * FROM yorumlar WHERE tarif_id = :tarif_id AND onay = 1 ORDER BY tarih DESC");
$yorum_sorgu->execute(['tarif_id' => $id]);
$yorumlar = $yorum_sorgu->fetchAll();

// Yorum ekleme işlemi
$yorum_mesaji = '';
$yorum_tur = '';

if(isset($_POST['yorum_gonder'])) {
    $ad_soyad = guvenlik($_POST['ad_soyad']);
    $email = guvenlik($_POST['email']);
    $yorum = guvenlik($_POST['yorum']);
    $puan = isset($_POST['puan']) ? (int)$_POST['puan'] : 5;
    
    if(empty($ad_soyad) || empty($email) || empty($yorum)) {
        $yorum_mesaji = 'Lütfen tüm alanları doldurun.';
        $yorum_tur = 'danger';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $yorum_mesaji = 'Lütfen geçerli bir e-posta adresi girin.';
        $yorum_tur = 'danger';
    } else {
        // Yorumu kaydet
        $yorum_ekle = $db->prepare("INSERT INTO yorumlar (tarif_id, ad_soyad, email, yorum, puan, onay) VALUES (:tarif_id, :ad_soyad, :email, :yorum, :puan, 0)");
        $yorum_ekle->execute([
            'tarif_id' => $id,
            'ad_soyad' => $ad_soyad,
            'email' => $email,
            'yorum' => $yorum,
            'puan' => $puan
        ]);
        
        if($yorum_ekle->rowCount() > 0) {
            $yorum_mesaji = 'Yorumunuz başarıyla gönderildi. Onaylandıktan sonra yayınlanacaktır.';
            $yorum_tur = 'success';
        } else {
            $yorum_mesaji = 'Yorum gönderilirken bir hata oluştu. Lütfen tekrar deneyin.';
            $yorum_tur = 'danger';
        }
    }
}
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="kategori.php?id=<?php echo $kategori['id']; ?>"><?php echo $kategori['kategori_adi']; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $tarif['tarif_adi']; ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Tarif İçeriği -->
        <div class="col-lg-8">
            <div class="card mb-4 recipe-detail-card">
                <div class="recipe-header">
                    <h1 class="recipe-title"><?php echo $tarif['tarif_adi']; ?></h1>
                    <div class="recipe-meta d-flex flex-wrap justify-content-between align-items-center">
                        <div class="recipe-info">
                            <span class="badge bg-primary me-2"><i class="fas fa-tag me-1"></i> <?php echo $kategori['kategori_adi']; ?></span>
                            <span class="badge bg-<?php 
                                if($tarif['zorluk'] == 'Kolay') echo 'success';
                                elseif($tarif['zorluk'] == 'Orta') echo 'warning text-dark';
                                else echo 'danger';
                            ?> me-2"><i class="fas fa-layer-group me-1"></i> <?php echo $tarif['zorluk']; ?></span>
                            <span class="badge bg-secondary me-2"><i class="far fa-clock me-1"></i> <?php echo $tarif['sure']; ?> dk</span>
                            <span class="badge bg-info text-dark"><i class="fas fa-utensils me-1"></i> <?php echo $tarif['porsiyon']; ?> kişilik</span>
                        </div>
                        <div class="recipe-share">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/tarif_detay.php?id=' . $id); ?>" target="_blank" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/tarif_detay.php?id=' . $id); ?>&text=<?php echo urlencode($tarif['tarif_adi']); ?>" target="_blank" class="btn btn-sm btn-outline-info me-1"><i class="fab fa-twitter"></i></a>
                            <a href="https://wa.me/?text=<?php echo urlencode($tarif['tarif_adi'] . ' - ' . SITE_URL . '/tarif_detay.php?id=' . $id); ?>" target="_blank" class="btn btn-sm btn-outline-success me-1"><i class="fab fa-whatsapp"></i></a>
                            <a href="mailto:?subject=<?php echo urlencode($tarif['tarif_adi']); ?>&body=<?php echo urlencode('Bu tarifi mutlaka denemelisin: ' . SITE_URL . '/tarif_detay.php?id=' . $id); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="recipe-image">
                    <img src="assets/img/tarifler/<?php echo $tarif['resim']; ?>" class="img-fluid" alt="<?php echo $tarif['tarif_adi']; ?>">
                </div>
                
                <div class="card-body">
                    <div class="recipe-description mb-4">
                        <h3 class="mb-3">Tarif Hakkında</h3>
                        <p><?php echo nl2br($tarif['aciklama']); ?></p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-5">
                            <div class="recipe-ingredients mb-4">
                                <h3 class="mb-3">Malzemeler</h3>
                                <ul class="list-group list-group-flush">
                                    <?php 
                                    $malzemeler = explode("\n", $tarif['malzemeler']);
                                    foreach($malzemeler as $malzeme):
                                        if(trim($malzeme) != ''):
                                    ?>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i> <?php echo trim($malzeme); ?>
                                    </li>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-7">
                            <div class="recipe-steps mb-4">
                                <h3 class="mb-3">Hazırlanışı</h3>
                                <ol class="list-group list-group-numbered">
                                    <?php 
                                    $yapilis_adimlar = explode("\n", $tarif['yapilis']);
                                    foreach($yapilis_adimlar as $adim):
                                        if(trim($adim) != ''):
                                    ?>
                                    <li class="list-group-item"><?php echo trim($adim); ?></li>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(isset($tarif['video_url']) && !empty($tarif['video_url'])): ?>
                    <div class="recipe-video mb-4">
                        <h3 class="mb-3">Video</h3>
                        <div class="ratio ratio-16x9">
                            <iframe src="<?php echo str_replace('watch?v=', 'embed/', $tarif['video_url']); ?>" title="<?php echo $tarif['tarif_adi']; ?>" allowfullscreen></iframe>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Yazar Bilgisi -->
                    <?php if(isset($tarif['yazar']) && !empty($tarif['yazar'])): ?>
                    <div class="recipe-author mt-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="assets/img/avatars/default.png" class="rounded-circle" width="60" height="60" alt="Yazar">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1"><?php echo $tarif['yazar']; ?></h5>
                                <p class="mb-0 text-muted small">Bu tarif <?php echo tarih_formati($tarif['eklenme_tarihi']); ?> tarihinde eklendi.
                                <?php if($tarif['guncelleme_tarihi']): ?>
                                    Son güncelleme: <?php echo tarih_formati($tarif['guncelleme_tarihi']); ?>
                                <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Yorumlar -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Yorumlar (<?php echo count($yorumlar); ?>)</h3>
                </div>
                <div class="card-body">
                    <?php if(count($yorumlar) > 0): ?>
                        <?php foreach($yorumlar as $yorum): ?>
                        <div class="comment mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="comment-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <?php echo strtoupper(substr($yorum['ad_soyad'], 0, 1)); ?>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="comment-meta d-flex justify-content-between align-items-center">
                                        <h5 class="comment-author mb-0"><?php echo $yorum['ad_soyad']; ?></h5>
                                        <small class="text-muted"><?php echo tarih_formati($yorum['tarih']); ?></small>
                                    </div>
                                    <div class="comment-rating my-1">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo ($i <= $yorum['puan']) ? 'text-warning' : 'text-muted'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="comment-text mb-0"><?php echo nl2br($yorum['yorum']); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            Henüz yorum yapılmamış. İlk yorumu siz yapın!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Yorum Formu -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Yorum Yap</h3>
                </div>
                <div class="card-body">
                    <?php if(!empty($yorum_mesaji)): ?>
                    <div class="alert alert-<?php echo $yorum_tur; ?> alert-dismissible fade show" role="alert">
                        <?php echo $yorum_mesaji; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form action="" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="ad_soyad" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-12">
                                <label for="yorum" class="form-label">Yorumunuz <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="yorum" name="yorum" rows="4" required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Puan</label>
                                <div class="rating">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="puan" id="puan5" value="5" checked>
                                        <label class="form-check-label" for="puan5">5</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="puan" id="puan4" value="4">
                                        <label class="form-check-label" for="puan4">4</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="puan" id="puan3" value="3">
                                        <label class="form-check-label" for="puan3">3</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="puan" id="puan2" value="2">
                                        <label class="form-check-label" for="puan2">2</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="puan" id="puan1" value="1">
                                        <label class="form-check-label" for="puan1">1</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" name="yorum_gonder" class="btn btn-primary">Yorumu Gönder</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Yan Menü -->
        <div class="col-lg-4">
            <!-- Benzer Tarifler -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Benzer Tarifler</h3>
                </div>
                <div class="card-body">
                    <?php if(count($benzer_tarifler) > 0): ?>
                        <?php foreach($benzer_tarifler as $benzer): ?>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="assets/img/tarifler/<?php echo $benzer['resim']; ?>" class="rounded" width="70" height="70" alt="<?php echo $benzer['tarif_adi']; ?>" style="object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0"><a href="tarif_detay.php?id=<?php echo $benzer['id']; ?>" class="text-decoration-none"><?php echo $benzer['tarif_adi']; ?></a></h6>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted"><i class="far fa-clock me-1"></i> <?php echo $benzer['sure']; ?> dk</small>
                                    <span class="badge bg-<?php 
                                        if($benzer['zorluk'] == 'Kolay') echo 'success';
                                        elseif($benzer['zorluk'] == 'Orta') echo 'warning text-dark';
                                        else echo 'danger';
                                    ?>"><?php echo $benzer['zorluk']; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            Benzer tarif bulunamadı.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mb-4">
    <div class="card-header bg-white">
        <h3 class="mb-0">Kategoriler</h3>
    </div>
    <div class="card-body">
        <div class="list-group">
            <?php foreach($kategoriler as $kat): ?>
            <a href="kategori.php?id=<?php echo $kat['id']; ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                
                <!-- Kategori resmi -->
                <img src="assets/img/kategoriler/<?php echo $kat['resim']; ?>" alt="<?php echo $kat['kategori_adi']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 15px;">
                
                <div class="flex-grow-1">
                    <?php echo $kat['kategori_adi']; ?>
                </div>
                
                <?php 
                    // Kategori tarif sayısını getir
                    $kat_tarif_sorgu = $db->prepare("SELECT COUNT(*) FROM tarifler WHERE kategori_id = :id");
                    $kat_tarif_sorgu->execute(['id' => $kat['id']]);
                    $tarif_sayisi = $kat_tarif_sorgu->fetchColumn();
                ?>
                <span class="badge bg-primary rounded-pill"><?php echo $tarif_sayisi; ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

            
            <!-- Günün Önerisi -->
            <?php 
                $oneri_sorgu = $db->query("SELECT * FROM tarifler ORDER BY RAND() LIMIT 1");
                $oneri = $oneri_sorgu->fetch();
                if($oneri):
            ?>
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Günün Önerisi</h3>
                </div>
                <img src="assets/img/tarifler/<?php echo $oneri['resim']; ?>" class="card-img-top" alt="<?php echo $oneri['tarif_adi']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $oneri['tarif_adi']; ?></h5>
                    <p class="card-text"><?php echo kisalt($oneri['aciklama'], 120); ?></p>
                    <a href="tarif_detay.php?id=<?php echo $oneri['id']; ?>" class="btn btn-primary">Tarifi İncele</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>