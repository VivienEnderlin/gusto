<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gusto Galaxy - Découvrez nos décelis</title>
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
          <li><a href="#foctionnement">Fonctionnement</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="">Politique de confidentialité</a></li>
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
            

        </section>

        <!-- Menu Section -->
       <section id="foctionnement" class="menu-section">
            <h2>Comment ça marche</h2>
            

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
