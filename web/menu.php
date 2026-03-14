
<?php
  require_once './../api-commande/models/Categorie.php';
  $categorieModel = new Categorie();
  $id_etablissement = 18;
  $categories = $categorieModel->getCategoriesByEtablissement($id_etablissement);

  require_once './../api-commande/models/Produit.php';
  $produitModel = new Produit();
  $id_etablissement = 18;
  $produits = $produitModel->getProduitsByEtablissement($id_etablissement);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Restaurant</title>

    <link href="./../assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="./../assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./../assets/css/style.css" />
</head>

<body class="body">

<header class="head">
    <h1>Menu</h1>
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

    $imgSrc = !empty($e['image']) ? trim($e['image'], '[]') : '';
                             
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
              <h5 class="modal-title m-0 font-weight-bold" style="font-size: 17px;" id="modalLabel">Liste de la commande</h5>
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
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tablePanier"></tbody>
            </table>
            <hr>

            <div class="d-flex justify-content-between">
                <h5>Total général :</h5>
                <h5 id="montantFinal">0 FCFA</h5>
            </div>

            <div>
                <button class="btn btn-warning float-right mt-3" id="btn-valider" style="display:none;">Commander maintenant</button>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <script src="./../assets/vendor/jquery/jquery.min.js"></script>
    <script src="./../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./../assets/js/feather.min.js"></script>

<script>

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

});

$(document).on('click', '.supprimer-item', function() {
    var index = $(this).data('index');
    var id = $(this).data('id');

    panier.splice(index, 1);

    var container = $('.ajouter[data-id="'+id+'"]').closest('.menu-card');
    container.find('.valeurquantite').val(1);  // remettre quantité à 1
    container.find('.check-panier').remove();   // enlever le check

    mettreAJourModal();
});

$(document).on('click','.commander',function(){ 
    $('.modal-c').modal({ backdrop:'static', keyboard:false }); 
});

//socket

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