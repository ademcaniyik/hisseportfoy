<?php
$db = new PDO("mysql:host=127.0.0.1;port=3306;dbname=ade69aniyikxyz_hisseportfoy;charset=utf8", "ade69aniyikxyz_ademcaniyik", "1594872630Ai!");
$sorgu = $db->prepare("SELECT ad, soyad FROM kullanici_info WHERE kullanici_id='1'");
$sorgu->execute();

$sonuc = $sorgu->fetch();

if ($sonuc) { // Veri varsa
    echo "Ad: " . $sonuc['ad'] . " Soyad: " . $sonuc['soyad'];
} else { // Veri yoksa
    echo "Kullanıcı id 1 için kayıt bulunamadı.";
}

