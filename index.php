<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="tablo/css/login.css">
</head>
<body>
<form autocomplete="off" class="form" method="post" action="">
    <div class="control">
        <h1>
            Oturum aç
        </h1>
    </div>
    <div class="control block-cube block-input">
        <input name="username" placeholder="Kullanıcı Adınız" type="text" >
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
    <button class="btn block-cube block-cube-hover" type="submit">
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
            Giriş Yap
        </div>
    </button>

    <button class="btn block-cube block-cube-hover" type="button" onclick="redirectToRegistration()">
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

    <script>
        function redirectToRegistration() {
            // Yönlendirme işlemi için window.location.href kullanılır
            window.location.href = "kayitol.php"; // Yönlendirilecek sayfanın URL'sini belirtin
        }
    </script>

</form>
</body>
</html>
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

// Form verilerini kontrol et
function kontrol_formu() {
    global $db;  // Global değişkeni kullanabilmek için ekledik

    // Kullanıcı adı ve şifre değişkenlerini tanımla
    $kullaniciAdi = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $sifre = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (empty($kullaniciAdi) || empty($sifre)) {
        echo "<p class='error'>Kullanıcı adı ve şifre alanları boş olamaz.</p>";
        return false;
    }

    // Veritabanı sorgusu
    $sorgu = $db->prepare("SELECT * FROM kullanici_info WHERE kullaniciAdi = :kullanici_adi AND sifre = :sifre");
    $sorgu->execute([
        'kullanici_adi' => $kullaniciAdi,
        'sifre' => $sifre
    ]);

    $sonuc = $sorgu->fetch();

    if ($sonuc) {
        // Giriş başarılı, oturum başlat
        $_SESSION['kullanici_adi'] = $kullaniciAdi;
        $_SESSION['kullanici_id'] = $sonuc['kullanici_id'];
        header("Location: tablo/tablo.php");
        exit();
    } else {
        // Giriş başarısız, hata mesajı göster
        echo "<p class='error'>Kullanıcı adı veya şifre yanlış.</p>";
        return false;
    }
}

// Giriş kontrolü
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (kontrol_formu()) {
        // Giriş başarılı, index.php yönlendir
        exit();
    } else {
        // Giriş başarısız, hata mesajı göster
        echo "<p class='error'>Kullanıcı adı veya şifre yanlış.</p>";
    }
}

// Bağlantıyı kapat
$db = null;
?>
