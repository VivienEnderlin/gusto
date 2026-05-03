<?php
    require_once 'link.php';

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

                        <div class="quantite-box">
                            <div class="btn-moins">−</div>
                            <span class="valeurquantite">1</span>
                            <div class="btn-plus">+</div>
                        </div>

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
                    <h5 id="montantFinal">0.00 <?= htmlspecialchars($devise) ?></h5>
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
                  <h5 class="modal-title m-0 font-weight-bold" style="font-size: 17px;" id="modalLabel"></h5>
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
                    <h5 id="montantTotal"><?= htmlspecialchars($devise) ?></h5>
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
            const qrCode = "<?= htmlspecialchars($code) ?>";
            const codeService = localStorage.getItem("code_service");
            if (!codeService) {
                 window.location.href = "index.php?code=" + qrCode;
            }
            let panier = [];
            let socket = null;

            // ================= INIT =================
            document.addEventListener("DOMContentLoaded", () => {
                initFeather();
                initFilters();
                initWebSocket();
            });

            // ================= UI =================
            function initFeather() {
                if (window.feather) feather.replace();
            }

            // ================= FILTRES =================
            function initFilters() {
                const filterButtons = document.querySelectorAll('.filter-btn');
                const dishCards = document.querySelectorAll('.menu-card');

                filterButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const filter = button.getAttribute('data-filter');

                        dishCards.forEach(card => {
                            if (filter === 'tout' || card.classList.contains(filter)) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        });

                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        button.classList.add('active');
                    });
                });
            }

            /* CALCUL TOTAL */

            function calculerMontantFinal() {
                let total = panier.reduce((sum, item) => sum + item.total, 0);
                $("#montantFinal").text(total.toFixed(2) + " " + devise);
            }

            /* UPDATE MODAL */

            function mettreAJourModal() {

                let html = "";

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

            /* AJOUT AU PANIER */

            $(document).on('click','.ajouter',function(e){
                e.preventDefault();

                let container = $(this).closest('.menu-card');
                let id = $(this).data('id');
                let nom = container.find("h3").text();
                let prix = parseFloat(container.find(".price").text().replace(" " + devise,""));
                let quantite = parseInt(container.find('.valeurquantite').text());

                let index = panier.findIndex(item => item.id === id);

                if (index !== -1) {
                    panier[index].quantite = quantite;
                    panier[index].total = prix * quantite;
                } else {
                    panier.push({ id, libelle: nom, prix, quantite, total: +(prix * quantite).toFixed(2) });
                }

                if (container.find('.check-panier').length === 0) {
                    container.append('<span class="check-panier">✓</span>');
                }

                mettreAJourModal();
                let nbElementsDistincts = panier.length;
                $('.commander .cart-count').show().text(nbElementsDistincts);
            });

            /* SUPPRIMER */

            $(document).on('click', '.supprimer-item', function() {
                let index = $(this).data('index');
                let id = $(this).data('id');

                panier.splice(index, 1);

                let container = $('.ajouter[data-id="'+id+'"]').closest('.menu-card');
                container.find('.valeurquantite').text(1);
                container.find('.check-panier').remove();

                mettreAJourModal();
                let nbElementsDistincts = panier.length;
                if(nbElementsDistincts === 0){
                    $('.commander .cart-count').hide();   // cacher si vide
                } else {
                    $('.commander .cart-count').show().text(nbElementsDistincts);
                }
            });

            /* OUVRIR MODAL */

            $(document).on('click','.commander',function(){ 
                $('.modal-c').modal({ backdrop:'static', keyboard:false }); 
            });


            $(document).on('click', '.facture', function() { 
                let id_ticket = localStorage.getItem("id_ticket");
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
                    $('#tableFacture').empty();
                    let totalGeneral = 0;
                    res.data.forEach(function(item) {
                    if (item.id_ticket != id_ticket) return;

                    let commandes = item.commandes;

                    commandes.forEach(function(prod) {
                        let row = `<tr>
                            <td>${prod.libelle}</td>
                            <td>${prod.quantite}</td>
                            <td>${prod.total.toFixed(2)} ${devise}</td>
                            <td>${prod.etat}</td>
                        </tr>`;

                        $('#tableFacture').append(row);
                        totalGeneral += prod.total;
                    });
                });
                   $('#montantTotal').text(totalGeneral.toFixed(2) + " " + devise);
                   $('.modal-f .modal-title').text(localStorage.getItem("id_ticket") ? localStorage.getItem("id_ticket") : "My commands");
                   $('.modal-f').modal({ backdrop:'static', keyboard:false });
                })
                .fail(function(xhr, status, error) {
                    alert("An error has occurred: " + error);
                });
            });


           /* SOCKET */

            function initWebSocket() {
                if (socket) return; // 🔥 empêche double connexion

                socket = new WebSocket("ws://172.30.108.90:8080");

                socket.onopen = () => {
                    console.log("✅ WebSocket connected");
                    socket.send(JSON.stringify({
                        type: "register",
                        id_etablissement
                    }));
                };

                socket.onerror = err => console.error("❌ WS error", err);
                socket.onclose = () => console.warn("⚠️ WS closed");
            }

            $(document).on('click', '#btn-valider', function () {

                let totalGeneral = panier.reduce((sum, item) => sum + item.total, 0);
                let id_ticket = localStorage.getItem("id_ticket");

                if (!id_ticket) {
                    const now = new Date();
                    const pad = n => n.toString().padStart(2, "0");
                   id_ticket = `TCK-${pad(id_table)}-${String(now.getFullYear()).slice(-2)}${pad(now.getMonth()+1)}${pad(now.getDate())}-${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}${pad(now.getMilliseconds())}`;

                    localStorage.setItem("id_ticket", id_ticket);
                }

                let payload = {
                    id_table,
                    id_etablissement,
                    commande: panier,
                    montant_total: totalGeneral,
                    devise: devise,
                    id_ticket
                };

                $.ajax({
                    url: "http://gusto/api-commande/routes/commande.php?id_etablissement=" + id_etablissement,
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(payload)
                })
                .done(res => {

                    if (!res.success) {
                        alert("Error: " + res.message);
                        return;
                    }

                    if (socket?.readyState === WebSocket.OPEN) {
                        socket.send(JSON.stringify({
                            type: "new_command",
                            id_etablissement,
                            table: id_table,
                            commande: panier,
                            montant: totalGeneral,
                            etat: "En attente",
                            id_commande: res.id_commande,
                            id_ticket: id_ticket
                        }));
                    }

                    // RESET
                    panier = [];
                    $('.commander .cart-count').hide(); 
                    $('.check-panier').remove();
                    $('.valeurquantite').text(1);
                    mettreAJourModal();
                    $('.modal-c').modal('hide');

                    alert("Command sent please wait a moment");
                })
                .fail(() => alert("Server error ❌"));
            });

            // ================= TERMINER =================

            $(document).on('click', '.terminer', function () {
                let id_ticket = localStorage.getItem("id_ticket");
                if (socket?.readyState === WebSocket.OPEN) {
                    socket.send(JSON.stringify({
                        type: "table_completed",
                        id_etablissement,
                        table: id_table,
                        id_ticket: id_ticket
                    }));
                }
                ["id_ticket", "code_service"].forEach(k => localStorage.removeItem(k));

                alert("✅ please wait a few moment your bill is coming");
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

            document.addEventListener('click', function(e) {

                // bouton +
                if (e.target.classList.contains('btn-plus')) {
                    const box = e.target.closest('.quantite-box');
                    const span = box.querySelector('.valeurquantite');
                    let valeur = parseInt(span.textContent);
                    span.textContent = valeur + 1;
                }

                // bouton -
                if (e.target.classList.contains('btn-moins')) {
                    const box = e.target.closest('.quantite-box');
                    const span = box.querySelector('.valeurquantite');
                    let valeur = parseInt(span.textContent);

                    if (valeur > 1) {
                        span.textContent = valeur - 1;
                    }
                }

            });
        </script>

    </body>
</html>