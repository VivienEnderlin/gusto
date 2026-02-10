<?php
session_start();
require_once './bdFonctions.class.php';

$manageBd = new bdFonctions();

// On vérifie si l'ID du menu est présent (souvent dans l'URL)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id = $_GET['id'];

    $tb = 'menu';
    
    // ATTENTION SÉCURITÉ : Utilisez des requêtes préparées dans votre classe bdFonctions.
    // Pour cet exemple, je garde votre syntaxe mais rappelez-vous du risque SQL.
    $cond = "WHERE idmenu = '" . $id . "' "; 

    $data = $manageBd->getAll($tb, $cond)->fetch(PDO::FETCH_ASSOC);

    // --- NOUVEAU : Récupérer la quantité si elle est envoyée via POST ---
    $quantite_recue = null;
    if (isset($_POST['quantite'])) {
        $quantite_recue = intval($_POST['quantite']);
    }
    // ------------------------------------------------------------------
    
    // Ajoutez la quantité à l'array $data
    $data['quantite_choisie'] = $quantite_recue;
    $data['net_à_payer'] = $quantite_recue*$data['prix'];
    
    // Retourne les données du menu PLUS la quantité choisie (si présente)
    die(json_encode(array_merge(array('backData' => true), $data)));
}


?>