<?php
  session_start();
  require_once './../controller/bdFonctions.class.php';

  $manageBd = new  bdFonctions();

  $table = $_GET['table'];
  $idunique = $_GET['id'];


  $tb='menu';
  $tbc='categorie';
  $tbt='identifianttable';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gusto Galaxy - Découvrez nos décelis</title>
     <link href="" class="logo icon" rel="icon">

    <link rel="stylesheet" href="./../assets/css/style.css" />
    <link href="./../assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="./../assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600&display=swap"
      rel="stylesheet"
    />
</head>
<body>
    <custom-navbar></custom-navbar>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Gusto Galaxy</h1>
                <p>Une expérience culinaire hors du commun</p>
                <button>Explorer le menu</button>
            </div>
            <div class="hero-divider"></div>
        </section>

        <!-- Menu Section -->
       <section class="menu-section">
            <h2>Notre Menu Exquis</h2>
            <p>Découvrez nos créations gastronomiques préparées avec des ingrédients frais et locaux.</p>

            <!-- Filter Buttons -->
            <div class="filter-container">
                <button class="filter-btn active" data-filter="all">All</button>
                <?php
                $resp = $manageBd->getAll($tbc, "");
                while ($elt = $resp->fetch()) {
                    $catId = 'filter-' . $elt['idcategorie'];
                    echo '<button class="filter-btn" data-filter="' . $catId . '">' 
                         . htmlspecialchars($elt['libelle']) . 
                         '</button>';
                }
                $resp->closeCursor();
                ?>
            </div>

            <!-- Menu Grid -->
            <div class="menu-grid">
                <?php
                    $resp = $manageBd->getAll($tb, "WHERE idunique = '".$idunique."' ");

                    while ($plat = $resp->fetch()) {

                        // Gestion des images JSON
                        $imageData = json_decode($plat['image'], true);
                        $imgUrl = $imageData['image'][0] ?? $plat['image'] ?? './../assets/img/placeholder.png';

                        // Classe de catégorie
                        $catClass = 'filter-' . $plat['idcategorie'];

                        echo '
                        <div class="menu-card ' . htmlspecialchars($catClass) . '">
                            <div class="image-container">
                                <img src="' . htmlspecialchars($imgUrl) . '" alt="' . htmlspecialchars($plat['libelle']) . '" />
                            </div>
                            <div class="content">
                                <div class="flex-between">
                                    <h3>' . htmlspecialchars($plat['libelle']) . '</h3>
                                    <span class="price">' . $plat['prix'] . ' Fcfa</span>
                                </div>
                                <p>' . htmlspecialchars($plat['description']) . '</p>
                                <input type="number" class="valeurquantite" value="1" min="1" style="width: 80px; float:right; margin-bottom: 20px;">
                                <a href="./../controller/ajouter.php?id=' . $plat["idmenu"] .'" class="ajouter"> 
                                    <button><i data-feather="shopping-cart"></i> Ajouter</button>
                                </a>
                            </div>
                        </div>';
                    }
                    $resp->closeCursor();
                ?>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <h2>Livraison à domicile disponible</h2>
            <p>Recevez votre commande en moins de 10 minutes*</p>
            <button class="commander">Commander maintenant</button>
            <small>*Bonne dégustation à nos chers clients</small>
            <button class="terminer mt-3">je reclame ma facture <i class="icofont-bell-alt"></i></button>
        </section>
    </main>

    <div class="modal fade modal-c" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" style="font-size: 17px;" id="modalLabel">Récapitulatif de la commande</h5>
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
                <input type="text" id="numeroTable" style="width: 80px" placeholder="N° table" disabled>
                <button class="btn btn-warning float-right" id="btn-valider" style="display:none;">Valider la commande</button>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <script src="./../assets/vendor/jquery/jquery.min.js"></script>
    <script src="./../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./../assets/js/feather.min.js"></script>

    <script>
        const ID_UNIQUE_RESTAURANT = "<?= htmlspecialchars($idunique, ENT_QUOTES) ?>"

        function calculerMontantFinal() {
            let total = 0;

            panier.forEach(item => {
                total += parseInt(item.total);
            });

            $("#montantFinal").text(total + " FCFA");
        }

        function mettreAJourModal() {
            var html = "";

            panier.forEach(function(item, index){
                html += `
                    <tr>
                        <td>${item.libelle}</td>
                        <td>${item.quantite}</td>
                        <td>${item.total} FCFA</td>
                        <td>
                            <button class="btn btn-danger btn-sm supprimer-item icofont-close" data-index="${index}"></button>
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

        var panier = [];

        $(document).on('click','.ajouter', function(e){
            e.preventDefault();
            var elt = $(this);
            var container = elt.closest('.menu-card');
            var quantiteInput = container.find('.valeurquantite');
            var valeurQuantite = quantiteInput.val();
            var url = elt.attr('href');
            $.post(url, { quantite: valeurQuantite })
            .done(function(data){
                data = JSON.parse(data);

                if (data.backData) {

                    let index  = panier.findIndex(item => item.libelle === data.libelle);

                    if (index !== -1) {
                        panier[index].quantite = data.quantite_choisie;
                        panier[index].total = data.net_à_payer;
                    }else{
                            panier.push({
                            libelle: data.libelle,
                            quantite: data.quantite_choisie,
                            total: data.net_à_payer
                        });
                    }
                    // --- Ajouter la commande dans le panier ---
                    
                    // --- Mettre à jour l'affichage de la modal ---
                    mettreAJourModal();
                }
            });
        });

        $(document).on('click','.commander', function(e){
            // $('.modal-c button[type=submit]').text("Commander");
            $('#numeroTable').val('<?= htmlspecialchars($table); ?>')
            $('.modal-c').modal({backdrop:'static',keyboard:false});
        });

        $(document).on('click', '.supprimer-item', function() {
            var index = $(this).data('index');

            panier.splice(index, 1);  // Supprime l'élément du tableau

            mettreAJourModal(); // Rafraîchit la table
        });


        let socket = new WebSocket("ws://192.168.100.238:8080");

        socket.onopen = function () {
            console.log("✅ WebSocket connecté (client)");
            socket.send(JSON.stringify({
                type: "register",
                idunique: ID_UNIQUE_RESTAURANT
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

            // 1️⃣ ENREGISTREMENT EN BASE (comme avant)
            $.post("./../controller/commande.php", {
                idtable: numeroTable,
                idunique: ID_UNIQUE_RESTAURANT,
                commande: JSON.stringify(panier),
                montant_a_payer: totalGeneral
            })
            .done(function(response){

                const res = JSON.parse(response);
                const idCommande = res.idcommande; 

                // 2️⃣ ENVOI INSTANTANÉ VIA WEBSOCKET
                if (socket.readyState === WebSocket.OPEN) {
                    socket.send(JSON.stringify({
                        type: "nouvelle_commande",
                        idunique: ID_UNIQUE_RESTAURANT,
                        table: numeroTable,
                        commande: panier,
                        montant: totalGeneral,
                        date: new Date().toLocaleString(),
                        statut: "En attente",
                        idcommande: idCommande
                    }));
                }
                
                panier = [];
                mettreAJourModal();
                $('.modal-c').modal('hide');
                alert(`Le serveur ${numeroTable} s'occupe de votre commande`);
            });
        });

        // 🔴 FIN DE COMMANDE (bouton "terminer")
        $(document).on('click', '.terminer', function () {

            let numeroTable = "<?= htmlspecialchars($table, ENT_QUOTES) ?>";

            if (socket.readyState === WebSocket.OPEN) {
                socket.send(JSON.stringify({
                    type: "table_terminee",
                    idunique: ID_UNIQUE_RESTAURANT,
                    table: numeroTable,
                    date: new Date().toLocaleString()
                }));
                alert('votre demande à été prise en compte')

                console.log("📤 Table terminée envoyée :", numeroTable);
            } else {
                console.warn("⚠️ WebSocket non connecté");
            }
        });
</script>


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
                    card.style.display = (category === 'all' || cardCategory === category) ? 'block' : 'none';
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
                        if (filter === 'all' || card.classList.contains(filter)) {
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
