<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gusto Manager</title>
    <link href="./assets/img/gusto.png" class="logo icon" rel="icon">

    <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="./assets/vendor/datatables/datatables.bootstrap4.min.css" rel="stylesheet">
    <link href="./assets/vendor/virtual-select/virtual-select.min.css" rel="stylesheet">
    <link href="./assets/vendor/build/intlTelInput.css" rel="stylesheet">
    <link href="./assets/vendor/font-awesome/css/all.min.css" rel="stylesheet">
    <link href="./assets/css/gerant.css" rel="stylesheet">
</head>

<body>

    <aside class="sidebar">

        <div class="logo">
            <span id="etabName"></span>
            <hr style="margin-top:20px;">
        </div>

        <ul class="menu">

            <li class="active" data-target="dashboard">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </li>

            <li data-target="tables">
                <i class="fa-solid fa-chair"></i>
                Tables
            </li>

            <li data-target="categories">
                <i class="fa-solid fa-list"></i>
                Categories
            </li>

            <li data-target="produits">
                <i class="fa-solid fa-burger"></i>
                Produits
            </li>

            <li data-target="employes">
                <i class="fa-solid fa-users"></i>
                Employés
            </li>

            <li data-target="commandes">
                <i class="fa-solid fa-receipt"></i>
                Commandes
            </li>

            <li data-target="logout" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i>
                <a href="#" style="color: #fff; text-decoration: none;">Deconnexion</a>
                
            </li>

        </ul>

    </aside>

    <!-- MAIN -->
    <main class="main">

        <!-- HEADER -->
        <header class="header">

            <div>
                <h2>Bonjour <span id="userLogin"></span> 👋</h2>
                <p>Bienvenue sur Gusto Manager</p>
            </div>

            <div class="header-actions">

                <button class="notification-btn">
                    <i class="fa-solid fa-bell"></i>
                </button>

                <div class="avatar" id="profileBtn"></div>

            </div>

        </header>

        <!-- DASHBOARD -->
        <section id="dashboard" class="content-section">

        <div class="stats">

            <div class="card stat-card">
                <i class="fa-solid fa-utensils"></i>
                <h3>Services  journalier</h3>
                <h1 id="svc"></h1>
            </div>

            <div class="card stat-card">
                <i class="fa-solid fa-money-bill-wave"></i>
                <h3>Gains Journalier</h3>
                <h1 id="gain"></h1>
            </div>

            <div class="card stat-card">
                <i class="fa-solid fa-cart-shopping"></i>
                <h3>Commandes journalier</h3>
                <h1 id="cmd"></h1>
            </div>

        </div>

        <!-- CHARTS -->
        <div class="charts">

            <div class="card chart-card">
                <h3>Montants Mensuels</h3>
                <canvas id="barChart"></canvas>
            </div>

            <div class="card chart-card">
                <h3>Répartition des Revenus</h3>
                <canvas id="pieChart"></canvas>
            </div>

        </div>

    </section>

        <!-- TABLES -->
        <section id="tables" class="content-section" style="display:none">

            <div class="section-header">
                <h2>Gestion des Tables</h2>

                <button class="btn-primary btn-table">
                    <i class="fa-solid fa-plus"></i> Ajouter
                </button>
            </div>

            <table class="info-table">
                <thead>
                    <tr>
                        <th>Table</th>
                        <th>Etat</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>

        </section>

        <!-- CATEGORIE -->
        <section id="categories" class="content-section" style="display:none">
            <div class="section-header">
                <h2>Gestion des Catégories</h2>
                <button class="btn-primary btn-cat">
                    <i class="fa-solid fa-plus"></i> Ajouter
                </button>
            </div>

            <table class="info-cat">
                <thead>
                    <tr>
                        <th>Libelle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </section>


        <!-- PRODUITS -->
        <section id="produits" class="content-section" style="display:none">

            <div class="section-header">
                <h2>Produits</h2>

                <button class="btn-primary btn-produit">
                    <i class="fa-solid fa-plus"></i> Ajouter Produit
                </button>
            </div>

            <table class="info-produit">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>

        </section>

        <!-- EMPLOYES -->
        <section id="employes" class="content-section" style="display:none">

            <div class="section-header">
                <h2>Employés</h2>

                <button class="btn-primary btn-user">
                    <i class="fa-solid fa-plus"></i> Ajouter Employé
                </button>
            </div>

            <table class="info-user">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>login</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>

        </section>

        <section id="commandes" class="content-section" style="display:none">
            <div class="trie">
                <div class="date-group">
                    <label>Date début</label>
                    <input type="date" id="dateDebut">
                </div>

                <div class="date-group">
                    <label>Date fin</label>
                    <input type="date" id="dateFin">
                </div>
            </div>
            <div id="commandesContainer"></div>
        </section>

        <div class="modal fade modal-login" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='userLoginForm' class="php-form">
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="text" name="login" class="form-control" disabled id="modalLoginField">
                    </div>
                    <div class="col-lg-12">
                      <input type="password" name="password" placeholder="Entrer le mot de passe" class="form-control password">
                    </div>
                    <div class="col-md-12 text-center">
                      <button class="loading" type="submit"></button>
                    </div>
                  </div>
                </form>  
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade modal-table" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='table' class="php-form">
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="text" name="nom" placeholder="Entrer le nom de la table" class="form-control">
                    </div>
                    <input type="hidden" name="id">
                    <div class="col-md-12 text-center">
                      <button class="loading" type="submit"></button>
                    </div>
                  </div>
                </form>  
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade modal-service" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                <form>
                  <div class="row">
                    <div class="col-lg-6 code"></div>
                    <div class="col-lg-6 date_ouverture"></div>
                    <div class="col-lg-6 date_fermeture"></div>
                    <div class="col-lg-6 user"></div>
                    <input type="hidden" name="id">
                    <div class="col-md-12 text-center">
                      <button class="loading" type="submit"></button>
                    </div>
                  </div>
                </form>  
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade modal-produit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='produit' class="php-form">
                    <center class="mb-2">
                      <img id="image" src='' class="img-fluid mb-3">
                      <div class="cam"></div>
                      <label for="imgInp" class="btn-img">Entrer le logo</label>             
                      <input type="file" name="image" accept=".png, .jpg, .jpeg, .gif, .ico" id="imgInp">
                  </center>
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="text" name="nom" placeholder="Entrer le nom du produit" class="form-control">
                    </div>
                    <div class="col-lg-12">
                      <select name="id_categorie" class="select" required>
                        <option value="" disabled selected>&nbsp;&nbsp;&nbsp;Choisir la catégorie</option>
                      </select>
                    </div>
                    <div class="col-lg-12">
                      <input type="text" name="prix" placeholder="Entrer le prix" class="form-control">
                    </div>
                    <div class="col-lg-12">
                      <textarea name="description" class="form-control" rows="4" placeholder="Ecrivez quelques choses"></textarea>
                    </div>
                    <input type="hidden" name="id">
                    <div class="col-md-12 text-center">
                      <button class="loading" type="submit"></button>
                    </div>
                  </div>
                </form>  
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade modal-cat" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='categorie' class="php-form">
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="text" name="libelle" placeholder="Entrer le nom de la categorie" class="form-control">
                    </div>
                    <input type="hidden" name="id">
                    <div class="col-md-12 text-center">
                      <button class="loading" type="submit"></button>
                    </div>
                  </div>
                </form>  
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade modal-user" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='user' class="php-form">
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="text" name="nom" placeholder="Entrer le nom de l'employé" class="form-control">
                    </div>
                    <div class="col-lg-12">
                      <input type="text" name="login" placeholder="Entrer le login" class="form-control">
                    </div>
                    <div class="col-lg-12">
                      <input type="text" name="telephone" placeholder="Entrer le téléphone" class="form-control">
                    </div>
                    <div class="col-lg-12">
                      <input type="email" name="email" placeholder="Entrer l'email" class="form-control">
                    </div>
                    <div class="col-lg-12">
                      <input type="text" name="adresse" placeholder="Entrer l'adresse" class="form-control">
                    </div>
                    <div class="col-lg-12">
                        <select name="role" required>
                            <option value="" disabled selected>&nbsp;&nbsp;&nbsp;Choisir le rôle</option>
                            <option value="2">&nbsp;&nbsp;&nbsp;Serveur</option>
                            <option value="1">&nbsp;&nbsp;&nbsp;Gérant</option>
                        </select>
                    </div>
                    <input type="hidden" name="id">
                    <div class="col-md-12 text-center">
                      <button class="loading" type="submit"></button>
                    </div>
                  </div>
                </form>  
              </div>
            </div>
          </div>
        </div>



    </main>


    <script src="./assets/vendor/jquery/jquery.min.js"></script>
    <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/vendor/datatables/jquery.datatables.min.js"></script>
    <script src="./assets/vendor/datatables/datatables.bootstrap4.min.js"></script>
    <script src="./assets/vendor/datatables/datatables-demo.js"></script>
    <script src="./assets/vendor/custom-file-input/custom-file-input.js"></script>
    <script src="./assets/vendor/build/intlTelInput.js"></script>
    <script src="./assets/js/chart.js"></script>
    <script src="./assets/js/gerant.js"></script>

</body>

</html>