<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gusto Galaxy - Découvrez nos décelis</title>
    <link href="./assets/img/gusto.ico" class="logo icon" rel="icon">

    <!-- Custom fonts for this template-->
    <link href="./assets/vendor/fontawesome-free-5.7.2-web/css/all.min.css" rel="stylesheet">
    <link href="./assets/vendor/admin-2/sb-admin-2.min.css" rel="stylesheet">
    <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="./assets/vendor/dataTables/datatables.bootstrap4.min.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  </head>

  <body>

    <!-- Page Wrapper -->
    <div id="wrapper">

      <div class="navbar-mainbg">
        <div id="navbarSupportedContent">
          <ul class="navbar-nav sidebar sidebar-dark accordion">
            <div class="hori-selector"></div>
            <span class="sidebar-brand d-flex align-items-center justify-content-center mt-3 mb-3">
              <span class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon rotate-n-15">
                  <i style="font-size: 25px" id="userLogin"></i>
                </div>
                <div class="sidebar-brand-text mx-3"></div>
              </span>
            </span>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Heading -->
            <div class="sidebar-heading">
                <!-- nom de l'entreprise -->
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider">

              <li class="nav-item link_page active">
                  <a class="nav-link" href="#" data-target="etablissement">
                      <span>Gestion des etablissements</span>
                  </a>
              </li>

               <li class="nav-item link_page">
                    <a class="nav-link" href="#" data-target="utilisateur">
                        <span>Gestion des utilisateurs</span>
                    </a>
                </li>

                <li class="nav-item link_page">
                    <a class="nav-link" href="#" data-target="appareil">
                        <span>Gestion des appareils</span>
                    </a>
                </li>

                <li class="nav-item link_page">
                    <a class="nav-link" href="#" data-target="licence">
                        <span>Gestion des licences</span>
                    </a>
                </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-md-block">

            <span class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon">
                   <a href="#" class="nav-link text-white" id="logoutBtn"> <i class="fas fa-sign-out-alt" style="font-size: 23px;"></i> Sortir</a>
                </div>
            </span>
          </ul>
        </div>
      </div>

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

          <!-- Topbar -->
          <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        
            <div class="notification alert alert-info" style="display: none;"></div>

            <div id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
            </div>
            <ul class="navbar-nav ml-auto">
              <div class="topbar-divider d-sm-block"></div>
              <li class="nav-item">
                <a class="nav-link" href="#" aria-haspopup="true" aria-expanded="false">
                  <img src="" class="img-fluid logo rounded-circle" style="height: 40px; width: 40px;" alt="">
                </a>
              </li>
            </ul>
          </nav>
          <div class="d-flex justify-content-center">
              <a href="./controller/qrcode.php" class="qrcode-btn btn btn-success">
                  Générer le QR code
              </a>
          </div>

            <!-- End of Topbar -->

          <div class="container-fluid content" id="etablissement">
            <button class="btn-ets mb-4">Ajouter un etablissement</button>
            <div class="row"> 
              <div class="card shadow mb-4 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">LISTE DES ETABLISSEMENTS</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable info-ets" width="100%" cellspacing="0">
                      <thead class="text-light">
                        <tr>
                          <th>Logo</th>
                          <th>Nom</th>
                          <th>Type</th>
                          <th>Adresse</th>
                          <th>Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            require_once './../api-commande/models/Etablissement.php';
                            $etablissementModel = new Etablissement();
                            $etablissements = $etablissementModel->getAllEtablissements(); // récupère tous les établissements

                            foreach($etablissements as $e) {
                                $logos = json_decode($e['logo'], true); // logo stocké en JSON
                                $logoHTML = '';
                                if(!empty($logos)) {
                                    foreach($logos as $l) {
                                        $logoHTML .= "<img src='$l' width='50'> ";
                                    }
                                }

                                echo "<tr>
                                        <td>$logoHTML</td>
                                        <td>{$e['nom']}</td>
                                        <td>{$e['type']}</td>
                                        <td>{$e['adresse']}</td>
                                        <td>{$e['dateenreg']}</td>
                                        <td>
                                            <button class='btn btn-sm btn-primary edit-btn' data-id='{$e['id_etablissement']}'>Modifier</button>
                                            <button class='btn btn-sm btn-success change-btn' data-id='{$e['id_etablissement']}'>Débloquer</button>
                                            <button class='btn btn-sm btn-danger delete-btn' data-id='{$e['id_etablissement']}'>Bloquer</button>
                                        </td>
                                      </tr>";
                            }
                          ?>
                       
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> 
          </div>

          <div class="container-fluid content" id="utilisateur">
            <button class="btn-user mb-4">Ajouter un utilisateur</button>
            <div class="row"> 
              <div class="card shadow mb-4 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">LISTE DES UTILISATEURS</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable info-user" width="100%" cellspacing="0">
                      <thead class="text-light">
                        <tr>
                          <th>Nom</th>
                          <th>Téléphone</th>
                          <th>Etablissement</th>
                          <th>Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                       
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> 
          </div>

          <div class="container-fluid content" id="appareil">
            <button class="btn-app mb-4">Ajouter un appareil</button>
            <div class="row"> 
              <div class="card shadow mb-4 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">LISTE DES APPAREILS</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable info-app" width="100%" cellspacing="0">
                        <thead class="text-light">
                          <tr>
                            <th>Model</th>
                            <th>N° serie</th>
                            <th>Système</th>
                            <th>Etablissement</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                      </table>
                  </div>
                </div>
              </div>
            </div> 
          </div>

          <div class="container-fluid content" id="licence">
            <button class="btn-licence mb-4">Ajouter une licence</button>
            <div class="row"> 
              <div class="card shadow mb-4 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">LISTE DES LICENCES</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable info-licence" width="100%" cellspacing="0">
                      <thead class="text-light">
                        <tr>
                          <th>Model</th>
                          <th>Etablissement</th>
                          <th>Code licence</th>
                          <th>Date de validité</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                       
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> 
          </div>

        </div>
      </div>
    </div>


    <div class="modal fade modal-ets" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='ets' class="php-form">
              <center>
                  <img id="logo" src='' class="img-fluid mb-3">
                  <div class="cam"></div>
                  <label for="imgInp" class="btn-img">Entrer le logo</label>             
                  <input type="file" name="logo" accept=".png, .jpg, .jpeg, .gif, .ico" id="imgInp">
                </center>
              <div class="row">
                <div class="col-lg-6">
                  <input type="text" name="nom" class="form-control nom" placeholder="Nom de l'etablissement" required>
                </div>
                <div class="col-lg-6">
                  <input type="text" name="type" class="form-control type" placeholder="Type d'etablissement" required>
                </div>
                <div class="col-lg-12">
                  <input type="text" name="adresse" class="form-control adresse" placeholder="Adresse de l'etablissement" required>
                </div>
                <div class="col-lg-6">
                  <input type="email" name="email" class="form-control email" placeholder="Email de l'établissement">
                </div>
                <div class="col-lg-6">
                  <input type="tel" name="telephone" class="form-control telephone" placeholder="Téléphone de l'établissement">
                </div>
                <div class="col-lg-12">
                  <input type="url" name="site_web" class="form-control site_web" placeholder="Site web de l'établissement">
                </div>
                <div class="col-lg-12">
                  <textarea name="description" class="form-control description" rows="4" placeholder="Ecrivez quelques choses"></textarea>
                </div>
                <input type="hidden" name="id" class="ets-id">
                <div class="col-md-12 text-center">
                  <button class="loading" type="submit"></button>
                </div>
              </div>
            </form>  
          </div>
        </div>
      </div>
    </div>

   
    <script src="./assets/vendor/jquery/jquery.min.js"></script>
    <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/vendor/admin-2/sb-admin-2.min.js"></script>
    <script src="./assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="./assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="./assets/vendor/datatables/datatables-demo.js"></script>
    <script src="./assets/vendor/custom-file-input/custom-file-input.js"></script>

    <script>
    // Fonction pour décoder un JWT côté client
        function parseJwt(token) {
            try {
                // Extraire la partie "payload" du token (la deuxième section après les points)
                const base64Url = token.split('.')[1];

                // Remplacer les caractères spécifiques URL-safe par des caractères base64 standard
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');

                // Décoder la chaîne base64 en JSON
                const jsonPayload = decodeURIComponent(
                    atob(base64) // atob décode la chaîne base64 en texte
                    .split('')    // transformer chaque caractère en tableau
                    .map(function(c) { 
                        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2); 
                    })
                    .join('')     // reconstituer la chaîne encodée pour decodeURIComponent
                );

                // Convertir la chaîne JSON en objet JavaScript
                return JSON.parse(jsonPayload);
            } catch(e) {
                // En cas d'erreur (token malformé ou absent), retourner null
                return null;
            }
        }

        // Récupérer le token stocké dans localStorage
        const token = localStorage.getItem('token');

        if (token) {
            // Décoder le token pour obtenir le payload
            const payload = parseJwt(token);

            // Vérifier si le payload contient la clé 'user' (ou 'login') et l'afficher dans l'UI
            if (payload && payload.user) {
                document.getElementById('userLogin').textContent = payload.user;
            } else {
                // Si le token est présent mais malformé ou sans user, afficher "Invité"
                document.getElementById('userLogin').textContent = 'admin';
            }
        } else {
            // Si aucun token, afficher "Invité"
             window.location.href = './login.php';
        }
    </script>

    <script>
      document.getElementById('logoutBtn').addEventListener('click', function(e) {
          e.preventDefault();
          // Supprimer le token du localStorage
          localStorage.removeItem('token');
          // Rediriger vers la page de login
          window.location.href = './login.php';
      });
   </script>

   <script>
    imgInp.onchange = evt=>{
      const [file] = imgInp.files
      if (file) {
        logo.src = URL.createObjectURL(file)
      }
    }
  </script>

  <script src="./assets/js/admin.js"></script>
  </body>
</html>