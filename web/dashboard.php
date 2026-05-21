<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Gusto</title>
    <link href="./assets/img/gusto.png" class="logo icon"  type="image/png" rel="icon">

    <!-- Custom fonts for this template-->
    <link href="./assets/vendor/fontawesome-free-5.7.2-web/css/all.min.css" rel="stylesheet">
    <link href="./assets/vendor/admin-2/sb-admin-2.min.css" rel="stylesheet">
    <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="./assets/vendor/datatables/datatables.bootstrap4.min.css" rel="stylesheet">
    <link href="./assets/vendor/virtual-select/virtual-select.min.css" rel="stylesheet">
    <link href="./assets/vendor/build/intlTelInput.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <style>
      /* =========================
   STYLE GUSTO MODERNE
========================= */

:root{
  --design-color:#ff922b;
  --card-color:#111c31;
  --accent-color:#ff7a00;
  --white-color:#ffffff;
  --default-color:#6b7280;
}

#utilisateur, #appareil, #licence{
  display: none;
}

.navbar-mainbg{
  background: var(--card-color);
  border-radius: 0 30px 30px 0;
  box-shadow: 0 10px 40px rgba(0,0,0,.08);
  min-height: 100vh;
  padding-top: 10px;
}

#navbarSupportedContent{
  overflow:hidden;
  position:relative;
}

#navbarSupportedContent ul{
  padding:0;
  margin:0;
}


#navbarSupportedContent ul li a i{
    margin-right: 10px;
}


#navbarSupportedContent ul li a{
    color: var(--white-color);
    text-decoration: none;
    font-size: 17px;
    display: block;
    padding: 20px 20px;
    transition-duration:0.6s;
    transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    position: relative;
}

#navbarSupportedContent ul li a:hover{
  color:var(--design-color);
  transform:translateX(4px);
}

#navbarSupportedContent>ul>li.active>a{
  background-color: transparent;
  color:white;
  box-shadow:0 10px 25px rgba(255,122,0,.25);
}

#navbarSupportedContent a:not(:only-child):after {
    content: "\f105";
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 14px;
    display: inline-block;
    padding-right: 3px;
    vertical-align: middle;
    font-weight: 900;
    transition: 0.5s;
}

#navbarSupportedContent .active>a:not(:only-child):after {
    transform: rotate(90deg);
}

.hori-selector{
    display:inline-block;
    position:absolute;
    height: 100%;
    top: 0px;
    left: 0px;
    transition-duration:0.6s;
    transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    background:linear-gradient(135deg,#ff7a00,#ff922b);
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
}


.sidebar-dark .sidebar-brand{
  color:var(--white-color);
}

.sidebar-brand-text{
  font-weight:800;
  font-size:20px;
}


.sidebar-divider{
  border: none;
  background: var(--white-color);
  margin: 14px 20px;
  display: block;
  opacity: 1;
}

.topbar{
  background:var(--card-color);
  border-radius:20px;
  margin:20px;
  box-shadow:0 10px 30px rgba(0,0,0,.05);
  padding:10px 15px;
}

.topbar #sidebarToggleTop{
  color:var(--accent-color);
}

.topbar #sidebarToggleTop:hover{
  background:#fff4eb;
  color:var(--accent-color);
}

/* =========================
   CONTENT
========================= */

.container-fluid{
  padding:20px;
}

/* =========================
   BUTTONS
========================= */

.btn-user,
.btn-ets,
.btn-app{
  background:linear-gradient(135deg,#ff7a00,#ff922b);
  border:none;
  color:white;
  border-radius:14px;
  padding:12px 24px;
  font-size:14px;
  font-weight:700;
  transition:.3s ease;
  box-shadow:0 10px 20px rgba(255,122,0,.18);
}

.btn-user:hover,
.btn-ets:hover,
.btn-app:hover{
  transform:translateY(-2px);
  box-shadow:0 15px 25px rgba(255,122,0,.25);
}

/* =========================
   CARDS
========================= */

.card{
  border:none;
  border-radius:24px;
  overflow:hidden;
  background:var(--white-color);
  box-shadow:0 15px 40px rgba(0,0,0,.06);
}

.card-header{
  background:transparent;
  border-bottom:1px solid #f1f5f9;
  padding:22px;
}

.card-header h6{
  color:#111827;
  font-size:16px;
  font-weight:800;
  margin:0;
}

.card-body{
  padding:25px;
}

/* =========================
   TABLE
========================= */

.table{
  border-collapse:separate;
  border-spacing:0 10px;
}

.table thead th{
  background:linear-gradient(135deg,#ff7a00,#ff922b);
  color:white;
  border:none;
  padding:15px;
  font-size:14px;
  font-weight:700;
}

.table thead th:first-child{
  border-radius:14px 0 0 14px;
}

.table thead th:last-child{
  border-radius:0 14px 14px 0;
}

.table tbody tr{
  background:white;
  box-shadow:0 5px 15px rgba(0,0,0,.04);
}

.table tbody td{
  padding:16px;
  border-top:none !important;
  vertical-align:middle;
}

/* =========================
   PAGINATION
========================= */

.page-item.active .page-link{
  background:var(--accent-color);
  border-color:var(--accent-color);
  border-radius:10px;
}

.page-link{
  border:none;
  margin:0 4px;
  border-radius:10px;
  color:#374151;
}

#prev-page, #next-page {
  background: var(--accent-color);
  color: #fff;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 0.7rem 2rem;
}

/* =========================
   MODALS
========================= */

.modal-content{
  border:none;
  border-radius:28px;
  overflow:hidden;
  box-shadow:0 25px 60px rgba(0,0,0,.15);
}

.modal-header{
  border:none;
  padding:25px 30px 10px;
}

.modal-title{
  font-weight:800;
  color:#111827;
}

.modal-body{
  padding:20px 30px 35px;
}

/* =========================
   FORMS
========================= */

.modal .php-form input[type=text],
.modal .php-form input[type=date],
.modal .php-form input[type=number],
.modal .php-form input[type=email],
.modal .php-form input[type=url],
.modal .php-form input[type=tel],
.modal .php-form textarea,
.modal .php-form input[type=password],
select{
  width:100%;
  border:1px solid #e5e7eb;
  border-radius:16px;
  font-size:14px;
  margin:10px 0;
  transition:.3s;
  background:#f9fafb;

}

.select{
  height: 90%;
}

.modal .php-form textarea{
  min-height:120px;
  resize:none;
}

.modal .php-form input:focus,
.modal .php-form textarea:focus,
select:focus{
  border-color:var(--accent-color);
  background:white;
  box-shadow:0 0 0 4px rgba(255,122,0,.12);
  outline:none;
}

/* =========================
   SUBMIT BUTTON
========================= */

.modal .php-form button[type=submit]{
  background:linear-gradient(135deg,#ff7a00,#ff922b);
  color:white;
  border:none;
  border-radius:50px;
  padding:13px 35px;
  font-weight:700;
  transition:.3s ease;
  margin-top:15px;
  box-shadow:0 10px 25px rgba(255,122,0,.2);
}

.modal .php-form button[type=submit]:hover{
  transform:translateY(-3px);
}

/* =========================
   LOGO
========================= */

#logo{
  height:100px;
  width:100px;
  border-radius:24px;
  border:3px solid #fff;
  box-shadow:0 10px 25px rgba(0,0,0,.1);
  object-fit:cover;
}

/* =========================
   IMAGE BUTTON
========================= */

.btn-img{
  background:linear-gradient(135deg,#ff7a00,#ff922b);
  color:white;
  border:none;
  border-radius:12px;
  padding:8px 10px;
  font-size:12px;
  font-weight:700;
  cursor:pointer;
  transition:.3s;
}

#imgInp, #imgLogo{
  display: none;
}

.btn-img:hover{
  transform:translateY(-2px);
}

/* =========================
   STATUS COLORS
========================= */

.statu-attente{
  color:#9ca3af;
  font-weight:700;
}

.statu-valide{
  color:#22c55e;
  font-weight:700;
}

.statu-expire{
  color:#ef4444;
  font-weight:700;
}

/* =========================
   LOADER
========================= */

button.loading::after{
  content:"";
  display:inline-block;
  border-radius:50%;
  width:22px;
  height:22px;
  margin:0 10px -5px 0;
  float:left;
  border:3px solid rgba(255,255,255,.4);
  border-top-color:white;
  animation:animate-loading 1s linear infinite;
  display:none;
}

button.loading.show-loader::after{
  display:block;
}

@keyframes animate-loading{
  to{
    transform:rotate(360deg);
  }
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:768px){

  .navbar-mainbg{
    border-radius:0;
  }

  .topbar{
    margin:10px;
  }

  .card{
    border-radius:18px;
  }

  .table-responsive{
    overflow-x:auto;
  }

}
    </style>
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
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading text-white text-center" style="font-size: 25px;">
                GUSTO
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
                        <span>Gestion des clients</span>
                    </a>
                </li>

                <li class="nav-item link_page">
                    <a class="nav-link" href="#" data-target="appareil">
                        <span>Gestion des appareils</span>
                    </a>
                </li>

                <li class="nav-item link_page">
                    <a class="nav-link" href="#" data-target="licence">
                        <span>Gestion des contrats</span>
                    </a>
                </li>

            <!-- Divider -->
            <hr class="sidebar-divider mt-3">

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
          <nav class="navbar navbar-expand navbar-light topbar static-top shadow">
        
            <div id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
            </div>
            <ul class="navbar-nav ml-auto">
              <div class="topbar-divider d-sm-block"></div>
              <li class="nav-item">
                <a class="nav-link" href="#" aria-haspopup="true" aria-expanded="false">
                  <img src="./assets/img/avatar.png" class="img-fluid logo rounded-circle userLogin" style="height: 40px; width: 40px;" alt="">
                </a>
              </li>
            </ul>
          </nav>

            <!-- End of Topbar -->

          <div class="container-fluid content" id="etablissement">
            <button class="btn-ets mb-4">Ajouter un établissement</button>
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
                      <tbody id="tbodyEtablissements"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> 
          </div>

          <div class="container-fluid content" id="utilisateur">
            <button class="btn-user mb-4">Ajouter un client</button>
            <div class="row"> 
              <div class="card shadow mb-4 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">LISTE DES CLIENTS</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable info-user" width="100%" cellspacing="0">
                      <thead class="text-light">
                        <tr>
                          <th>Nom</th>
                          <th>Adresse</th>
                          <th>Téléphone</th>
                          <th>role</th>
                          <th>Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="tbodyUsers"></tbody>
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
                            <th>Marque</th>
                            <th>Model</th>
                            <th>N° serie</th>
                            <th>Système</th>              
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="tbodyAppareils"></tbody>
                      </table>
                  </div>
                </div>
              </div>
            </div> 
          </div>

          <div class="container-fluid content" id="licence">
            <div class="row"> 
              <div class="card shadow mb-4 col-lg-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">LISTE DES CONTRATS</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered dataTable info-licence" width="100%" cellspacing="0">
                      <thead class="text-light">
                        <tr>
                          <th>Code licence</th>
                          <th>Date de validité</th>
                          <th>statu</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="tbodyContrats"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> 
          </div>

        </div>
      </div>
    </div>

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
                  <input type="text" name="login" class="form-control" id="modalLoginField">
                </div>
                <div class="col-lg-12">
                  <input type="password" name="password" class="form-control password">
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
                  <input type="text" name="nom" class="form-control" placeholder="Nom de l'etablissement" required>
                </div>
                <div class="col-lg-6">
                  <input type="text" name="type" class="form-control" placeholder="Type d'etablissement" required>
                </div>
                <div class="col-lg-6">
                  <input type="text" name="pays" class="form-control" placeholder="pays" required>
                </div>
                <div class="col-lg-6 mb-4">
                  <select name="devise" class="select w-100"  required>
                    <option value="" disabled selected>&nbsp;&nbsp;&nbsp;Choisir la monnaie</option>
                    <option value="FCFA">&nbsp;&nbsp;&nbsp;FCFA</option>
                    <option value="€">&nbsp;&nbsp;&nbsp;€</option>
                    <option value="$">&nbsp;&nbsp;&nbsp;$</option>
                  </select>
                </div>
                <div class="col-lg-6">
                  <input type="text" name="ville" class="form-control" placeholder="ville" required>
                </div>
                <div class="col-lg-6">
                  <input type="text" name="adresse" class="form-control" placeholder="Adresse de l'etablissement" required>
                </div>
                <div class="col-lg-6">
                  <input type="email" name="email" class="form-control" placeholder="Email de l'établissement">
                </div>
                <div class="col-lg-6" style="margin-top: 10px;">
                  <input type="tel" name="telephone" id="phone" class="form-control" value="+237 " placeholder="Téléphone de l'établissement">
                  <input type="hidden" name="country" id="country">
                </div>
                <div class="col-lg-12">
                  <input type="url" name="site_web" class="form-control" placeholder="Site web de l'établissement">
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
                  <input type="text" name="nom" class="form-control" placeholder="Nom de l'utilisateur" required>
                </div>
                <div class="col-lg-6">
                  <input type="text" name="adresse" class="form-control" placeholder="Adresse de l'utilisateur" required>
                </div>
                <div class="col-lg-6">
                  <input type="email" name="email" class="form-control" placeholder="Email de l'utilisateur" required>
                </div>
                <div class="col-lg-6">
                  <input type="tel" name="telephone" class="form-control" placeholder="Téléphone de l'utilisateur" required>
                </div>
                <div class="col-lg-6">
                  <input type="text" name="login" class="form-control" placeholder="Entrez le login de l'utilisateur" required>
                </div>
                <div class="col-lg-6 mb-4">
                  <select name="role" class="select w-100"  required>
                    <option value="" disabled selected>&nbsp;&nbsp;&nbsp;Choisir le rôle</option>
                    <option value="0">&nbsp;&nbsp;&nbsp;Admin</option>
                    <option value="1">&nbsp;&nbsp;&nbsp;Gérant</option>
                  </select>
                </div>
                <div class="col-lg-6 mb-4">
                  <select name="id_etablissement" class="select  w-100 selectEtablissement" required>
                    <option value="" disabled selected>&nbsp;&nbsp;&nbsp;Choisir l'etablissement géré</option>
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

    <div class="modal fade modal-app" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='dispositif' class="php-form">
              <div class="row">
                <div class="col-lg-6">
                  <input type="text" name="marque" class="form-control" placeholder="Marque de l'appareil" required>
                </div>
                <div class="col-lg-6">
                  <input type="text" name="model" class="form-control" placeholder="Model de l'appareil" required>
                </div>
                <div class="col-lg-12">
                  <input type="text" name="numero_serie" class="form-control" placeholder="Numero de serie de l'appareil">
                </div>
                <div class="col-lg-6">
                  <input type="text" name="systeme_exploitation" class="form-control" placeholder="Système de l'appareil">
                </div>
                <div class="col-lg-6">
                  <input type="text" name="annee_fabrication" class="form-control" placeholder="Année de fabrication">
                </div>
                <div class="col-lg-12">
                  <small class="ml-3">Date de fin de support</small>
                  <input type="date" class="form-control" name="date_fin_support">
               </div>
                <div class="col-lg-12 mb-4">
                  <select name="id_etablissement" class="select  w-100 selectEtablissement" required>
                    <option value="" disabled selected>&nbsp;&nbsp;&nbsp;Choisir l'etablissement destinataire</option>
                  </select>
                </div>
                <textarea name="description" class="form-control" rows="4" placeholder="Ecrivez quelques choses"></textarea>
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

    <div class="modal fade modal-licence" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='contrat' class="php-form">
              <div class="row">
                <div class="col-lg-12 mb-4">
                  <select name="id_etablissement" class="select  w-100 selectEtablissement" required>
                    <option value="" disabled selected>&nbsp;&nbsp;&nbsp;Choisir l'etablissement destinataire</option>
                  </select>
                </div>
                <div class="col-lg-12">
                  <small class="ml-3">Date d'expiration</small>
                  <input type="date" name="date_validite" class="form-control" required>
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


    <script src="./assets/vendor/jquery/jquery.min.js"></script>
    <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/vendor/admin-2/sb-admin-2.min.js"></script>
    <script src="./assets/vendor/datatables/jquery.datatables.min.js"></script>
    <script src="./assets/vendor/datatables/datatables.bootstrap4.min.js"></script>
    <script src="./assets/vendor/datatables/datatables-demo.js"></script>
    <script src="./assets/vendor/custom-file-input/custom-file-input.js"></script>
    <script src="./assets/vendor/build/intlTelInput.js"></script>

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

        const headers = {
            'Content-Type': 'application/json',
            'authorization': 'Bearer ' + token
        };

        if (token) {
          const payload = parseJwt(token);

          if (payload && payload.data && payload.data.login) {
              document.getElementById('userLogin').textContent = payload.data.login;
          } else {
              document.getElementById('userLogin').textContent = 'Invité';
          }
        } else {
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

      // ================= Fetch API pour peupler les tables =================

      const selectEts = document.querySelectorAll('.selectEtablissement');

      fetch('/api-commande/routes/etablissement.php', { headers })
      .then(res => res.json())
      .then(response => {
        if (response.success && Array.isArray(response.data)) {
          // ⚡ Ici on utilise ton DataTable déjà initialisé
          response.data.forEach(row => {
            selectEts.forEach(select => {
                const option = document.createElement('option');
                option.value = row[5]; // id_etablissement
                option.textContent = "\u00A0\u00A0\u00A0" + row[1]; // nom
                select.appendChild(option);
            });
            ets.row.add([
                row[0], // Logo
                row[1], // Nom
                row[2], // Type
                row[3], // Adresse
                row[4], // Date
                row[6], //Action
            ]).draw(false);
          });
        } else {
          console.error('Pas de données ou format inattendu', response);
        }
      })
      .catch(err => console.error(err));

      // Utilisateurs
      fetch('/api-commande/routes/utilisateur.php', { headers })
        .then(res => res.json())
        .then(response => {
          if (response.success && Array.isArray(response.data)) {
            response.data.forEach(row => {
                user.row.add([
                    row[0], 
                    row[1], 
                    row[2], 
                    row[3], 
                    row[4], 
                    row[5], 
                ]).draw(false);
            });
            } else {
            console.error('Pas de données ou format inattendu', response);
          }
        })
        .catch(err => console.error(err));

      // Appareils
      fetch('/api-commande/routes/appareil.php', { headers })
        .then(res => res.json())
        .then(response => {
          if (response.success && Array.isArray(response.data)) {
            response.data.forEach(row => {
                app.row.add([
                    row[0], 
                    row[1], 
                    row[2], 
                    row[3], 
                    row[4], 
                ]).draw(false);
            });
            } else {
            console.error('Pas de données ou format inattendu', response);
          }
        })
        .catch(err => console.error(err));

      // Contrats
      fetch('/api-commande/routes/contrat.php', { headers })
        .then(res => res.json())
        .then(response => {
          if (response.success && Array.isArray(response.data)) {
            response.data.forEach(row => {
                licence.row.add([
                    row[0], 
                    row[1], 
                    row[2], 
                    row[3], 
                ]).draw(false);
            });
            } else {
            console.error('Pas de données ou format inattendu', response);
          }
        })
        .catch(err => console.error(err));
   </script>

   <script>
    imgInp.onchange = evt=>{
      const [file] = imgInp.files
      if (file) {
        logo.src = URL.createObjectURL(file)
      }
    }

    var input = document.querySelector("#phone");
    const iti =window.intlTelInput(input, {
      utilsScript: "./assets/vendor/build/utils.js",
    });
  </script>

  <script src="./assets/js/admin.js"></script>
  </body>
</html>