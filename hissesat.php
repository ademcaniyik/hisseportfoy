<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="tablo/css/login.css">
</head>
<body>
<form class="form" method="post" action="">
    <div class="control">
    </div>
    <div class="control block-cube block-input">
        <input name="hisse-kodu" id="hisse-kodu" placeholder="Hisse Kodu" type="text" required onchange="getPortfolioHisseKoduOptions()">
        <div class="bg-top">
            <div class="bg-inner"></div>
        </div>
        <div class="bg-right">
            <div class="bg-inner"></div>
        </div>
        <div class="bg">
            <div class="bg-inner"></div>
        </div>
    </div>
    <div class="control block-cube block-input">
        <input name="kac-lot-aldin" id="kac-lot-aldin" placeholder="Kaç lot satacaksınız" type="number" required >
        <div class="bg-top">
            <div class="bg-inner"></div>
        </div>
        <div class="bg-right">
            <div class="bg-inner"></div>
        </div>
        <div class="bg">
            <div class="bg-inner"></div>
        </div>
    </div>
    <button class="btn block-cube block-cube-hover" type="submit" value="kaydet">
        <div class="bg-top">
            <div class="bg-inner"></div>
        </div>
        <div class="bg-right">
            <div class="bg-inner"></div>
        </div>
        <div class="bg">
            <div class="bg-inner"></div>
        </div>
        <div class="text">
            Hisseyi Sat
        </div>
    </button>
    <button class="btn block-cube block-cube-hover" type="button" value="kaydet" onclick="redirectToPortfolio()">
        <div class="bg-top">
            <div class="bg-inner"></div>
        </div>
        <div class="bg-right">
            <div class="bg-inner"></div>
        </div>
        <div class="bg">
            <div class="bg-inner"></div>
        </div>
        <div class="text">
            Portföye Git
        </div>
    </button>
    <script>
        function redirectToPortfolio() {
            // JavaScript'te window.location.href kullanarak yönlendirme yapabilirsiniz.
            window.location.href = "tablo/tablo.php";
        }
    </script>
</form>
<?php
session_start(); // Oturumu başlat
$db = new PDO("mysql:host=127.0.0.1;port=3306;dbname=hisseportfoy;charset=utf8", "root", "");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kullanıcının oturum açtığını kontrol et
    if (!isset($_SESSION['kullanici_id'])) {
        echo "Oturum açılmış değil.";
        exit;
    }
    $kullanici_id = $_SESSION['kullanici_id'];
    $hisse_kodu = isset($_POST['hisse-kodu']) ? $_POST['hisse-kodu'] : '';
    $alis_fiyati = isset($_POST['alis-fiyati']) ? floatval($_POST['alis-fiyati']) : '';
    $kac_lot_sattin = isset($_POST['kac-lot-aldin']) ? floatval($_POST['kac-lot-aldin']) : '';
    if (!is_numeric($kac_lot_sattin)) {
        echo "Kaç lot sattığın sayısal bir değer olmalıdır.";
        exit;
    }
    // SQL sorgusunu parametre kullanarak oluştur
    $sql = "SELECT lot FROM portfolyo WHERE kullanici_id = :kullanici_id AND hisse_kodu = :hisse_kodu";
    // Sorguyu hazırla
    $stmt = $db->prepare($sql);
    // Parametre değerlerini ata
    $stmt->bindParam(':kullanici_id', $kullanici_id);
    $stmt->bindParam(':hisse_kodu', $hisse_kodu);
    // Sorguyu çalıştır
    $stmt->execute();
    // Sorgu sonucunu bir değişkene ata
    $sonuc = $stmt->fetch();
    // Sorgu sonucu boşsa, hata mesajı ver
    if (!$sonuc) {
        echo "Hisse portföyünde bulunamadı.";
        exit;
    }
    // Mevcut lot sayısını hesapla
    $mevcut_lot_sayisi = $sonuc['lot'];
    // Satılacak lot sayısını hesapla
    $satilcak_lot_sayisi = $kac_lot_sattin;
    // Yeni lot sayısını hesapla
    $yeni_lot_sayisi = $mevcut_lot_sayisi - $satilcak_lot_sayisi;
    // SQL sorgusunu parametre kullanarak oluştur
    $sql = "UPDATE portfolyo SET lot = :yeni_lot_sayisi WHERE kullanici_id = :kullanici_id AND hisse_kodu = :hisse_kodu";
    // Sorguyu hazırla
    $stmt = $db->prepare($sql);
    // Parametre değerlerini ata
    $stmt->bindParam(':yeni_lot_sayisi', $yeni_lot_sayisi);
    $stmt->bindParam(':kullanici_id', $kullanici_id);
    $stmt->bindParam(':hisse_kodu', $hisse_kodu);
    $sonuc = $stmt->execute();
    if ($yeni_lot_sayisi == 0) {
        $sql = "DELETE FROM portfolyo WHERE kullanici_id = :kullanici_id AND hisse_kodu = :hisse_kodu";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':kullanici_id', $kullanici_id);
        $stmt->bindParam(':hisse_kodu', $hisse_kodu);
        $stmt->execute();
    }
    // Güncelleme işlemi başarılı mı kontrol et
    if ($sonuc) {
        echo "Hisse başarıyla satıldı.";
    } else {
        echo "Hisse satılırken bir hata oluştu.";
    }
}
?>
</body>
</html>