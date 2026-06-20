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
        <style>
            :root {
  --default-font: "Roboto",  system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
  --heading-font: "Raleway",  sans-serif;
  --nav-font: "Nunito",  sans-serif;
}

/* Global Colors - The following color variables are used throughout the website. Updating them here will change the color scheme of the entire website */
:root { 
  --background-color: #ffffff; /* Background color for the entire website, including individual sections */
  --accent-color: #d97706; /* Accent color that represents your brand on the website. It's used for buttons, links, and other elements that need to stand out */
  --default-color: #000;
  --design-color: #fef3c7;
  --hover-color: #7b4105;
  --body-color: #f5f6fa;
  --nav-dropdown-color: #444444;
}

/* ============================
   Base
============================ */
* {
    /*font-family: 'Poppins', sans-serif;*/
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
    margin: 0;
    padding: 0;
}

.body {
  background:var(--body-color);
}

a{
  text-decoration: none !important;
  color: var(--accent-color);
}

a:hover{
  color: var(--accent-color);
}

.head {
    background:white;
    padding:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
    position:sticky;
    top:0;
    z-index:10;
}

.header {
  color: var(--background-color);
  background-color: var(--hover-color);
  padding: 10px 0;
  transition: all 0.5s;
  z-index: 997;
}

.logo {
  font-size: 25px;
  margin: 0;
  font-weight: 700;
  color: var(--background-color);
}

.scrolled .header {
  box-shadow: 0px 0 18px rgba(0, 0, 0, 0.1);
}

/* Global Header on Scroll
------------------------------*/
.scrolled .header {
  --background-color: var(--accent-color);
}

/*--------------------------------------------------------------
# Navigation Menu
--------------------------------------------------------------*/

.nav {
    position:fixed;
    bottom:0;
    left:0;
    width:100%;
    background:white;
    display:flex;
    justify-content:space-around;
    padding:10px 0;
    box-shadow:0 -2px 10px rgba(0,0,0,0.08);
}

.nav div {
    font-size:12px;
    text-align:center;
    cursor:pointer;
}

.nav span {
    display:block;
    font-size:20px;
}

/* Desktop Navigation */
@media (min-width: 1200px) {
  .navmenu {
    padding: 0;
  }

  .navmenu ul {
    margin: 0;
    padding: 0;
    display: flex;
    list-style: none;
    align-items: center;
  }

  .navmenu li {
    position: relative;
  }

  .navmenu>ul>li {
    white-space: nowrap;
    padding: 15px 14px;
  }

  .navmenu>ul>li:last-child {
    padding-right: 0;
  }

  .navmenu a,
  .navmenu a:focus {
    color: color-mix(in srgb, var(--background-color), transparent 20%);
    font-size: 15px;
    padding: 0 2px;
    font-weight: 400;
    display: flex;
    align-items: center;
    justify-content: space-between;
    white-space: nowrap;
    transition: 0.3s;
    position: relative;
  }

  .navmenu a i,
  .navmenu a:focus i {
    font-size: 12px;
    line-height: 0;
    margin-left: 5px;
    transition: 0.3s;
  }

  .navmenu>ul>li>a:before {
    content: "";
    position: absolute;
    height: 2px;
    bottom: -6px;
    left: 0;
    background-color: var(--accent-color);
    visibility: hidden;
    width: 0px;
    transition: all 0.3s ease-in-out 0s;
  }

  .navmenu a:hover:before,
  .navmenu li:hover>a:before,
  .navmenu .active:before {
    visibility: visible;
    width: 25px;
  }

  .navmenu li:hover>a,
  .navmenu .active,
  .navmenu .active:focus {
    color: var(--nav-color);
  }

  .navmenu .dropdown ul {
    margin: 0;
    padding: 10px 0;
    display: block;
    position: absolute;
    visibility: hidden;
    left: 14px;
    top: 130%;
    opacity: 0;
    transition: 0.3s;
    border-radius: 4px;
    z-index: 99;
    box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.1);
  }

  .navmenu .dropdown ul li {
    min-width: 200px;
  }


  .navmenu .dropdown ul a i {
    font-size: 12px;
  }

  .navmenu .dropdown:hover>ul {
    opacity: 1;
    top: 100%;
    visibility: visible;
  }

  .navmenu .dropdown .dropdown ul {
    top: 0;
    left: -90%;
    visibility: hidden;
  }

  .navmenu .dropdown .dropdown:hover>ul {
    opacity: 1;
    top: 0;
    left: -100%;
    visibility: visible;
  }
}

/* Mobile Navigation */
@media (max-width: 1199px) {
  .mobile-nav-toggle {
    color: var(--nav-color);
    font-size: 30px;
    line-height: 0;
    margin-right: 10px;
    cursor: pointer;
    transition: color 0.3s;
  }

  .navmenu {
    padding: 0;
    z-index: 9997;
  }

  .navmenu ul {
    display: none;
    list-style: none;
    position: absolute;
    inset: 60px 20px 20px 20px;
    padding: 10px 0;
    margin: 0;
    background-color: var(--background-color);
    border: 1px solid color-mix(in srgb, var(--default-color), transparent 90%);
    box-shadow: none;
    overflow-y: auto;
    transition: 0.3s;
    z-index: 9998;
  }

  .navmenu a,
  .navmenu a:focus {
    color: var(--nav-dropdown-color);
    padding: 10px 20px;
    font-family: var(--nav-font);
    font-size: 15px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: space-between;
    white-space: nowrap;
    transition: 0.3s;
  }

  .navmenu a i,
  .navmenu a:focus i {
    font-size: 12px;
    line-height: 0;
    margin-left: 5px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: 0.3s;
    background-color: color-mix(in srgb, var(--accent-color), transparent 90%);
  }

  .navmenu a:hover,
  .navmenu .active,
  .navmenu .active:focus {
    color: var(--accent-color);
  }

  .navmenu .dropdown ul {
    position: static;
    display: none;
    z-index: 99;
    padding: 10px 0;
    margin: 10px 20px;
    background-color: var(--nav-dropdown-background-color);
    transition: all 0.5s ease-in-out;
  }

  .navmenu .dropdown ul ul {
    background-color: rgba(33, 37, 41, 0.1);
  }

  .navmenu .dropdown>.dropdown-active {
    display: block;
    background-color: rgba(33, 37, 41, 0.03);
  }

  .mobile-nav-active {
    overflow: hidden;
  }

  .mobile-nav-active .mobile-nav-toggle {
    color: #fff;
    position: absolute;
    font-size: 32px;
    top: 15px;
    right: 15px;
    margin-right: 0;
    z-index: 9999;
  }

  .mobile-nav-active .navmenu {
    position: fixed;
    overflow: hidden;
    inset: 0;
    background: rgba(33, 37, 41, 0.8);
    transition: 0.3s;
  }

  .mobile-nav-active .navmenu>ul {
    display: block;
  }
}


:root {
  scroll-behavior: smooth;
}


/* ============================
   HERO SECTION
============================ */
.hero {
    position: relative;
    height: 24rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(to right, #d97706, #92400e);
    color: white;
    text-align: center;
    overflow: hidden;
    
}

.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
}

.hero-content {
    position: relative;
    z-index: 10;
}

.hero h1 {
    font-size: clamp(2.5rem, 5vw, 3rem);
    font-weight: 700;
    margin-bottom: 1rem;
}

.hero p {
    font-size: clamp(1rem, 3vw, 1.25rem);
    margin-bottom: 2rem;
}

.hero button {
    color: var(--hover-color);
    padding: 1rem 3rem;
    border: none;
    border-radius: 9999px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    margin-bottom: 25px;
}

.hero button:hover {
    background: var(--design-color);
}

.hero-divider {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4rem;
    background: white;
    transform: skewY(-2deg);
    transform-origin: top left;
}

.hero .hero-waves {
  display: block;
  width: 100%;
  height: 60px;
  position: absolute;
  left: 0;
  bottom: 0;
  right: 0;
  z-index: 3;
}

.hero .wave1 use {
  animation: move-forever1 10s linear infinite;
  animation-delay: -2s;
  fill: var(--background-color);
  opacity: 0.6;
}

.hero .wave2 use {
  animation: move-forever2 8s linear infinite;
  animation-delay: -2s;
  fill: var(--background-color);
  opacity: 0.4;
}

.hero .wave3 use {
  animation: move-forever3 6s linear infinite;
  animation-delay: -2s;
  fill: var(--background-color);
}

@keyframes move-forever1 {
  0% {
    transform: translate(85px, 0%);
  }

  100% {
    transform: translate(-90px, 0%);
  }
}

@keyframes move-forever2 {
  0% {
    transform: translate(-90px, 0%);
  }

  100% {
    transform: translate(85px, 0%);
  }
}

@keyframes move-forever3 {
  0% {
    transform: translate(-90px, 0%);
  }

  100% {
    transform: translate(85px, 0%);
  }
}

@keyframes up-down {
  0% {
    transform: translateY(10px);
  }

  100% {
    transform: translateY(-10px);
  }
}

/* ============================
   MENU SECTION
============================ */
.menu-section  {
    padding: 2rem;    
}

.menu-section div {
    max-width: 1280px; 
    text-align: justify;  
}


.menu-section h2 {
    font-size: 2rem;
    font-weight: 700;
    margin: 2rem;
    text-align: center;
}

.menu-section h3 {
    color: var(--default-color);
    margin: 1rem 0 1rem 0;
}

/* Filter Buttons */

.categories {
    position: sticky;
    top: 70px; /* hauteur du header */
    background: var(--body-color);
    z-index: 9;
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 20px 15px;
}

.filter-container {
    display: flex;
    gap: 1rem;
    overflow-x:auto;
    padding:15px;
}

.filter-btn {
    background: var(--background-color);
    color: var(--hover-color);
    padding: 8px 16px;
    height: 36px;              /* hauteur fixe */
    line-height: 20px;         /* garde le texte centré */
    border: none;
    border-radius: 20px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s;
    display: flex;             /* centre le texte */
    align-items: center;
    justify-content: center;
    white-space: nowrap;       /* empêche le texte de passer à la ligne */
}

.filter-btn:hover {
    background: #fde68a;
}

.filter-btn.active {
    background: var(--accent-color);
    color: white;
}

/* Menu Grid */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

/* Dish Card */
.menu-card {
  position: relative;
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transform: translateY(20px);
    opacity: 0;
    transition: transform 0.5s ease, opacity 0.5s ease;
}

.menu-card.visible {
    transform: translateY(0);
    opacity: 1;
}

.menu-card:hover {
    transform: scale(1.05);
}

.image-container {
    position: relative;
    height: 12rem;
    overflow: hidden;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--accent-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Card Content */
.content {
    padding: 1.5rem;
}

.flex-between {
    display: flex;
    justify-content: space-between;
    align-items: start;
}

.price {
    color: var(--accent-color);
    font-weight: 700;
}

.menu-card p {
    text-align: center;
    margin: 1rem 
}

.menu-card button {
    width: 100%;
    background: var(--accent-color);
    color: white;
    padding: 0.75rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.menu-card button:hover {
    background: #b45309;
}


.menu-card {
    position: relative;
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transform: translateY(20px);
    opacity: 0;
    transition: transform 0.5s ease, opacity 0.5s ease;
}

.check-panier {
    position: absolute;
    top: 200px;
    left: 10px;
    background: green;
    color: white;
    font-size: 12px;
    font-weight: bold;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    text-align: center;
    line-height: 20px;
    pointer-events: none;
    z-index: 20;
}


/* ============================
   CTA SECTION
============================ */
.cta-section {
    padding: 4rem 1rem;
    background: var(--hover-color);
    color: white;
    text-align: center;
}

.cta-section h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.cta-section p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
}

.cta-section button {
    background: white;
    color: var(--hover-color);
    padding: 1rem 2rem;
    border: none;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 1.125rem;
    cursor: pointer;
    transition: background 0.3s;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.cta-section button:hover {
    background: var(--design-color);
}

.cta-section small {
    display: block;
    margin: 1rem 2rem;
    color: #fcd34d;
}


#main{
  margin-top: 10%;
}

select,
.modal .php-form input[type=text],
.modal .php-form input[type=date],
.modal .php-form input[type=number],
.modal .php-form input[type=email],
.modal .php-form input[type=url],
.modal .php-form input[type=tel],
.modal .php-form textarea,
.modal .php-form input[type=password] {
  font-size: 14px;
  margin: 10px auto;
  box-shadow: none;
  border-radius: 0;
}

select, input[type=number]{
  border: 1px solid #d1d3e2;
}

.modal .php-form input[type=text]:focus,
.modal .php-form input[type=email]:focus,
.modal .php-form input[type=url]:focus,
.modal .php-form input[type=tel]:focus,
.modal .php-form textarea:focus,
.modal .php-form input[type=password]:focus {
  border-color: var(--accent-color);
}

.modal .php-form input[type=text]::placeholder,
.modal .php-form input[type=email]::placeholder,
.modal .php-form input[type=url]::placeholder,
.modal .php-form input[type=tel]::placeholder,
.modal .php-form textarea::placeholder,
.modal .php-form input[type=password]::placeholder {
  color: color-mix(in srgb, var(--default-color), transparent 70%);
}

.bg-white{
  height: 90%;
}


.modal .php-form button[type=submit]{
  color: var(--background-color);
  background: var(--accent-color);
  border: 0;
  padding: 10px 30px;
  margin-top: 10px;
  transition: 0.4s;
  border-radius: 50px;
}

.modal .php-form button[type=submit]:hover {
  background: color-mix(in srgb, var(--accent-color), transparent 25%);
}

button.loading::after {
  content: "";
  display: inline-block;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  margin: 0 10px -6px 0;
  float: left;
  border: 3px solid var(--accent-color);
  border-top-color: var(--white-color);
  animation: animate-loading 1s linear infinite;
  display: none;
  z-index: 2;
}

button.loading.show-loader::after {
  display: block;
}

@keyframes animate-loading {
  to {
    transform: rotate(360deg);
  }
}


/*espace admin*/

.navbar-mainbg{
  background-color: var(--accent-color);
}
#navbarSupportedContent{
    overflow: hidden;
    position: relative;
}
#navbarSupportedContent ul{
    padding: 0px;
    margin: 0px;
}
#navbarSupportedContent ul li a i{
    margin-right: 10px;
}
#navbarSupportedContent li {
    list-style-type: none;
    float: left;
}
#navbarSupportedContent ul li a{
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    font-size: 17px;
    display: block;
    padding: 20px 20px;
    transition-duration:0.6s;
    transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    position: relative;
}
#navbarSupportedContent>ul>li.active>a{
    color: var(--default-color);
    background-color: transparent;
    transition: all 0.7s;
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
    background-color: var(--background-color);
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
}

.topbar #sidebarToggleTop{
  color: var(--accent-color);
}

.topbar #sidebarToggleTop:hover{
  color: var(--accent-color);
  background: transparent;
}

.text-light{
  background: var(--accent-color);
}

.page-item.active .page-link {
  background: var(--accent-color);
  border-color: var(--accent-color);
}

.btn-user, .btn-ets, .btn-app {
  margin-left: 10px;
  background: var(--accent-color);
  border: var(--accent-color);
  color: var(--background-color);
  border-radius: 4px;
  padding: 8px 25px;
  white-space: nowrap;
  transition: 0.3s;
  font-size: 14px;
  font-weight: 600;
  display: inline-block;
}

#utilisateur, #appareil, #licence{
  display: none;
}

.dataTables_empty, .d-lg-inline, label{
  color: var(--default-color);
}


.top-bar {
  background: #333;
  color: #fff;
  padding: 1rem;
}

#prev-page, #next-page {
  background: var(--accent-color);
  color: #fff;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 0.7rem 2rem;
}

.btn-light{
  background-color: var(--accent-color);
  border-color: var(--accent-color);
  color: var(--white-color);
}

.pagination, .dataTables_filter label{
  float: right;
}


@media (max-width: 768px) {
  .owl-carousel img {
    height: 350px; /* fixez une hauteur visible */
  }
}

.section-title .img-fluid{
   height: 150px;
}

.dropdown-container {
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  background-color: #fff;
  overflow: hidden;
  transition: box-shadow 0.3s ease;
}

.dropdown-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 24px;
  cursor: pointer;
  background-color: #ffffff;
  font-size: 18px;
  font-weight: 600;
  color: #333;
  transition: background-color 0.3s ease;
}

.dropdown-header:hover {
  background-color: #f8f9fa;
}

.arrow {
  font-size: 18px;
  transition: transform 0.3s ease;
}

.rotate {
  transform: rotate(180deg);
}

.dropdown-content {
  max-height: 0;
  overflow: hidden;
  background-color: #fafafa;
  transition: max-height 0.4s ease, padding 0.3s ease;
  padding: 0 24px;
}

.dropdown-content.open {
  max-height: 500px;
  padding: 16px 24px;
}

.dropdown-content p {
  margin: 0 0 12px;
  color: #555;
  line-height: 1.6;
}

.dropdown-content ul {
  padding-left: 20px;
  margin: 0;
  color: #444;
}

.dropdown-content ul li {
  margin-bottom: 6px;
}

#logo{
  height: 100px; 
  width: 100px;
  border-radius: 10px; 
  border: 1px solid; 
}

.btn-img{
  background: var(--accent-color);
  border: var(--accent-color);
  color: var(--background-color);
  border-radius: 4px;
  cursor: pointer;
  padding: 3px;
  font-size: 14px;
  font-weight: 600;
  display: inline-block;
}


#imgInp, #imgLogo{
  display: none;
}

.modal-f .modal-title, .TG, .montantFinal{
  font-size: 17px;
}

.statu-attente {
    color: grey;
}

.statu-valide {
    color: #28a745; /* vert bootstrap */
}

/* Statut Bloquer */
.statu-expire {
    color: #dc3545; /* rouge bootstrap */
}


.commander {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 1px 6px;
    text-align: center;
}

.quantite-box {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 25px;
  float: right;
}

.quantite-box div {
  color: #000;
  font-size: 25px;
  cursor: pointer;
}

.quantite-box span {
  min-width: 20px;
  text-align: center;
  font-weight: bold;
}

.disabled {
    pointer-events: none;
    opacity: 0.5;
}
        </style>

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
            const nom_table = "<?= htmlspecialchars($nom_table) ?>"; 
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
                let total = panier.reduce((sum, item) => sum + Number(item.total), 0);
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
                    panier.push({ id, libelle: nom, prix, quantite, total: prix * quantite});
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
                    url: "/api-commande/routes/commande.php?id_etablissement=" + id_etablissement,
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

                socket = new WebSocket("wss://gusto-api-48f214a89058.herokuapp.com");

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
                   id_ticket = `TC-${String(now.getFullYear()).slice(-2)}${pad(now.getMonth()+1)}${pad(now.getDate())}-${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}${pad(now.getMilliseconds())}`;

                    localStorage.setItem("id_ticket", id_ticket);
                }

                let payload = {
                    nom_table,
                    id_etablissement,
                    commande: panier,
                    montant_total: totalGeneral,
                    devise: devise,
                    id_ticket
                };

                $.ajax({
                    url: "/api-commande/routes/commande.php?id_etablissement=" + id_etablissement,
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
                            table: nom_table,
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
                        table: nom_table,
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
            // const filterButtons = document.querySelectorAll('.filter-btn');
            // filterButtons.forEach(button => {
            //     button.addEventListener('click', () => {
            //         const category = button.getAttribute('data-filter');

            //         dishCards.forEach(card => {
            //             const cardCategory = card.getAttribute('data-category');
            //             card.style.display = (category === 'tout' || cardCategory === category) ? 'block' : 'none';
            //         });

            //         // Changement de style actif
            //         filterButtons.forEach(btn => btn.classList.remove('active'));
            //         button.classList.add('active');
            //     });
            // });

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