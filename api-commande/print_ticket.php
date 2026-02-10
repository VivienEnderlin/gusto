<?php
session_start();

require_once './bdFonctions.class.php';
$manageBd = new bdFonctions();

// require __DIR__ . '/escpos-php-development/src/Mike42/Escpos/Printer.php';
// require __DIR__ . '/escpos-php-development/src/Mike42/Escpos/PrintConnectors/WindowsPrintConnector.php';


// use Mike42\Escpos\Printer;
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

// Récupération des données
$idtableRaw  = $_POST['idtable'];
$idcommandeRaw  = $_POST['idcommande'];
$idtable = (int) preg_replace('/[^0-9]/', '', $idtableRaw);
$commande = json_decode($_POST['commande'], true);
$montantFinal = $_POST['montantFinal'];

// $connector = new WindowsPrintConnector("POS-80"); // NOM EXACT imprimante
// $printer = new Printer($connector);

// // ===== EN-TÊTE =====
// $printer->setJustification(Printer::JUSTIFY_CENTER);
// $printer->setTextSize(2, 2);
// $printer->setEmphasis(true);
// $printer->text("FACTURE\n\n");

// $printer->setTextSize(1, 1);
// $printer->setEmphasis(false);
// $printer->text("Table : " . $idtable . "\n");
// $printer->text("--------------------------------\n");
// $printer->text("Date : " . date("d/m/Y H:i") . "\n");
// $printer->text("--------------------------------\n");

// // ===== ARTICLES =====
// foreach ($commande as $item) {
//     $printer->text(sprintf(
//         "%-16s %2dx %6s\n",
//         substr($item['libelle'], 0, 16, 'UTF-8'),
//         $item['quantite'],
//         $item['total'] . "F"
//     ));
// }

// // ===== TOTAL =====
// $printer->text("--------------------------------\n");
// $printer->setJustification(Printer::JUSTIFY_RIGHT);
// $printer->setEmphasis(true);
// $printer->text("TOTAL : " . $montantFinal . " FCFA\n\n");

// // ===== FIN =====
// $printer->setJustification(Printer::JUSTIFY_CENTER);
// $printer->text("Merci pour votre visite\n\n");

// $printer->cut();
// $printer->close();

//mise à jour


$tb = 'commande';

/* Récupérer la DERNIÈRE commande NON achevée de cette table */
$lastCmd = $manageBd->getAll($tb,"WHERE idcommande = $idcommandeRaw")->fetch();

/* Mettre à jour le statut */
if ($lastCmd) {
    $col  = ['statu'];
    $val  = ['Achevé'];
    $cond = "WHERE idcommande = " . $lastCmd['idcommande'];

    $manageBd->set($tb, $col, $val, $cond);
    $data=$manageBd->getAll($tb,$cond)->fetch();

    die(json_encode([1=>true,'id' => $data['idcommande']]));
}


?>
