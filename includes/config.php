<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'tareblezzonline_iamazra03');
define('DB_PASS', 'Azra@2025!'); 
define('DB_NAME', 'tareblezzonline_yemek_tarifleri');

define('SITE_URL', 'https://tariflezzetleri.online');
define('SITE_TITLE', 'Tarif Lezzetleri');
define('ADMIN_EMAIL', 'admin@tariflezzetleri.com');

try {
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Fonksiyonlar

// XSS saldırılarına karşı girdi temizleme fonksiyonu
function guvenlik($data) {
    if(is_array($data)) {
        foreach($data as $key => $value) {
            $data[$key] = guvenlik($value);
        }
        return $data;
    } else {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}

// SEO URL oluşturma fonksiyonu
function seo_url($str) {
    $str = mb_strtolower($str, 'UTF-8');
    $str = str_replace(
        ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'],
        ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'],
        $str
    );
    $str = preg_replace('/[^a-z0-9]/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    $str = trim($str, '-');
    return $str;
}

// Tarih formatı düzenleme fonksiyonu
function tarih_formati($tarih) {
    return date('d.m.Y', strtotime($tarih));
}

// Yazı kısaltma fonksiyonu
function kisalt($icerik, $uzunluk = 100) {
    if(strlen($icerik) > $uzunluk) {
        $icerik = substr($icerik, 0, $uzunluk) . '...';
    }
    return $icerik;
}

// URL yönlendirme fonksiyonu
function yonlendir($url, $zaman = 0) {
    if($zaman == 0) {
        header("Location: $url");
    } else {
        header("Refresh: $zaman; url=$url");
    }
    exit;
}

// Kategorileri getirme fonksiyonu
function kategorileri_getir() {
    global $db;
    $sorgu = $db->query("SELECT * FROM kategoriler ORDER BY kategori_adi ASC");
    return $sorgu->fetchAll();
}

// Tarif getirme fonksiyonu
function tarifi_getir($id) {
    global $db;
    $sorgu = $db->prepare("SELECT t.*, k.kategori_adi FROM tarifler t 
                          LEFT JOIN kategoriler k ON t.kategori_id = k.id 
                          WHERE t.id = :id");
    $sorgu->execute(['id' => $id]);
    return $sorgu->fetch();
}

// Yönetici oturum kontrolü
function admin_kontrol() {
    if(!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_kullanici'])) {
        yonlendir("giris.php");
    }
}
