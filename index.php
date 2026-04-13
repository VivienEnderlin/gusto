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
        rel="stylesheet"/>
  </head>

  <div id="preloader"></div>

  <body>
    <header id="header" class="header d-flex align-items-center fixed-top">
      <div class="container container-xl position-relative d-flex align-items-center justify-content-between">

        <div>
          <span class="logo">Gusto</span>
          <button id="languageToggle" class="align-items-center px-2 py-1 shadow-sm">
            <i class="bi bi-translate"></i>
            <span>Langues</span>
          </button>
          <div id="languageDropdown" class="language-dropdown absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10" >
              <div id="google_translate_element" class="p-3"></div>
          </div>
        </div>

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
            <span>Gusto est une plateforme digitale conçue pour simplifier la gestion des commandes dans un etablissement (restaurant, snack...). Grâce à ce systeme bien definit, la gestion devient fluide, securisé et intelligente, tout en permettant une prise de décision rapide grâce à des informations claires et accessibles en temps réel.</span>.
            <h2>Fonctionnalités principales</h2>
            <h3>Le client disposant d'un smartphone</h3>
            <ul>
              <li>Accèder au menu digitale en scannant le Qrcode de la table</li><br>
              <li>Commander à distance</li><br>
              <li>signaler pour reclamer sa facture</li>   
            </ul>
            <h3>Le serveur disposant d'une tablette</h3>
            <ul>
              <li>Ouvre et ferme le service d'une table</li><br>
              <li>Selectionne le menu pour le client qui n'a pas de téléphone</li><br>
              <li>Visualise l'historique commandes</li><br>
              <li>Interagir avec le système pour informer du statu de la commande</li>
            </ul>  
            <h3>Le Gérant disposant d'un ordinateur</h3>
            <ul>
              <li>Gestion des tables, produits, employé etc...</li><br>
              <li>Visiualise l'historique des commandes</li><br>
              <li>Contrôle globale des trafics en temps réel</li><br>
              <li>Imprime les factures</li><br>
            </ul>
        </section>

        <!-- Call to Action -->
        <section id="contact" class="cta-section">
          <h2>Prêt à simplifier votre gestion de commandes ?</h2>
            <a href="https://wa.me/680468901"><button>Contactez nous au 680468901</button></a>
        </section>
    </main>

    <script src="./web/assets/vendor/jquery/jquery.min.js"></script>
    <script src="./web/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./web/assets/js/feather.min.js"></script>

    <script>

        // Toggle du menu déroulant
        document.getElementById('languageToggle').addEventListener('click', function() {
            const dropdown = document.getElementById('languageDropdown');
            const chevron = this.querySelector('.fa-chevron-down');
            
            dropdown.classList.toggle('show');
            chevron.classList.toggle('rotate-180');
        });

        // Fermer le dropdown quand on clique ailleurs
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('languageDropdown');
            const toggle = document.getElementById('languageToggle');
            
            if (!toggle.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
                toggle.querySelector('.fa-chevron-down').classList.remove('rotate-180');
            }
        });

        // Fonction pour initialiser Google Traduction
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                // pageLanguage: 'fr',
                includedLanguages: 'fr,en,es,de,it,pt,ru,zh-CN,ja,ar',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
            
            // Masquer la barre Google par défaut
            const googleBar = document.querySelector('.goog-te-banner-frame');
            if (googleBar) googleBar.style.display = 'none';
        }

        // Charger le script Google Traduction
        function loadGoogleTranslateScript() {
            const script = document.createElement('script');
            script.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
            document.body.appendChild(script);
        }

        // Démarrer le chargement
        loadGoogleTranslateScript();

        /**Preloader*/
        const preloader = document.querySelector('#preloader');
        if (preloader) {
          window.addEventListener('load', () => {
            preloader.remove();
          });
        }

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