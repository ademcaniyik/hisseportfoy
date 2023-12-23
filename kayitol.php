<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="tablo/css/login.css">
</head>
<body>

<form autocomplete="off" class="form" method="post" action="">
    <div class="control">
        <h1>
            Kayıt olun
        </h1>
    </div>
    <div class="control block-cube block-input">
        <input name="ad" placeholder="Adınız" type="text" >
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
        <input name="soyad" placeholder="Soyadınız" type="text" >
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
        <input name="username" placeholder="Kullanıcı Adı Giriniz" type="text" >
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
        <input name="password" placeholder="Şifreniz" type="password" >
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
        <input name="password2" placeholder="Şifreniz tekrardan" type="password" >
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

    <button class="btn block-cube block-cube-hover" type="submit" name="register">
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
            Kayıt Ol
        </div>
    </button>
</form>

<?php
session_start(); // Oturum başlat

// Veritabanı bağlantı bilgileri
$dbHost = "localhost";
$dbName = "hisseportfoy";
$dbCharset = "utf8mb4";
$dbUsername = "root";
$dbPassword = "";

// Bağlantı oluştur
try {
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset", $dbUsername, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı başarısız: " . $e->getMessage();
    die();
}

// Kayıt işlemi
if (isset($_POST['register'])) {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $kullaniciAdi = $_POST['username'];
    $sifre = $_POST['password'];
    $sifreTekrar = $_POST['password2'];

    // Boş alan kontrolü
    if (empty($ad) || empty($soyad) || empty($kullaniciAdi) || empty($sifre) || empty($sifreTekrar)) {
        echo "Lütfen tüm alanları doldurun.";
    } else {
        // Gerekli kontrolleri yapabilirsiniz, örneğin şifrelerin eşleşip eşleşmediği
        if ($sifre === $sifreTekrar) {
            // Kullanıcı adının veritabanında olup olmadığını kontrol et
            $sorgu = $db->prepare("SELECT COUNT(*) AS kullaniciSayisi FROM kullanici_info WHERE kullaniciAdi = :kullaniciAdi");
            $sorgu->bindParam(':kullaniciAdi', $kullaniciAdi);
            $sorgu->execute();

            $kullaniciSayisi = $sorgu->fetchColumn();

            if ($kullaniciSayisi > 0) {
                // Kullanıcı adı zaten var
                echo "Bu kullanıcı adı zaten kullanılıyor. Lütfen farklı bir kullanıcı adı seçin.";
            } else {
                // Kullanıcı adı kullanılabilir
                // Şifreleri şifrelemeyin

                try {
                    // Kayıt işlemini gerçekleştir
                    $sorgu = $db->prepare("INSERT INTO kullanici_info (ad, soyad, kullaniciAdi, sifre) VALUES (:ad, :soyad, :kullaniciAdi, :sifre)");
                    $sorgu->bindParam(':ad', $ad);
                    $sorgu->bindParam(':soyad', $soyad);
                    $sorgu->bindParam(':kullaniciAdi', $kullaniciAdi);
                    $sorgu->bindParam(':sifre', $sifre);

                    if ($sorgu->execute()) {
                        header("refresh:4;url=index.php");
                        echo "Kayıt başarıyla oluşturuldu, Giriş sayfasına yönlendiriliyorsunuz.";
                    } else {
                        echo "Kayıt oluşturulurken bir hata oluştu.";
                    }
                } catch (PDOException $ex) {
                    echo "PDO Hatası: " . $ex->getMessage();
                }
            }
        } else {
            echo "Şifreler eşleşmiyor.";

        }
    }
}


?>
</body>
</html>
