<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hisse Senedi Portföyü</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<table>
    <thead>
    <tr>
        <th>Banka</th>
        <th>Hisse Kodu</th>
        <th>Lot Sayısı</th>
        <th>Alış Fiyatı</th>
        <th>Maliyet</th>
        <th>Şuanki Fiyat</th>
        <th>Anlık Kar/Zarar</th>
    </tr>
    </thead>
    <tbody>
    <?php
    session_start();

    // Oturum kontrolü
    if (!isset($_SESSION['kullanici_id'])) {
        // Kullanıcı giriş yapmamışsa, giriş sayfasına yönlendir
        header("Location: ../index.php");
        exit();
    }
    $kullanici_id = $_SESSION['kullanici_id'];
    $db = new PDO("mysql:host=127.0.0.1;port=3306;dbname=hisseportfoy;user=root;password=");
    $sorgu = $db->prepare("SELECT * FROM portfolyo WHERE kullanici_id = :kullanici_id");
    $sorgu->execute(['kullanici_id' => $kullanici_id]);
    $portfoyler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
    foreach ($portfoyler as $portfoy) {
        $hisse_kodu = isset($portfoy["hisse_kodu"]) ? $portfoy["hisse_kodu"] : "";
        $lot_sayisi = isset($portfoy["lot"]) ? $portfoy["lot"] : "";
        $alis_fiyati = isset($portfoy["alis_fiyat"]) ? $portfoy["alis_fiyat"] : "";
        $maliyet = isset($portfoy["maliyet"]) ? $portfoy["maliyet"] : "";
        $banka = isset($portfoy["banka"]) ? $portfoy["banka"] : "";
        $portfolyo_id = isset($portfoy["portfolyo_id"]) ? $portfoy["portfolyo_id"] : "";
        echo "<tr>";
        echo "<td>".$banka."</td>";
        echo "<td>" . $hisse_kodu . "</td>";
        echo "<td>" . $lot_sayisi . "</td>";
        echo "<td>" . $alis_fiyati . "</td>";
        echo "<td>" . $maliyet . "</td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";
    }
    ?>
    <div><h1>Mevcut Hisseler</h1></div>
    <a href="../hisseekle.php"><button>Hisse Ekle</button></a>
    <a href="../hissesat.php"><button>Hisse Sat</button></a>
    </tbody>
</table>
</body>
</html>
