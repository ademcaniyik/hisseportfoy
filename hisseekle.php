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
        <input name="hisse-kodu" id="hisse-kodu" placeholder="Hisse Kodu" type="text" required >
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
        <input  name="alis-fiyati" id="alis-fiyati" placeholder="Alış Fiyat" type="number" required >
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
        <input name="kac-lot-aldin" id="kac-lot-aldin" placeholder="Kaç lot aldınız" type="number" required >
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
        <input name="hangi-banka" id="hangi-banka" placeholder="Hangi bankadan" type="text" required>
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
            Hisseyi ekle
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
    $kac_lot_aldin = isset($_POST['kac-lot-aldin']) ? floatval($_POST['kac-lot-aldin']) : '';
    $banka = isset($_POST['hangi-banka']) ? $_POST['hangi-banka'] : '';

    if (!is_numeric($alis_fiyati)) {
        echo "Alış fiyatı sayısal bir değer olmalıdır.";
        exit;
    }

    if (!is_numeric($kac_lot_aldin)) {
        echo "Kaç lot aldığın sayısal bir değer olmalıdır.";
        exit;
    }

    // Maliyeti hesapla
    $maliyet = $alis_fiyati * $kac_lot_aldin;

    // SQL sorgusunu parametre kullanarak oluştur
    $sql = "INSERT INTO portfolyo (kullanici_id, hisse_kodu, alis_fiyat, lot, maliyet, banka) VALUES (:kullanici_id, :hisse_kodu, :alis_fiyati, :kac_lot_aldin, :maliyet, :banka)";

    // Sorguyu hazırla
    $stmt = $db->prepare($sql);

    // Parametre değerlerini ata
    $stmt->bindParam(':kullanici_id', $kullanici_id);
    $stmt->bindParam(':hisse_kodu', $hisse_kodu);
    $stmt->bindParam(':alis_fiyati', $alis_fiyati);
    $stmt->bindParam(':kac_lot_aldin', $kac_lot_aldin);
    $stmt->bindParam(':maliyet', $maliyet);
    $stmt->bindParam(':banka', $banka);

    // Sorguyu çalıştır
    $sonuc = $stmt->execute();

    // Ekleme işlemi başarılı mı kontrol et
    if ($sonuc) {
        echo "Hisse başarıyla eklendi.";
    } else {
        echo "Hisse eklenirken bir hata oluştu.";
    }
}
?>

</body>
</html>