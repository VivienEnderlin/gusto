<?php

    $code = $_GET['code'] ?? null;
    $secret = "CLE_SECRETE_GUSTO"; // ✅ AJOUT OBLIGATOIRE

    if (!$code) {
        exit("Lien invalide");
    }

    // remettre base64 standard
    $code = strtr($code, '-_', '+/');

    // remettre padding '='
    $pad = strlen($code) % 4;
    if ($pad) {
        $code .= str_repeat('=', 4 - $pad);
    }

    $decoded = base64_decode($code, true);

    if (!$decoded) exit("QR code invalide");

    // Extraire id, table et signature
    $parts = explode(":", $decoded);
    if (count($parts) !== 3) exit("QR code invalide");

    list($id_etablissement, $id_table, $signature) = $parts;

    // Vérifier format id/table
    if (!ctype_digit($id_etablissement) || !ctype_digit($id_table)) {
        exit("QR code modifié ou invalide");
    }

    // Vérifier la signature
    $expected = hash_hmac('sha256', $id_etablissement . ":" . $id_table, $secret);
    // var_dump($signature); 
    // var_dump($expected);

    if (!hash_equals($expected, $signature)) {
        exit("QR code modifié ou invalide");
    }

    // Vérifier que la table existe réellement
    require_once './../api-commande/models/Table.php';
    $tableModel = new Table();
    $tableData = $tableModel->getTable($id_etablissement, $id_table); // méthode à créer pour vérifier table précise
    if (!$tableData) {
        exit("Table invalide");
    }

    $nom_table = $tableData['nom'];

    // ✅ Tout est OK, tu peux continuer
    echo "Etablissement: $id_etablissement, Table: $nom_table";

    require_once './../api-commande/models/Etablissement.php';
    $etablissementModel = new Etablissement();
    $etablissements = $etablissementModel->getById($id_etablissement);

    require_once './../api-commande/models/Categorie.php';
    $categorieModel = new Categorie();
    $categories = $categorieModel->getCategoriesByEtablissement($id_etablissement);

    require_once './../api-commande/models/Produit.php';
    $produitModel = new Produit();
    $produits = $produitModel->getProduitsByEtablissement($id_etablissement);

    $devise = $etablissements["devise"];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=htmlspecialchars($etablissements['nom'])?></title>
    <?php
        $logos = json_decode($etablissements['logo'], true);
        $logo = $logos[0] ?? '';
    ?>
    <link href="<?= htmlspecialchars($logo) ?>" rel="icon">

    <link href="./assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>

<body class="body">

<header class="head">
    <h1><?=htmlspecialchars($etablissements['nom'])?></h1>
</header>

<!-- CATEGORIES -->

<div class="categories mt-3 mb-3">

    <div class="filter-container">
        <button class="filter-btn active" data-filter="tout">Tout</button>
        <?php
        foreach ($categories as $e) {
            $catId = 'filter-' . $e['id_categorie'];
            echo '<button class="filter-btn" data-filter="' . $catId . '">' 
                 . htmlspecialchars($e['libelle']) . 
                 '</button>';
        }
        ?>
    </div>


</div>

<!-- MENU -->

<div class="menu-grid container-fluid">
<?php
foreach ($produits as $e) {

    $images = json_decode($e['image'], true);
    $imgSrc = $images[0] ?? '';
                             
    // Classe de catégorie
    $catClass = 'filter-' . $e['id_categorie'];

    echo '
    <div class="menu-card ' . htmlspecialchars($catClass) . '">
        <div class="image-container">
            <img src="' . htmlspecialchars($imgSrc) . '" alt="' . htmlspecialchars($e['nom']) . '" />
        </div>
        <div class="content">
            <div class="flex-between">
                <h3>' . htmlspecialchars($e['nom']) . '</h3>
                <span class="price">' . htmlspecialchars($e['prix']) . ' ' . htmlspecialchars($devise) . '</span>
            </div>
            <p>' . htmlspecialchars($e['description']) . '</p>
            <input type="number" class="valeurquantite" value="1" min="1" style="width: 80px; float:right; margin-bottom: 20px;">

            <button class="ajouter"  data-id='.$e['id_produit'].'><i data-feather="shopping-cart"></i> Ajouter</button>
            </a>
        </div>
    </div>';
}
?>
</div>

<!-- NAVBAR -->

<nav class="nav">

    <div class="commander">
        <span>🛒</span>
        My basket
        <span class="cart-count" style="display:none;">0</span>
    </div>

    <div class="facture">
        <span>🧾</span>
        My commands
    </div>

    <div class="terminer">
        <span>🛎️</span>
        ask my bill
    </div>

</nav>

<!-- MODAL -->

    <div class="modal fade modal-c" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" style="font-size: 17px;" id="modalLabel">My basket</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Command</th>
                        <th>Qte</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tablePanier"></tbody>
            </table>
            <hr>

            <div class="d-flex justify-content-between">
                <h5>Total amount :</h5>
                <h5 id="montantFinal">0 <?= htmlspecialchars($devise) ?></h5>
            </div>

            <div>
                <input type="text" class="mt-3" id="numeroTable" style="width: 80px" placeholder="N° table" value="<?= htmlspecialchars($nom_table); ?>" disabled>
                <button class="btn btn-warning float-right mt-3" id="btn-valider" style="display:none;">Command now</button>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade modal-f" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" style="font-size: 17px;" id="modalLabel">My commands</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Command</th>
                        <th>Qte</th>
                        <th>Amount</th>
                        <th>Statu</th>
                    </tr>
                </thead>
                <tbody id="tableFacture"></tbody>
            </table>
            <hr>

            <div class="d-flex justify-content-between">
                <h5>Total amount :</h5>
                <h5 id="montantTotal">0 <?= htmlspecialchars($devise) ?></h5>
            </div>

            <div>
                <input type="text" class="mt-3" id="numeroTable" style="width: 80px" placeholder="N° table" value="<?= htmlspecialchars($nom_table); ?>" disabled>
                <input type="hidden" id="id_table" value="<?= htmlspecialchars($id_table); ?>">
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <script src="./assets/vendor/jquery/jquery.min.js"></script>
    <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/feather.min.js"></script>

<script>
    const id_etablissement = "<?= htmlspecialchars($id_etablissement) ?>"
    const id_table = "<?= htmlspecialchars($id_table) ?>"; 
    const devise = "<?= htmlspecialchars($etablissements["devise"]) ?>";
    var panier = [];

/* CALCUL TOTAL */

function calculerMontantFinal() {

    let total = 0;

    panier.forEach(item => {
        total += item.total;
    });
    parseFloat(total.toFixed(2));
    $("#montantFinal").text(total.toFixed(2) + " " + devise);
}

/* UPDATE MODAL */

function mettreAJourModal() {

    var html = "";

    panier.forEach(function(item, index) {

        html += `
        <tr>
            <td>${item.libelle}</td>
            <td>${item.quantite}</td>
            <td>${item.total.toFixed(2)} ${devise}</td>
            <td>
                <button class="btn btn-danger btn-sm supprimer-item"
                        data-index="${index}"
                        data-id="${item.id}">
                    x
                </button>
            </td>
        </tr>
        `;
    });

    $("#tablePanier").html(html);

    calculerMontantFinal();

    if (panier.length > 0) {
        $("#btn-valider").show();
    } else {
        $("#btn-valider").hide();
    }
}

/* AJOUT PANIER */

$(document).on('click','.ajouter',function(e){

    e.preventDefault();

    var bouton = $(this);
    var id = bouton.data('id');

    var container = bouton.closest('.menu-card');

    var nom = container.find("h3").text();

    var prix = container.find(".price").text().replace(" " + devise,"");

    var quantite = container.find('.valeurquantite').val();

    var total = prix * quantite;

    let index = panier.findIndex(
        item => item.id === id
    );

    if (index !== -1) {

        panier[index].quantite = quantite;
        panier[index].total = prix * quantite;

    } else {

        panier.push({
            id: id,
            libelle: nom,
            prix: prix,
            quantite: quantite,
            total: total
        });

    }

    if (container.find('.check-panier').length === 0) {
        container.append('<span class="check-panier">✓</span>');
    }

    mettreAJourModal();
    let nbElementsDistincts = panier.length;
    $('.commander .cart-count').show().text(nbElementsDistincts);

});

$(document).on('click', '.supprimer-item', function() {
    var index = $(this).data('index');
    var id = $(this).data('id');

    panier.splice(index, 1);

    var container = $('.ajouter[data-id="'+id+'"]').closest('.menu-card');
    container.find('.valeurquantite').val(1);  // remettre quantité à 1
    container.find('.check-panier').remove();   // enlever le check

    mettreAJourModal();
    let nbElementsDistincts = panier.length;
    if(nbElementsDistincts === 0){
        $('.commander .cart-count').hide();   // cacher si vide
    } else {
        $('.commander .cart-count').show().text(nbElementsDistincts);
    }
});

$(document).on('click','.commander',function(){ 
    $('.modal-c').modal({ backdrop:'static', keyboard:false }); 
});


$(document).on('click', '.facture', function() { 
    $.ajax({
        url: "http://gusto/api-commande/routes/commande.php?id_etablissement=" + id_etablissement,
        method: "GET",
        dataType: "json"
    })
    .done(function(res) {
        if (!res.success) {
            alert("Error: " + res.message);
            return;
        }

        // Vider le tableau avant de remplir
        $('#tableFacture').empty();

        let totalGeneral = 0;

        res.data.forEach(function(item) {
            if (item.etat=="Payé" || item.id_table != id_table) return
            // Parse la chaîne JSON de la commande
            let commandes;
            try {
                commandes = JSON.parse(item.commande);
            } catch (e) {
                console.error("Erreur JSON.parse:", e);
                return;
            }

            commandes.forEach(function(prod) {
                const nomProduit = prod.libelle;
                const quantite = prod.quantite;
                const montantLigne = prod.total;
                const etat = item.etat;

                let row = `<tr>
                    <td>${nomProduit}</td>
                    <td>${quantite}</td>
                    <td>${montantLigne.toFixed(2)} ${devise}</td>
                    <td>${etat}</td>
                </tr>`;

                $('#tableFacture').append(row);
                totalGeneral += montantLigne;
            });
        });

        // Mettre à jour le montant total
       $('#montantTotal').text(totalGeneral.toFixed(2) + " " + devise);
       

        // Afficher la modal (Bootstrap 5)
         $('.modal-f').modal({ backdrop:'static', keyboard:false });
    })
    .fail(function(xhr, status, error) {
        alert("An error has occurred: " + error);
    });
});

//Socket

let socket = new WebSocket("ws://192.168.100.238:8080");

    socket.onopen = function () {
        console.log("✅ WebSocket connecté (client)");
        socket.send(JSON.stringify({
            type: "register",
            id_etablissement: id_etablissement
        }));
    };

    // Gestion des erreurs et fermeture
    socket.onerror = function (err) {
        console.error("❌ Error WebSocket", err);
    };

    socket.onclose = function () {
        console.warn("⚠️ WebSocket diconnected");
    };

    $(document).on('click', '#btn-valider', function () {

        let idTable = $("#id_table").val();
        let totalGeneral = panier.reduce((sum, item) => sum + item.total, 0);

        let payload = {
            id_table: idTable,
            id_etablissement: id_etablissement,
            commande: panier,
            montant_total: totalGeneral
        };

        $.ajax({
            url: "http://gusto/api-commande/routes/commande.php?id_etablissement=" + id_etablissement,
            method: "POST",
            contentType: "application/json", // <- important !
            data: JSON.stringify(payload)     // <- tout l'objet en JSON
            
        })
        .done(function(response){

            const res = response;

            // ✅ Vérifier succès
            if (!res.success) {
                alert("Error: " + res.message);
                return;
            }

            const id_Commande = res.id_commande;

            // ✅ WebSocket
            if (socket.readyState === WebSocket.OPEN) {
                socket.send(JSON.stringify({
                    type: "new_command",
                    id_etablissement: id_etablissement,
                    table: idTable,
                    commande: panier,
                    montant: totalGeneral,
                    date: new Date().toLocaleString(),
                    etat: "En attente",
                    id_commande: id_Commande
                }));
            }

            // RESET
            panier = [];
            $('.commander .cart-count').hide(); 
            $('.check-panier').remove(); 
            $('.valeurquantite').val(1); 
            mettreAJourModal();
            $('.modal-c').modal('hide');

            alert("Command sent please wait a moment");
        })
        .fail(function(){
            alert("Server error ❌");
        });

    });


    // 🔴 FIN DE COMMANDE (bouton "terminer")
    $(document).on('click', '.terminer', function () {

        let numeroTable = $("#id_table").val();

        if (socket.readyState === WebSocket.OPEN) {
            socket.send(JSON.stringify({
                type: "table_completed",
                id_etablissement: id_etablissement,
                table: numeroTable,
                date: new Date().toLocaleString()
            }));
            alert('Your command has been processed')

            console.log("📤 Table completed sent :", numeroTable);
        } else {
            console.warn("⚠️ WebSocket no connected");
        }
    });

</script>

<script>
        feather.replace();

        // Animation on scroll
        const dishCards = document.querySelectorAll('.menu-card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        dishCards.forEach(card => {
            observer.observe(card);
        });

        // Filtrage par catégorie
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const category = button.getAttribute('data-filter');

                dishCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    card.style.display = (category === 'tout' || cardCategory === category) ? 'block' : 'none';
                });

                // Changement de style actif
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
    // Feather icons
            if (window.feather) feather.replace();

            const filterButtons = document.querySelectorAll('.filter-btn');
            const dishCards = document.querySelectorAll('.menu-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filter = button.getAttribute('data-filter'); // ex: "filter-1"

                    dishCards.forEach(card => {
                        if (filter === 'tout' || card.classList.contains(filter)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Gestion de l'état actif
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                });
            });
        });
    </script>

</body>
</html>