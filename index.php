<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gusto</title>
     <link href="./web/assets/img/gusto.ico" class="logo icon" rel="icon">

    <link rel="stylesheet" href="./web/assets/css/style.css" />
    <link href="./web/assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="./web/assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="./web/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600&display=swap"
      rel="stylesheet"
    />
</head>
<body>
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="https://ingenierielogiciel.com" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="./web/assets/img/logo.png" alt="">
        <span>Gusto Galaxy</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#accueil" class="active">Accueil</a></li>
          <li><a href="#presentation">A propos du système</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="privacy.php">Politique de confidentialité</a></li>
          <li><a href="./web/login.php">Connexion</a></li>
          
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>
    <custom-navbar></custom-navbar>

    <main>
        <!-- Hero Section -->
        <section id="accueil" class="hero" style="height: 600px;">
            <div class="hero-content container">
               <h1>Plateforme de digitalisation de gestion des commandes</h1>
                <p>Transformez votre restaurant en hight-tech en digitalisant vos commandes*.</p>
               <a href="#presentation"><button>En savoir plus</button></a>
            </div>
            <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
              <defs>
                <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
              </defs>
              <g class="wave1">
                <use xlink:href="#wave-path" x="50" y="3"></use>
              </g>
              <g class="wave2">
                <use xlink:href="#wave-path" x="50" y="0"></use>
              </g>
              <g class="wave3">
                <use xlink:href="#wave-path" x="50" y="9"></use>
              </g>
            </svg>
        </section>

        <section id="presentation" class="menu-section">
            <h2>Présentation du système</h2>
            <span>Gusto est une plateforme digitale conçue pour simplifier la gestion des commandes dans un etablissement (restaurant,bar, snack...). Grâce à ce systeme bien definit, la gestion devient fluide, securisé et intelligente, tout en permettant une prise de décision rapide grâce à des informations claires et accessibles en temps réel.</span>.
            <h2>Fonctionnalités principales</h2>
            <h3>Le client</h3>
            <ul>
              <li>Accède au menu digitale en scannant le Qrcode de la table avec son téléphone</li><br>
              <li>Commande à distance depuis son téléphone</li><br>
              <li>signale pour reclamer sa facture depuis son téléphone</li>   
            </ul>
            <h3>Le serveur</h3>
            <ul>
              <li>Ouvre et ferme le service d'une table à l'aide d'une tablette apres avoir installer le client</li><br>
                <li>Selectionne le menu pour le client qui n'a pas de téléphone</li><br>
                <li>Visualise l'historique commandes</li>
            </ul>  
            <h3>Le Gerant</h3>
            <ul>
              <li>Gestion des tables, produits etc</li><br>
              <li>Visiualise l'historique des commandes</li><br>
              <li>Contrôle globale des trafics en temps réel (Statu des commandes en attente, servie, payer etc...)</li><br>
              <li>Imprime les factures</li><br>
              <li>Ouvre et ferme le service d'une table</li>
            </ul>
        </section>

        <!-- Call to Action -->
        <section id="contact" class="cta-section">
          <h2>Prêt à simplifier votre gestion de commandes ?</h2>
            <a href="tel://680468901"><button>Contactez nous au 680468901</button></a>
        </section>
    </main>

    <script src="./web/assets/vendor/jquery/jquery.min.js"></script>
    <script src="./web/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./web/assets/js/feather.min.js"></script>

    <script>
      //scrollspy
      let navmenulinks = document.querySelectorAll('.navmenu a');
      function navmenuScrollspy() {
        navmenulinks.forEach(navmenulink => {
          if (!navmenulink.hash) return;
          let section = document.querySelector(navmenulink.hash);
          if (!section) return;
          let position = window.scrollY + 200;
          if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
            document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
            navmenulink.classList.add('active');
          } else {
            navmenulink.classList.remove('active');
          }
        })
      }
      window.addEventListener('load', navmenuScrollspy);
      document.addEventListener('scroll', navmenuScrollspy);

      //navbar

      const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');
      function mobileNavToogle() {
        document.querySelector('body').classList.toggle('mobile-nav-active');
        mobileNavToggleBtn.classList.toggle('bi-list');
        mobileNavToggleBtn.classList.toggle('bi-x');
      }
      mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
      document.querySelectorAll('#navmenu a').forEach(navmenu => {
        navmenu.addEventListener('click', () => {
          if (document.querySelector('.mobile-nav-active')) {
            mobileNavToogle();
          }
        });

      });
      document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
        navmenu.addEventListener('click', function(e) {
          e.preventDefault();
          this.parentNode.classList.toggle('active');
          this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
          e.stopImmediatePropagation();
        });
      });


    </script>
</body>
</html>
