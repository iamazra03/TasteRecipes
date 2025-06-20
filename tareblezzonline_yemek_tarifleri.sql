-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 20 Haz 2025, 13:31:54
-- Sunucu sürümü: 10.11.13-MariaDB
-- PHP Sürümü: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `tareblezzonline_yemek_tarifleri`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `sifre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`id`, `kullanici_adi`, `sifre`) VALUES
(1, 'azracengiz', '$2y$10$Tv0vVt1KmESBpqMscgylMO3tuYgNkjwjYbweUu8a9lYAEyx2TWZhu');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategoriler`
--

CREATE TABLE `kategoriler` (
  `id` int(11) NOT NULL,
  `kategori_adi` varchar(100) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `resim` varchar(255) DEFAULT NULL,
  `olusturma_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`id`, `kategori_adi`, `aciklama`, `resim`, `olusturma_tarihi`) VALUES
(1, 'Çorbalar', 'Her damak tadına uygun çorba tarifleri', 'corbalar.jpg', '2025-05-19 15:00:00'),
(2, 'Ana Yemekler', 'Doyurucu ana yemek tarifleri', 'ana_yemekler.jpg', '2025-05-19 15:01:00'),
(3, 'Tatlılar', 'Ağzınızı tatlandıracak tatlı tarifleri', 'tatlilar.jpg', '2025-05-19 15:02:00'),
(4, 'Salatalar', 'Sağlıklı ve lezzetli salata tarifleri', 'salatalar.jpg', '2025-05-19 15:03:00'),
(5, 'İçecekler', 'Serinleten içecek tarifleri', 'icecekler.jpg', '2025-05-19 15:04:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tarifler`
--

CREATE TABLE `tarifler` (
  `id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `tarif_adi` varchar(255) NOT NULL,
  `aciklama` text NOT NULL,
  `malzemeler` text NOT NULL,
  `yapilis` text NOT NULL,
  `sure` int(11) NOT NULL COMMENT 'Dakika cinsinden',
  `porsiyon` int(11) NOT NULL,
  `zorluk` enum('Kolay','Orta','Zor') NOT NULL DEFAULT 'Orta',
  `resim` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `eklenme_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `guncelleme_tarihi` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `yazar` varchar(100) DEFAULT NULL,
  `one_cikan` tinyint(1) NOT NULL DEFAULT 0,
  `goruntulenme` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `tarifler`
--

INSERT INTO `tarifler` (`id`, `kategori_id`, `tarif_adi`, `aciklama`, `malzemeler`, `yapilis`, `sure`, `porsiyon`, `zorluk`, `resim`, `video_url`, `eklenme_tarihi`, `guncelleme_tarihi`, `yazar`, `one_cikan`, `goruntulenme`) VALUES
(1, 1, 'Mercimek Çorbası', 'Klasik Türk mutfağının vazgeçilmezi, besleyici mercimek çorbası.', '2 su bardağı kırmızı mercimek\r\n1 adet soğan\r\n1 adet havuç\r\n1 adet patates\r\n1 yemek kaşığı biber salçası\r\n1 tatlı kaşığı tuz\r\nKarabiber\r\nPul biber\r\n2 litre su\r\n5 yemek kaşığı sıvı yağ\r\nSıcak servis için üzerine:\r\nTereyağı\r\nNane\r\nPul biber', '1. Mercimekleri güzelce yıkayın.\r\n2. Soğan, havuç ve patatesi küçük küçük doğrayın.\r\n3. Tencereye sıvı yağı koyun ve soğanları pembeleşene kadar kavurun.\r\n4. Havuç ve patatesi de ekleyip 5 dakika kavurun.\r\n5. Salçayı ekleyip kokusu çıkana kadar kavurun.\r\n6. Mercimek ve suyu ekleyip karıştırın.\r\n7. Tuz ve karabiberi ekleyin.\r\n8. Mercimekler ve sebzeler yumuşayana kadar yaklaşık 30-40 dakika pişirin.\r\n9. Çorbayı blenderdan geçirin.\r\n10. Servis ederken üzerine kızdırılmış tereyağında nane ve pul biber gezdirin.', 45, 6, 'Kolay', 'mercimek_corbasi.jpg', 'https://www.youtube.com/watch?v=MV5K23Auxlc', '2025-05-19 15:10:00', '2025-06-18 17:07:53', 'Site Yöneticisi', 1, 157),
(2, 2, 'Karnıyarık', 'Türk mutfağının klasiklerinden, patlıcan severlerin favorisi.', '5 adet patlıcan\r\n250 gr kıyma\r\n2 adet soğan\r\n2 adet domates\r\n4 adet sivri biber\r\n3 diş sarımsak\r\n2 yemek kaşığı domates salçası\r\nTuz, karabiber, kırmızı toz biber\r\nSıvı yağ\r\nMaydanoz', '1. Patlıcanları alacalı soyup, tuzlu suda bekletin.\r\n2. Soğan ve biberleri ince ince doğrayın.\r\n3. Kıymayı kavurup soğan ve biberleri ekleyin.\r\n4. Domates, salça ve baharatları ekleyip 10 dakika pişirin.\r\n5. Patlıcanları kızartıp, ortalarını yarın.\r\n6. İçlerine harçtan doldurup, üzerine domates dizin.\r\n7. 180 derece fırında 25-30 dakika pişirin.', 60, 5, 'Orta', 'karniyarik.jpg', 'https://www.youtube.com/watch?v=brvuUWDqXw8', '2025-05-19 15:15:00', '2025-06-18 17:07:54', 'Site Yöneticisi', 1, 151),
(3, 3, 'Revani', 'Şerbetli tatlıların en hafif ve lezzetlilerinden biri.', '3 adet yumurta\r\n1 su bardağı şeker\r\n1 su bardağı yoğurt\r\n1 su bardağı irmik\r\n1 su bardağı un\r\n1 paket kabartma tozu\r\n1 paket vanilya\r\nYarım çay bardağı sıvı yağ\r\n\r\nŞerbeti için:\r\n3 su bardağı su\r\n3 su bardağı şeker\r\nYarım limon suyu', '1. Yumurta ve şekeri çırpın.\r\n2. Yoğurt ve sıvı yağı ekleyip karıştırın.\r\n3. Kuru malzemeleri eleyerek ekleyin.\r\n4. Yağlanmış tepsiye dökün.\r\n5. 180 derece fırında 30 dakika pişirin.\r\n6. Şerbeti kaynatıp soğutun.\r\n7. Ilık tatlının üzerine soğuk şerbeti dökün.', 45, 10, 'Orta', 'revani.jpg', 'https://www.youtube.com/watch?v=YyUjZojK0ew', '2025-05-19 15:20:00', '2025-06-18 17:07:56', 'Site Yöneticisi', 0, 97),
(4, 4, 'Çoban Salatası', 'Yemeklerin yanında ferahlatıcı klasik Türk salatası.', '3 adet domates\r\n2 adet salatalık\r\n1 adet kuru soğan\r\n1 adet sivri biber\r\nYarım demet maydanoz\r\n3 yemek kaşığı zeytinyağı\r\n1 yemek kaşığı limon suyu veya sirke\r\nTuz', '1. Tüm sebzeleri küp küp doğrayın.\r\n2. Maydanozu ince ince kıyın.\r\n3. Zeytinyağı, limon suyu ve tuzu ekleyip karıştırın.\r\n4. Servis tabağına alıp üzerine sumak serpin.', 15, 4, 'Kolay', 'coban_salatasi.jpeg', 'https://www.youtube.com/watch?v=Q2DMNt1KXxg', '2025-05-19 15:25:00', '2025-06-18 17:07:55', 'Site Yöneticisi', 0, 83),
(5, 3, 'Profiterol', 'Çikolata soslu, enfes lezzeti ile vazgeçilmez bir tatlı.', 'Hamuru için:\r\n1 su bardağı su\r\n125 gr tereyağı\r\n1 su bardağı un\r\n3 adet yumurta\r\n1 tutam tuz\r\n\r\nKrema için:\r\n2.5 su bardağı süt\r\n3 yemek kaşığı un\r\n2 yemek kaşığı nişasta\r\n1 su bardağı şeker\r\n1 paket vanilya\r\n\r\nSosu için:\r\n200 gr bitter çikolata\r\n1 su bardağı süt\r\n1 yemek kaşığı tereyağı', '1. Hamur için suyu, tereyağını ve tuzu kaynatın.\r\n2. Unu ekleyip karıştırarak pişirin.\r\n3. Ateşten alıp soğumaya bırakın.\r\n4. Yumurtaları teker teker ekleyip karıştırın.\r\n5. Hamuru sıkma torbasına doldurup tepsiye küçük toplar şeklinde sıkın.\r\n6. 200 derece fırında 25-30 dakika pişirin.\r\n7. Kreması için tüm malzemeleri karıştırıp koyulaşana kadar pişirin.\r\n8. Soğuyan kremayı profiterol toplarının içine doldurun.\r\n9. Çikolata sosunu hazırlayıp üzerine dökün.', 75, 8, 'Zor', 'profiterol.jpg', 'https://www.youtube.com/watch?v=Q2gxUyzWjfk', '2025-05-19 15:30:00', '2025-06-18 17:07:54', 'Site Yöneticisi', 1, 144),
(6, 3, 'Low Calorie Brownie', 'Low Calorie Brownie (Düşük kalorili brownie), belirli bir ülke mutfağına ait geleneksel bir tarif değildir. Bu tarz tarifler genellikle sağlıklı beslenme trendlerinin bir parçası olarak ortaya çıkmıştır ve modern batı mutfağının (özellikle Amerikan mutfağının) bir uyarlamasıdır.', 'Malzemeler:\r\n♡ 3 kararmış büyük muz\r\n♡ 2 adet yumurta\r\n♡ 40 gr eritilmiş bitter çikolata\r\n♡ 6 yemek kaşığı yulaf ezmesi\r\n♡ 2 yemek kaşığı kakao\r\n♡ 1 paket kabartma tozu\r\n♡ 2 yemek kaşığı tahin\r\n♡ 1 yemek kaşığı erimiş hindistan cevizi yağı\r\n♡ 1 çay kaşığı tarçın\r\n♡ Bir tutam tuz\r\n(Üzeri için): Donmuş meyve veya bitter parça çikolata\r\n', ' Nasıl Yapılır?\r\n1. Muzları blenderda püre haline getir.\r\n2. Tüm malzemeleri (tahin ve hindistan cevizi yağı dahil) ekleyip pürüzsüz olana kadar karıştır.\r\n3. Fırın kabına dök, üzerine süslemeleri ekle.\r\n4. 180°C\'de 25–30 dakika kadar pişir.\r\n5. Ilık veya soğuk servis yap.', 30, 6, 'Kolay', 'LowCalorieBrownie.jpg', NULL, '2025-06-03 16:33:55', '2025-06-18 17:07:52', 'Azra Cengiz', 1, 57),
(7, 3, 'Low Calorie Banana Bread', 'Low Calorie Banana Bread, geleneksel bir Amerikan tarifi olan muzlu ekmeğin, günümüzün sağlıklı yaşam trendlerine göre uyarlanmış bir versiyonudur. Yani kökeni Amerikan mutfağıdır, ama tarzı sağlıklı/fitness mutfağıdır.', '♡ 3 olgun muz\r\n♡ 2 yumurta\r\n♡ 1 su bardağı süt (isteğe bağlı: badem sütü/ laktozsuz süt)\r\n♡ 1.5 su bardağı rulo yulaf (yulaf unu)\r\n♡ 1 paket kabartma tozu\r\n♡ 2 yemek kaşığı tatlandırılmamış fıstık ezmesi\r\n♡ 1 yemek kaşığı hindistan cevizi yağı (eritilmiş)\r\n♡ 1.5 çay kaşığı tarçın\r\n♡ Bir tutam tuz\r\n', '1. Fırını 180°C\'ye önceden ısıt. Kalıbınızı hindistan cevizi yağı ile yağlayın ya da greaseproof kağıt ile hatlayın\r\n2. Muzları çatalla ez. Yumurtaları ekleyin ve iyice karıştırın.\r\n3. Süt, fıstık ezmesi, erimiş hindistan cevizi yağı ve tarçın ekleyin.\r\n4. Ayrı bir kasede yulaf, kabartma tozu ve tuzu karıştırın. Sıvı karışıma ekleyin.\r\n5. Doğranmış bitter çikolata ve damla çikolata ekleyin ve karıştırın.\r\n6. Hamuru kalıba dök.\r\n7. Muz dilimleri ve ekstra çikolata parçacıklarını üstüne düzenleyin.\r\n8. Önceden ısıtılmış fırında 30-40 dakika pişirin.\r\nKürdanla kontrol et.\r\n\r\nNot:\r\n1 dilim (8 dilimlik tarif için):\r\n~190 kcal,\r\n5 g protein,\r\n9 g yağ,\r\n23-25 g karbonhidrat.\r\nTatlılık tamamen muz ve çikolatadan geliyor rafine şeker yok', 30, 8, 'Kolay', 'LowCalorieBananaBread.jpg', '', '2025-06-03 16:48:55', '2025-06-19 03:31:27', 'Azra Cengiz', 0, 63);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yorumlar`
--

CREATE TABLE `yorumlar` (
  `id` int(11) NOT NULL,
  `tarif_id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `yorum` text NOT NULL,
  `puan` int(11) DEFAULT NULL,
  `onay` tinyint(1) NOT NULL DEFAULT 0,
  `tarih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `yorumlar`
--

INSERT INTO `yorumlar` (`id`, `tarif_id`, `ad_soyad`, `email`, `yorum`, `puan`, `onay`, `tarih`) VALUES
(1, 1, 'Ayşe Yılmaz', 'ayse@mail.com', 'Harika bir tarif, ailem çok beğendi. Teşekkürler!', 5, 1, '2025-05-19 15:40:00'),
(2, 1, 'Mehmet Kaya', 'mehmet@mail.com', 'Çorbayı tarife göre yaptım ve çok lezzetli oldu.', 4, 1, '2025-05-19 15:45:00'),
(3, 2, 'Zeynep Demir', 'zeynep@mail.com', 'Karnıyarık tarifini denedim, tam kıvamında oldu.', 5, 1, '2025-05-19 15:50:00'),
(4, 5, 'Ali Şahin', 'ali@mail.com', 'Profiterol ilk denemede biraz zorlandım ama sonuç mükemmel oldu.', 4, 1, '2025-05-19 15:55:00'),
(10, 2, 'Azra Cengiz', 'iamazra03@gmail.com', 'Harikaaa', 5, 1, '2025-06-03 13:09:22'),
(13, 6, 'Aden', 'adencngz15@gmail.com', 'Canım ablam  tarifini tadan bir gurme olarak  tatlını çok beğendim ????????', 5, 1, '2025-06-03 17:22:04'),
(14, 6, 'Gnccngz', 'gnccengiz82@gmail.com', 'Çok güzel olduuuu ', 5, 1, '2025-06-03 17:28:44'),
(15, 7, 'Gnccngz', 'gnccengiz82@gmail.com', 'Eline sağlık canım. Her zamanki gibi çok güzeldi ', 5, 1, '2025-06-03 17:31:19');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kategoriler`
--
ALTER TABLE `kategoriler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategori_adi` (`kategori_adi`);

--
-- Tablo için indeksler `tarifler`
--
ALTER TABLE `tarifler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Tablo için indeksler `yorumlar`
--
ALTER TABLE `yorumlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tarif_id` (`tarif_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `kategoriler`
--
ALTER TABLE `kategoriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `tarifler`
--
ALTER TABLE `tarifler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `yorumlar`
--
ALTER TABLE `yorumlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `tarifler`
--
ALTER TABLE `tarifler`
  ADD CONSTRAINT `tarifler_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `yorumlar`
--
ALTER TABLE `yorumlar`
  ADD CONSTRAINT `yorumlar_ibfk_1` FOREIGN KEY (`tarif_id`) REFERENCES `tarifler` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
