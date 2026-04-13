<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrintController {

    public function imprimerFacture($id_Table, $commande, $montant_total, $devise) {

        try {
            $connector = new WindowsPrintConnector("POS-80"); // Nom imprimante
            $printer = new Printer($connector);

            // ===== EN-TÊTE =====
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->setEmphasis(true);
            $printer->text("FACTURE\n\n");

            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            $printer->text("Table : " . $id_Table . "\n");
            $printer->text("--------------------------------\n");
            $printer->text("Date : " . date("d/m/Y H:i") . "\n");
            $printer->text("--------------------------------\n");

            // ===== ARTICLES =====
            foreach ($commande as $item) {
                $printer->text(sprintf(
                    "%-16s %2dx %6s\n",
                    substr($item['libelle'], 0, 16),
                    $item['quantite'],
                    $item['total'] . " " . $devise
                ));
            }

            // ===== TOTAL =====
            $printer->text("--------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setEmphasis(true);
            $printer->text("TOTAL : " . $montant_total . " " . $devise . "\n\n");

            // ===== FIN =====
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Thank you for visiting\n\n");

            $printer->cut();
            $printer->close();

            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}