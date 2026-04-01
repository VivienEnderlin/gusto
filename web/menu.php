<?php
$code = $_GET['code'] ?? null;
  $secret = "CLE_SECRETE_GUSTO";

    if(!$code){
        exit("Lien invalide");
    }

    $decoded = base64_decode($code);
    list($id_etablissement, $table) = explode(":", $decoded);
    $check = hash_hmac('sha256', $id_etablissement.":".$table, $secret);

    if(!ctype_digit($id_etablissement) || !ctype_digit($table)){
        exit("QR code modifié ou invalide");
    }

   require_once './../api-commande/models/Table.php';
    $tableModel = new Table();
    $exists = $tableModel->getTablesByEtablissement($id_etablissement);

    if(!$exists){
        exit("QR code modifié ou invalide");
    }

echo "Etablissement: $id_etablissement, Table: $table";

    // $code = $_GET['code'] ?? null;
    // $secret = "CLE_SECRETE_GUSTO";

    // if (!$code) {
    //     exit("Lien invalide");
    // }

    // // Décodage sécurisé
    // $decoded = base64_decode($code, true);
    // if (!$decoded) exit("QR code invalide");

    // // Extraire id, table et signature
    // $parts = explode(":", $decoded);
    // if (count($parts) !== 3) exit("QR code invalide");

    // list($id_etablissement, $table, $signature) = $parts;

    // // Vérifier format id/table
    // if (!ctype_digit($id_etablissement) || !preg_match('/^[\w\s-]+$/u', $table)) {
    //     exit("QR code modifié ou invalide");
    // }

    // // Vérifier la signature
    // $expected = hash_hmac('sha256', $id_etablissement . ":" . $table, $secret);
    // if (!hash_equals($expected, $signature)) {
    //     exit("QR code modifié ou invalide");
    // }

    // // Vérifier que la table existe réellement
    // require_once './../api-commande/models/Table.php';
    // $tableModel = new Table();
    // $exists = $tableModel->getTable($id_etablissement, $table); // méthode à créer pour vérifier table précise
    // if (!$exists) {
    //     exit("QR code modifié ou invalide");
    // }

    // // ✅ Tout est OK, tu peux continuer
    // echo "Etablissement: $id_etablissement, Table: $table";

    require_once './../api-commande/models/Etablissement.php';
    $etablissementModel = new Etablissement();
    $etablissements = $etablissementModel->getById($id_etablissement);

    require_once './../api-commande/models/Categorie.php';
    $categorieModel = new Categorie();
    $categories = $categorieModel->getCategoriesByEtablissement($id_etablissement);

    require_once './../api-commande/models/Produit.php';
    $produitModel = new Produit();
    $produits = $produitModel->getProduitsByEtablissement($id_etablissement);
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
                <span class="price">' . htmlspecialchars($e['prix']) . ' Fcfa</span>
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
        Mon panier
        <span class="cart-count" style="display:none;">0</span>
    </div>

    <div class="facture">
        <span>🧾</span>
        Voir mes commandes
    </div>

    <div class="terminer">
        <span>🛎️</span>
        reclamer ma facture
    </div>

</nav>

<!-- MODAL -->

    <div class="modal fade modal-c" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" style="font-size: 17px;" id="modalLabel">Mon panier</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Qte</th>
                        <th>Montant</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tablePanier"></tbody>
            </table>
            <hr>

            <div class="d-flex justify-content-between">
                <h5>Montant total :</h5>
                <h5 id="montantFinal">0 FCFA</h5>
            </div>

            <div>
                <input type="text" class="mt-3" id="numeroTable" style="width: 80px" placeholder="N° table" value="<?= htmlspecialchars($table); ?>" disabled>
                <button class="btn btn-warning float-right mt-3" id="btn-valider" style="display:none;">Commander maintenant</button>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade modal-f" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" style="font-size: 17px;" id="modalLabel">Mes commandes</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Qte</th>
                        <th>Montant</th>
                        <th>Etat</th>
                    </tr>
                </thead>
                <tbody id="tableFacture"></tbody>
            </table>
            <hr>

            <div class="d-flex justify-content-between">
                <h5>Montant total :</h5>
                <h5 id="montantTotal">0 FCFA</h5>
            </div>

            <div>
                <input type="text" class="mt-3" id="numeroTable" style="width: 80px" placeholder="N° table" value="<?= htmlspecialchars($table); ?>" disabled>
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
    var panier = [];

/* CALCUL TOTAL */

function calculerMontantFinal() {

    let total = 0;

    panier.forEach(item => {
        total += parseInt(item.total);
    });

    $("#montantFinal").text(total + " FCFA");
}

/* UPDATE MODAL */

function mettreAJourModal() {

    var html = "";

    panier.forEach(function(item, index) {

        html += `
        <tr>
            <td>${item.libelle}</td>
            <td>${item.quantite}</td>
            <td>${item.total} FCFA</td>
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

    var prix = parseInt(
        container.find(".price").text().replace(" Fcfa","")
    );

    var quantite = parseInt(
        container.find('.valeurquantite').val()
    );

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


const table = "<?= htmlspecialchars($table) ?>"; 

$(document).on('click', '.facture', function() { 
    $.ajax({
        url: "http://gusto/api-commande/routes/commande.php?id_etablissement=" + id_etablissement,
        method: "GET",
        dataType: "json"
    })
    .done(function(res) {
        if (!res.success) {
            alert("Erreur: " + res.message);
            return;
        }

        // Vider le tableau avant de remplir
        $('#tableFacture').empty();

        let totalGeneral = 0;

        res.data.forEach(function(item) {
            if (item.etat=="Payé" || item.id_table != table) return
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
                const quantite = parseInt(prod.quantite);
                const montantLigne = parseFloat(prod.total);
                const etat = item.etat;

                let row = `<tr>
                    <td>${nomProduit}</td>
                    <td>${quantite}</td>
                    <td>${montantLigne.toLocaleString()} FCFA</td>
                    <td>${etat}</td>
                </tr>`;

                $('#tableFacture').append(row);
                totalGeneral += montantLigne;
            });
        });

        // Mettre à jour le montant total
       
        $('#montantTotal').text(totalGeneral.toLocaleString() + " FCFA");
       

        // Afficher la modal (Bootstrap 5)
         $('.modal-f').modal({ backdrop:'static', keyboard:false });
    })
    .fail(function(xhr, status, error) {
        alert("Une erreur est survenue: " + error);
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
        console.error("❌ Erreur WebSocket", err);
    };

    socket.onclose = function () {
        console.warn("⚠️ WebSocket déconnecté");
    };

    $(document).on('click', '#btn-valider', function () {

        let numeroTable = $("#numeroTable").val().trim();
        let totalGeneral = panier.reduce((sum, item) => sum + item.total, 0);

        let payload = {
            id_table: numeroTable,
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
                alert("Erreur: " + res.message);
                return;
            }

            const id_Commande = res.id_commande;

            // ✅ WebSocket
            if (socket.readyState === WebSocket.OPEN) {
                socket.send(JSON.stringify({
                    type: "nouvelle_commande",
                    id_etablissement: id_etablissement,
                    table: numeroTable,
                    commande: panier,
                    montant: totalGeneral,
                    etat: "En attente",
                    id_commande: id_Commande
                }));
            }

            // RESET
            panier = [];
            $('.commander .cart-count').hide(); 
            $('.check-panier').remove(); 
            mettreAJourModal();
            $('.modal-c').modal('hide');

            alert("Commande envoyée veillez patienter quelques s'il vous plaît");
        })
        .fail(function(){
            alert("Erreur serveur ❌");
        });

    });


    // 🔴 FIN DE COMMANDE (bouton "terminer")
    $(document).on('click', '.terminer', function () {

        let numeroTable = "<?= htmlspecialchars($table) ?>";

        if (socket.readyState === WebSocket.OPEN) {
            socket.send(JSON.stringify({
                type: "table_terminee",
                id_etablissement: id_etablissement,
                table: numeroTable
            }));
            alert('votre demande à été prise en compte')

            console.log("📤 Table terminée envoyée :", numeroTable);
        } else {
            console.warn("⚠️ WebSocket non connecté");
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