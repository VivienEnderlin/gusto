<?php
session_start();
require_once './../controller/bdFonctions.class.php';

$manageBd = new  bdFonctions();

$tbs = 'serveur';
$tbm = 'menu';
$tbc = 'commande';
$tbcat = 'categorie';
$tbuser = 'utilisateur';


?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gusto Galaxy - Découvrez nos décelis</title>
    <link href="<?=json_decode($_SESSION['logo'],true)['logo'][0]?>" class="logo icon" rel="icon">

    <!-- Custom fonts for this template-->
    <link href="./../assets/vendor/fontawesome-free-5.7.2-web/css/all.min.css" rel="stylesheet">
    <link href="./../assets/vendor/admin-2/sb-admin-2.min.css" rel="stylesheet">
    <link href="./../assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="./../assets/vendor/dataTables/datatables.bootstrap4.min.css" rel="stylesheet">
    <link href="./../assets/vendor/richtext/richtext.min.css" rel="stylesheet">
    <link href="./../assets/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  </head>

  <body data-idunique="<?= htmlspecialchars($_SESSION['idunique'] ?? '', ENT_QUOTES) ?>">

    <!-- Page Wrapper -->
    <div id="wrapper">

      <div class="navbar-mainbg">
        <div id="navbarSupportedContent">
          <ul class="navbar-nav sidebar sidebar-dark accordion">
            <div class="hori-selector"></div>
            <span class="sidebar-brand d-flex align-items-center justify-content-center mt-3 mb-3">
              <span class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon rotate-n-15">
                  <i style="font-size: 25px"><?=$_SESSION['nomutilisateur']?></i>
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

            <?php
              if ($_SESSION['idunique']=='cdfd5c84eebd1b5b6a8d7ca3ea717794bbefd31da004397f688fffa92c13aa11'){
                echo '<li class="nav-item link_page active">
                    <a class="nav-link" href="#" data-target="utilisateur">
                        <span>Gestion des utilisateurs</span>
                    </a>
                </li>';
                ?>
                <style>
                  #utilisateur{
                    display: block;
                  }
                </style>
                <?php
              }
              else{
                echo'
                <li class="nav-item link_page active">
                    <a class="nav-link" href="#" data-target="serveur">
                        <span>Gestion des serveurs</span>
                    </a>
                </li>

                <li class="nav-item link_page">
                    <a class="nav-link" href="#" data-target="menu">
                        <span>Gestion des menus</span>
                    </a>
                </li>

                <li class="nav-item link_page">
                    <a class="nav-link" href="#" data-target="commande">
                        <span>Gestion des commandes</span>
                    </a>
                </li>';
                ?>
                <style>
                  #serveur, .qrcode-btn{
                    display: block;
                  }
                </style>
                <?php
              }
            ?>

            

            <!-- Divider -->
            <hr class="sidebar-divider d-md-block">

            <span class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon">
                   <a class="nav-link text-white" href="./../controller/deconnexion.php"> <i class="fas fa-sign-out-alt" style="font-size: 23px;"></i> Sortir</a>
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
                  <img src="<?=json_decode($_SESSION['logo'],true)['logo'][0]?>" class="img-fluid logo rounded-circle" style="height: 40px; width: 40px;" alt="">
                </a>
              </li>
            </ul>
          </nav>
          <div class="d-flex justify-content-center">
              <a href="./../controller/qrcode.php" class="qrcode-btn btn btn-success">
                  Générer le QR code
              </a>
          </div>

            <!-- End of Topbar -->
          <div class="container-fluid content" id="serveur">
            <button class="btn-serveur mb-4">Ajouter un serveur</button>
            <div class="row"> 
              <div class="card shadow mb-4 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">LISTE DES SERVEURS</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable info-serveur" width="100%" cellspacing="0">
                      <thead class="text-light">
                        <tr>
                          <th>Nom</th>
                          <th>Login</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            $resp=$manageBd->getAll($tbs,"where idunique = '$_SESSION[idunique]'");
                            while ($data=$resp->fetch()) {
                              echo "<tr data-id=$data[idserveur]>
                                <td>$data[nom]</td> 
                                <td>$data[login]</td> 
                                <td>
                                  <a href='./../controller/editserveur.php?id=" . $data["idserveur"] ."' class='btn btn-warning editserveur icofont-pencil'></a>
                                </td>
                              </tr>";   
                            }
                            $resp->closeCursor();
                          ?> 
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> 
          </div>

          <div class="container-fluid content" id="menu">
            <button class="btn-menu mb-4">Ajouter un menu</button>
            <div class="row"> 
              <div class="card shadow mb-4 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">LISTE DES MENUS</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable info-menu" width="100%" cellspacing="0">
                      <thead class="text-light">
                        <tr>
                          <th>Libelle</th>
                          <th>Catégorie</th>
                          <th>Prix de vente</th>
                          <th>Description</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            $resp=$manageBd->getAll($tbm,"where idunique = '$_SESSION[idunique]'");
                            $totalMEnu = 0; // Somme totale
                            $categories = [
                                1 => 'Plat',
                                2 => 'Boisson',
                                3 => 'Dessert'
                            ];
                            while ($data=$resp->fetch()) {

                              $montantMEnu = (float)$data['prix'];
                              $totalMEnu += $montantMEnu;

                              $categorieNom = isset($categories[$data['idcategorie']]) ? $categories[$data['idcategorie']] : 'Inconnu';

                              echo "<tr data-id=$data[idmenu]>
                                <td>$data[libelle]</td> 
                                <td>$categorieNom</td>
                                <td>$data[prix]</td>
                                <td>$data[description]</td>
                                <td>
                                  <a href='./../controller/editmenu.php?id=" . $data["idmenu"] ."' class='btn btn-warning editmenu icofont-pencil'></a>
                                </td>
                              </tr>";   
                            }
                            $resp->closeCursor();
                          ?> 
                      </tbody>
                      <tfoot>
                            <tr>
                                <th colspan="2">Total :</th>
                                <th id="SumMenu"><?= number_format($totalMEnu, 2, ',', ' ') ?></th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div> 
          </div>

          <div class="container-fluid content" id="commande">
            <div class="row"> 
              <div class="card shadow mb-4 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">LISTE DES COMMANDES</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable info-commande" width="100%" cellspacing="0">
                        <thead class="text-light">
                          <tr>
                            <th>N° table</th>
                            <th>Commande</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Statu</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                              $resp = $manageBd->getAll($tbc, "where idunique = '$_SESSION[idunique]' order by idcommande DESC");
                              $totalCommande = 0; // Somme totale
                              while ($data = $resp->fetch()) {

                                $statu = $data['statu'];
                                if ($statu === 'En attente') {
                                $couleur = 'red';
                                } else{
                                    $couleur = 'green';
                                }

                                  // Décoder le JSON en tableau associatif
                                  $commandeItems = json_decode($data['commande'], true);

                                  // Construire la chaîne "libelle (xquantite) : total"
                                  $commandeStr = [];
                                  foreach ($commandeItems as $item) {
                                      $commandeStr[] = $item['libelle'] . " (x" . $item['quantite'] . ") : " . $item['total'];
                                  }
                                  $commandeStr = implode(", ", $commandeStr); // séparer par des virgules

                                  $montantCommande = (float)$data['montant_a_payer'];
                                  $totalCommande += $montantCommande;

                                  echo "<tr data-id='{$data['idcommande']}'>
                                          <td>{$data['idtable']}</td>
                                          <td>$commandeStr</td>
                                          <td>{$data['montant_a_payer']}</td>
                                          <td>{$data['datedujour']}</td>
                                          <td><span style='color:$couleur;' class='position'>$statu</span></td>
                                          <td>
                                            <a href='./../controller/viewcommande.php?id={$data['idcommande']}' class='btn btn-warning viewcommande icofont-eye'></a>
                                          </td>
                                        </tr>";   
                              }
                              $resp->closeCursor();
                          ?>   
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total :</th>
                                <th id="SumCommande"><?= number_format($totalCommande, 2, ',', ' ') ?></th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
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
                          <th>Logo</th>
                          <th>Utilisateur</th>
                          <th>Adresse</th>
                          <th>Telephone</th>
                          <th>Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            $resp=$manageBd->getAll($tbuser,"");
                            while ($data=$resp->fetch()) {
                              echo "<tr data-id=$data[idutilisateur]>
                              <td><img src=".json_decode($data['logo'],true)['logo'][0]." class='logo'></td>
                                <td>$data[nomutilisateur]</td> 
                                <td>$data[adresse]</td> 
                                <td>$data[telephone]</td> 
                                <td>$data[dateenreg]</td>
                                <td>
                                  <a href='./../controller/edituser.php?id=" . $data["idutilisateur"] ."' class='btn btn-warning edituser icofont-pencil'></a>
                                </td>
                              </tr>";   
                            }
                            $resp->closeCursor();
                          ?> 
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


    <div class="modal fade modal-s" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='server' class="php-form">
              <div class="row">
                <div class="col-lg-12">
                  <input type="text" name="nom" class="form-control nom" placeholder="Entrez le nom du serveur" required>
                </div>
                <div class="col-lg-12">
                  <input type="text" name="login" class="form-control login" placeholder="Creer son login" required>
                </div>
                <input type="hidden" name="idserveur" class="id">
                <div class="col-md-12 text-center">
                  <button class="loading" type="submit"></button>
                </div>
              </div>
            </form>  
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade modal-m" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='plat' class="php-form">
              <center>
                <img id="blah" src='' class="img-fluid mb-3">
                <div class="cam"></div>
                <label for="imgInp" class="btn-img">Choisir une image</label>             
                <input type="file" name="image[]" accept=".png, .jpg, .jpeg, .gif, .ico" id="imgInp">
              </center>
              <div class="row">
                <div class="col-lg-6">
                  <input type="text" name="libelle" class="form-control libelle" placeholder="Entrez le nom du produit" required>
                </div>

                <div class="col-lg-6">
                  <input type="number" name="prix" class="form-control prix" placeholder="Entrez le prix de vente" required>
                </div>

                <div class="col-lg-12 mb-4">
                  <select name="idcategorie" class="categorie bg-white w-100 h-100" required="">
                    <option value="" disabled selected>Choisir la categorie</option>
                    <?php
                    $resp=$manageBd->getAll($tbcat,"");
                    while ($cat=$resp->fetch()) {
                      echo '<option value="'.$cat['idcategorie'].'">'.$cat['libelle'].'</option>';
                    }
                    $resp->closeCursor();
                    ?>
                  </select>
                </div>

                <div class="col-lg-12">
                  <textarea name="description" class="form-control description" rows="4" placeholder="Ecrivez quelques choses"></textarea>
                </div>
                <input type="hidden" name="idmenu" class="id">
                <div class="col-md-12 text-center">
                  <button class="loading" type="submit"></button>
                </div>
              </div>
            </form>  
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade modal-f" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body" id="facture">
            <table class="table">
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Qte</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="fichePanier"></tbody>
            </table>
            <hr>
            <div class="d-flex justify-content-between">
              <input type="hidden" name="idcommande" class="id">
                <h5 class="TG font-weight-bold">Total général :</h5>
                <h5 class="montantFinal font-weight-bold">0 FCFA</h5>
            </div>
          </div>
          <div>
              <button type="submit" class="loading btn btn-warning text-dark mb-3 mr-3 float-right" id="btn-print">Imprimer la facture</button>
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
              <center>
                <img id="logo" src='' class="img-fluid mb-3">
                <div class="cam"></div>
                <label for="imgLogo" class="btn-img">Entrer le logo</label>             
                <input type="file" name="logo[]" accept=".png, .jpg, .jpeg, .gif, .ico" id="imgLogo">
              </center>
              <div class="row">
                <div class="col-lg-6">
                  <input type="text" name="nomutilisateur" class="form-control etablisement" placeholder="Entrez le nom de l'établissement">
                </div>

                <div class="col-lg-6">
                  <input type="text" name="adresse" class="form-control adresse" placeholder="Entrez le l'adresse">
                </div>

                <div class="col-lg-6">
                  <input type="text" name="nomproprietaire" class="form-control proprietaire" placeholder="Entrez le nom du proprietaire">
                </div>

                <div class="col-lg-6">
                  <input type="text" name="telephone" class="form-control telephone" placeholder="Entrez le téléphone">
                </div>

                <div class="col-lg-12">
                  <input type="text" name="email" class="form-control email" placeholder="Entrez l'email">
                </div>

                <div class="col-lg-12">
                  <textarea name="description" class="form-control description" rows="4" placeholder="Ecrivez quelques choses"></textarea>
                </div>
                <input type="hidden" name="idutilisateur" class="id">
                <input type="hidden" name="idunique" class="idunique">
                <div class="col-md-12 text-center">
                  <button class="loading" type="submit"></button>
                </div>
              </div>
            </form>  
          </div>
        </div>
      </div>
    </div>

    <script src="./../assets/vendor/jquery/jquery.min.js"></script>
    <script src="./../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./../assets/vendor/admin-2/sb-admin-2.min.js"></script>
    <script src="./../assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="./../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="./../assets/vendor/datatables/datatables-demo.js"></script>
    <script src="./../assets/vendor/richtext/jquery.richtext.js"></script>
    <script src="./../assets/vendor/custom-file-input/custom-file-input.js"></script>

    <script>
    $('#imgInp, #imgLogo').on('change', function (e) {
        const file = this.files[0];
        if (file) {
            $('#blah, #logo').attr('src', URL.createObjectURL(file));
        }
    });
  </script>

  <script src="./../assets/js/admin.js"></script>
  </body>
</html>