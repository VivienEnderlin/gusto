<?php
session_start();

require_once './bdFonctions.class.php';
$manageBd = new bdFonctions();

require_once './phpqrcode/qrlib.php';


// URL à encoder
$url = "http://192.168.100.238/gusto/web/index.php?id=" . urlencode($_SESSION['idunique']);


// Nom du fichier téléchargé
$filename = "mon_qrcode.png";

// Headers pour forcer le téléchargement
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

// Génération du QR Code
QRcode::png($url, null, QR_ECLEVEL_H, 8);
exit;

?>