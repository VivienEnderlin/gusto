<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gusto</title>
    <link href="./web/assets/img/gusto.png" class="logo icon" rel="icon">
    <link href="./web/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./web/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="./web/assets/vendor/build/intlTelInput.css" rel="stylesheet">
    <link href="./web/assets/vendor/aos/aos.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

      :root { 
        --background-color: #0f172a;
        --degrad-color: #cbd5e1;
        --design-color: #f97316;
      }

      body{
        font-family:'Poppins',sans-serif;
        background: var(--background-color);
        color:white;
        overflow-x:hidden;
      }

      a{
        text-decoration:none;
      }

      /* NAVBAR */

      .navbar{
        background:rgba(15,23,42,0.75);
        backdrop-filter:blur(10px);
        padding:18px 0;
        transition:.3s;
      }

      .navbar-brand{
        font-size:32px;
        font-weight:700;
        color: var(--design-color) !important;
      }

      .nav-link{
        color:white !important;
        margin-left:20px;
        font-weight:500;
        transition:.3s;
      }

      .nav-link:hover{
        color: var(--design-color) !important;
      }

      .nav-link.active{
        color: var(--design-color) !important;
      }

      .btn-nav{
        background: var(--design-color);
        color:white;
        padding:10px 22px;
        border-radius:12px;
        transition:.3s;
        font-weight:600;
      }

      .btn-nav:hover{
        background:#ea580c;
        color:white;
      }

      /* HERO */

      .hero{
        min-height:100vh;
        display:flex;
        align-items:center;
        position:relative;
        background:linear-gradient(rgba(15,23,42,.85),rgba(15,23,42,.9)),url('./web/assets/img/photo1.jpg');
        background-size:cover;
        background-position:center;
        overflow:hidden;
        padding-top:120px;
      }

      .hero h1{
        font-size:55px;
        font-weight:700;
        line-height:1.2;
      }

      .hero h1 span{
        color: var(--design-color);
      }

      .hero p{
        margin:25px 0;
        color: var(--degrad-color);
        font-size:18px;
        line-height:1.8;
      }

      .hero-buttons{
        display:flex;
        gap:20px;
        flex-wrap:wrap;
      }

      .btn-main{
        background: var(--design-color);
        color:white;
        padding:16px 35px;
        border-radius:14px;
        font-weight:600;
        transition:.3s;
        display:inline-block;
      }

      .btn-main:hover{
        background:#ea580c;
        transform:translateY(-3px);
        color:white;
      }

      .btn-send{
        background: var(--design-color);
        color:white;
        padding:9px 25px;
        border-radius:14px;
        border: none;
        font-weight:600;
        transition:.3s;
        display:inline-block;
      }

      .btn-outline-custom{
        border:1px solid var(--design-color);
        color:white;
        padding:16px 35px;
        border-radius:14px;
        font-weight:600;
        transition:.3s;
      }

      .btn-outline-custom:hover{
        background: var(--design-color);
        color:white;
      }

      /* SECTION TITLE */

      .section-title{
        text-align:center;
        margin-bottom:70px;
      }

      .section-title span{
        color: var(--design-color);
        font-weight:600;
        text-transform:uppercase;
        letter-spacing:2px;
      }

      .section-title h2{
        font-size:45px;
        font-weight:700;
        margin-top:10px;
      }

      /* FEATURES */

      .features{
        padding:120px 0;
      }

      .feature-card{
        background:#1e293b;
        border-radius:25px;
        padding:45px 35px;
        transition:.4s;
        height:100%;
        border:1px solid rgba(255,255,255,.05);
      }

      .feature-card:hover{
        transform:translateY(-12px);
        background:#273549;
      }

      .feature-icon{
        width:80px;
        height:80px;
        background: var(--design-color);
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:20px;
        margin-bottom:30px;
        font-size:35px;
      }

      .feature-card h3{
        font-size:24px;
        margin-bottom:20px;
        font-weight:600;
      }

      .feature-card p{
        color: var(--degrad-color);
        line-height:1.8;
      }

      /* HOW IT WORKS */

      .how{
        padding:120px 0;
        background:#111c31;
      }

      .step-box{
        text-align:center;
        padding:30px;
      }

      .step-number{
        width:70px;
        height:70px;
        background: var(--design-color);
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:auto;
        font-size:28px;
        font-weight:700;
        margin-bottom:25px;
      }

      /* CTA */

      .cta{
        padding:120px 0;
        text-align:center;
        background:
        linear-gradient(rgba(15,23,42,.8),
        rgba(15,23,42,.8)),
        url('./web/assets/img/photo2.jpg');

        background-size:cover;
        background-position:center;
      }

      .cta h2{
        font-size:55px;
        font-weight:700;
        margin-bottom:30px;
      }

      .cta p{
        color: var(--degrad-color);
        font-size:18px;
        margin-bottom:40px;
      }

      /* FOOTER */

      footer{
        background:#0b1220;
        padding:50px 0;
        text-align:center;
      }

      .socials a{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:#1e293b;
        border-radius:50%;
        margin:0 10px;
        color:white;
        transition:.3s;
        font-size:20px;
      }

      .socials a:hover{
        transform:translateY(-5px);
      }

      footer p{
        margin-top:30px;
        color:#94a3b8;
      }

      /* MOBILE */

      @media(max-width:992px){
        .navbar-collapse{
          background:#1e293b;
          padding:20px;
          border-radius:20px;
          margin-top:15px;
        }

        .nav-link{
          margin:15px 0;
        }

      }

      .navbar-toggler:focus{
          box-shadow:none;
      }
      .navbar-toggler i{
          color:white;
      }

      #languageToggle{
        background-color: white; 
        border-radius: 8px;
      }

      .language-dropdown {
          transition: all 0.3s ease;
          opacity: 0;
          visibility: hidden;
          transform: translateY(10px);
          position: absolute;
          z-index: 2;
      }
      .language-dropdown.show {
          opacity: 1;
          visibility: visible;
          transform: translateY(0);
      }
      #google_translate_element select {
          background-image: none !important;
          -webkit-appearance: none !important;
          -moz-appearance: none !important;
          appearance: none !important;
      }

      /* Preloader */
      #preloader {
        position: fixed;
        inset: 0;
        z-index: 999999;
        background: #212529;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.6s ease-out;
      }

      #preloader::before {
        content: "";
        width: 60px;
        height: 60px;
        border: 6px solid var(--design-color);
        border-top-color: transparent;
        border-bottom-color: transparent;
        border-radius: 50%;
        animation: animate-preloader 1s linear infinite;
      }

      @keyframes animate-preloader {
        0% {
          transform: rotate(0deg);
        }

        100% {
          transform: rotate(360deg);
        }
      }

      @keyframes animate-preloader {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

      /* =========================
      ASSISTANT
      ========================= */

      #assistantToggle{
        position:fixed;
        right:25px;
        bottom:25px;
        width:70px;
        height:70px;
        border:none;
        border-radius:50%;
        background:var(--design-color);
        color:white;
        font-size:28px;
        z-index:9999;
        box-shadow:0 10px 30px rgba(0,0,0,.3);
        transition:.3s;
      }

      #assistantToggle:hover{
        transform:scale(1.08);
        background:#ea580c;
      }

      #assistantBox{
        position:fixed;
        right:25px;
        bottom:110px;
        width:370px;
        background: #0f172a;
        border-radius:25px;
        overflow:hidden;
        z-index:9999;
        display:none;
        box-shadow:0 20px 60px rgba(0,0,0,.35);
      }

      @media(max-width:576px){

      #assistantBox{
        width:90%;
        left:5%;
        right:auto;
        bottom:90px;
      }

      #assistantToggle{
        right:20px;
        bottom:20px;
      }

    }

      .assistant-header{
        background:var(--design-color);
        padding:20px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        color:white;
      }

      .assistant-header button{
        background:none;
        border:none;
        color:white;
        font-size:20px;
      }

      .assistant-body{
        padding:25px;
      }

      .assistant-body input,
      .assistant-body textarea{
        background:transparent;
        border: 1px solid grey;
        color:white;
        border-radius:10px;
        padding:14px;
        margin-top: 18px;
      }

      .assistant-body input::placeholder,
      .assistant-body textarea::placeholder{
        color:white;
        font-size: 14px;
      }

      .assistant-body input:focus,
      .assistant-body textarea:focus{
        background:transparent;
        border: 1px solid grey;
        color:white;
        box-shadow:none;
      }

      .assistant-body textarea{
        resize:none;
      }

      .iti{
        width:100%;
      }

      .iti--container{
        z-index:999999999 !important;
      }

      .iti__country-name{
        color: grey;
      }

      /* LOADER */

      button.loading::after {
        content: "";
        display: inline-block;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        margin: 0 10px -5px 0;
        float: left;
        border: 3px solid rgba(255,255,255,.4);
        border-top-color: white;
        animation: animate-loading 1s linear infinite;
        display: none;
        z-index: 2;
      }

      button.loading.show-loader::after {
        display: block;
      }

      /* ANIMATION */

      @keyframes animate-loading {
        to {
          transform: rotate(360deg);
        }
      }
    </style>
  </head>

  <div id="preloader"></div>

  <body>

  <!-- NAVBAR -->

  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <div>
        <span class="navbar-brand">Gusto</span>
          <button id="languageToggle" class="align-items-center px-2 py-1 shadow-sm">
          <i class="bi bi-translate"></i>
          <span>Langues</span>
          </button>
          <div id="languageDropdown" class="language-dropdown absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10" >
            <div id="google_translate_element" class="p-3"></div>
          </div>
      </div>
      
      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"aria-controls="navMenu"aria-expanded="false"aria-label="Toggle navigation"><i class="bi bi-list text-white fs-1"></i></button>

      <div class="collapse navbar-collapse" id="navMenu">

        <ul class="navbar-nav ms-auto align-items-lg-center">

          <li class="nav-item">
            <a class="nav-link" href="#home">Accueil</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#features">Fonctionnalités</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#how">Comment ça marche</a>
          </li>

          <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
            <a href="#contact" class="btn-nav">Contactez-nous</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO -->

  <section class="hero" id="home">

    <div class="container">

      <div class="align-items-center mt-5">

        <div data-aos="fade-up">

          <h1>
            Le futur de la gestion de commandes avec
            <span>Gusto</span>
          </h1>

          <p>
            Digitalisez vos commandes et améliorez l’expérience client grâce à une plateforme moderne, rapide et intelligente.
          </p>

          <div class="hero-buttons">

            <a href="#features" class="btn-main">
              Découvrir la plateforme
            </a>

            <a href="#contact" class="btn-outline-custom">
              Contactez-nous
            </a>

          </div>

        </div>

        <div class="col-lg-6" data-aos="fade-left">

        </div>

      </div>

    </div>

  </section>

  <!-- FEATURES -->

  <section class="features" id="features">

    <div class="container">

      <div class="section-title" data-aos="fade-up">

        <span>Fonctionnalités</span>

        <h2>Une solution complète pour la gestion des commandes</h2>

      </div>

      <div class="row g-4">

        <div class="col-lg-4" data-aos="fade-up">

          <div class="feature-card">

            <div class="feature-icon">
              <i class="bi bi-qr-code"></i>
            </div>

            <h3>Système QR Code</h3>

            <p>
              Les clients scannent le QR code de la table et passent leurs commandes directement depuis leur téléphone.
            </p>

          </div>

        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">

          <div class="feature-card">

            <div class="feature-icon">
              <i class="bi bi-tablet"></i>
            </div>

            <h3>Gestion sur tablette</h3>

            <p>
              Les serveurs gèrent les commandes et interagissent avec le système en temps réel, notamment l’ouverture et la fermeture des tables ainsi que la mise à jour des statuts des commandes...
            </p>

          </div>

        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">

          <div class="feature-card">

            <div class="feature-icon">
              <i class="bi bi-bar-chart"></i>
            </div>

            <h3>Statistiques intelligentes</h3>

            <p>
              Analyse des ventes et suivi des performances globales du restaurant.
            </p>

          </div>

        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">

          <div class="feature-card">

            <div class="feature-icon">
              <i class="bi bi-bell"></i>
            </div>

            <h3>Alerte</h3>

            <p>
             Le client peut demander l’addition en envoyant une notification au serveur via un simple clic sur la plateforme.
            </p>

          </div>
          
        </div>

        <div class="col-lg-4" data-aos="fade-up">

          <div class="feature-card">

            <div class="feature-icon">
              <i class="bi bi-receipt"></i>
            </div>

            <h3>Facturation automatique</h3>

            <p>
              Impression rapide des tickets.
            </p>

          </div>

        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">

          <div class="feature-card">

            <div class="feature-icon">
              <i class="bi bi-clock-history"></i>
            </div>

            <h3>Historique complet</h3>

            <p>
              Retrouvez les commandes
              en quelques secondes.
            </p>

          </div>

        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">

          <div class="feature-card">

            <div class="feature-icon">
              <i class="bi bi-shield-check"></i>
            </div>

            <h3>Système sécurisé</h3>

            <p>
              Gestion fiable et sécurisée des données
              et des opérations du restaurant.
            </p>

          </div>

        </div>

      </div>

    </div>

  </section>

  <!-- HOW IT WORKS -->

  <section class="how" id="how">

    <div class="container">

      <div class="section-title" data-aos="fade-up">

        <span>Simple & Rapide</span>

        <h2>Comment fonctionne Gusto ?</h2>

      </div>

      <div class="row">

        <div class="col-lg-4" data-aos="fade-up">

          <div class="step-box">

            <div class="step-number">1</div>

            <h3>Scan du QR Code</h3>

            <p>
              Le client scanne le QR code installé sur la table.
            </p>

          </div>

        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">

          <div class="step-box">

            <div class="step-number">2</div>

            <h3>Commande instantanée</h3>

            <p>
              Il choisit son menu et passe sa commande directement.
            </p>

          </div>

        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">

          <div class="step-box">

            <div class="step-number">3</div>

            <h3>Service ultra rapide et securiser</h3>

            <p>
              Le serveur reçoit la commande en temps réel pour traitement.
            </p>

          </div>

        </div>

      </div>

    </div>

  </section>

  <!-- CTA -->

  <section class="cta">

    <div class="container" data-aos="zoom-in">

      <h2>
        Faites passer votre restaurant au niveau supérieur
      </h2>

      <p>
        Impressionnez vos clients avec une expérience moderne,
        rapide et totalement digitale.
      </p>

      <a href="#contact" class="btn-main">
        Commander votre système maintenant
      </a>

    </div>

  </section>

  <!-- FOOTER -->

  <footer id="contact">

    <div class="container">

      <h2 class="mb-4">
        Gusto
      </h2>

      <div class="socials d-flex justify-content-center flex-wrap gap-3">

        <a href="https://wa.me/+237680468901">
          <i class="bi bi-whatsapp"> +237 6 80 46 89 01</i>
        </a>

        <a href="https://wa.me/+237693503891">
          <i class="bi bi-whatsapp"> +237 6 93 50 38 91</i>
        </a>

        <a href="https://wa.me/+393288084393">
          <i class="bi bi-whatsapp"> +39 328 808 4393</i>
        </a>

      </div>

      <p>
        © 2026 Gusto — Tous droits réservés
      </p>

    </div>

    <!-- BOUTON ASSISTANT -->
<button id="assistantToggle">
  <i class="bi bi-chat-dots-fill"></i>
</button>

<!-- CHAT ASSISTANT -->
<div id="assistantBox">
  <div class="assistant-header">
    <div>
      <strong>Gusto Assistant</strong><br>
      <small>Support en ligne</small>
    </div>
    <button id="closeAssistant">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>

  <div class="assistant-body">
    <form id="assistantForm">
      <input type="text" name="nom" class="form-control mb-3" placeholder="Votre nom" required>
      <input 
        type="tel" name="telephone" id="phone" class="form-control mb-3" value="+237&nbsp;" placeholder="Votre téléphone (whatsapp)" required>
      <textarea 
        name="message" class="form-control mb-3" rows="4" placeholder="Votre message..." required></textarea>
      <button type="submit" class="btn-send loading">
        Envoyer
      </button>

    </form>

  </div>

</div>

  </footer>

  <!-- JS -->
  <script src="./web/assets/vendor/jquery/jquery.min.js"></script>
  <script src="./web/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="./web/assets/vendor/aos/aos.js"></script>
  <script src="./web/assets/vendor/build/intlTelInput.js"></script>

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

        //preload
        const preloader = document.querySelector('#preloader');
        if (preloader) {
          window.addEventListener('load', () => {
            preloader.remove();
          });
        }

        AOS.init({
          duration:1000,
          once:true
        });

        // OUVRIR LE BOT
        $('#assistantToggle').on('click', function(){
          $('#assistantBox').slideDown(700);
        });

        // FERMER LE BOT
        $('#closeAssistant').on('click', function(){
          $('#assistantBox').slideUp(700);
        });

        // ENVOYER MESSAGE
        $('#assistantForm').on('submit', function(e){
          e.preventDefault();
          $('button.loading').addClass('show-loader').prop('disabled', true);
          $.ajax({
            url:'./api-commande/routes/send_message.php',
            type:'POST',
            data: $(this).serialize(),
            success:function(response){
              $('button.loading').removeClass('show-loader').prop('disabled', false);
              setTimeout(() => {
                alert('Message envoyé avec succès');
                $('textarea[name="message"]').val('');
              }, 100);
            },
            error:function(){
              $('button.loading').removeClass('show-loader').prop('disabled', false);
              setTimeout(() => {
                alert('Erreur lors de l’envoi');
              }, 100);
            }
          });
        });

        //---------SCROLL SPY----------

        const sections = document.querySelectorAll("section");
        const navLinks = document.querySelectorAll(".nav-link");
        window.addEventListener("scroll", () => {
          let current = "";
          sections.forEach(section => {
            const sectionTop = section.offsetTop - 150;
            const sectionHeight = section.clientHeight;

            if (scrollY >= sectionTop) {
              current = section.getAttribute("id");
            }

          });
          navLinks.forEach(link => {
            link.classList.remove("active");
            if(link.getAttribute("href") === "#" + current){
              link.classList.add("active");
            }

          });

        });

        var input = document.querySelector("#phone");
        const iti =window.intlTelInput(input, {
          utilsScript: "./web/assets/vendor/build/utils.js",
        });

  </script>

  </body>
</html>