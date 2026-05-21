<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Gusto</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="./assets/img/gusto.png" type="image/png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i,900" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="./assets/vendor/aos/aos.css" rel="stylesheet">

  <style>
    :root{
      --background-color:#071126;
      --card-color:#111c31;
      --accent-color:#ff7a00;
      --white-color:#ffffff;
      --default-color:#cbd5e1;
    }


    #main{
      margin-top: 8%;
    }

    /* CARD */

    .contact .php-form {
      background: var(--white-color);
      border: 1px solid rgba(255,255,255,.08);
      box-shadow: 0 20px 45px rgba(0,0,0,.45);
      border-radius: 25px;
      padding: 40px;
      backdrop-filter: blur(12px);
    }

    /* INPUTS */

    .contact .php-form input[type=text],
    .contact .php-form input[type=password]{
      font-size: 14px;
      margin-bottom: 15px;
      margin-top: 20px;
      color: #000;
      box-shadow: none;
      border-radius: 10px;
      padding: 14px 18px;
      transition: .3s;
    }

    select,
    input[type=number]{
      border: 1px solid rgba(255,255,255,.08);
    }

    /* FOCUS */
    .contact .php-form input[type=text]:focus,
    .contact .php-form input[type=password]:focus{
      border-color: var(--accent-color);

    }

    /* PLACEHOLDER */
    .contact .php-form input[type=text]::placeholder,
    .contact .php-form input[type=password]::placeholder {
      color: grey;
    }

    /* BUTTON */

    .contact .php-form button[type=submit]{
      color: white;
      background: linear-gradient(135deg,#ff7a00,#ff8f1f);
      border: 0;
      padding: 13px 35px;
      margin-top: 15px;
      transition: 0.4s;
      border-radius: 50px;
      font-weight: 600;
      letter-spacing: .5px;
    }

    .contact .php-form button[type=submit]:hover,
    .modal .php-form button[type=submit]:hover {
      transform: translateY(-3px);
      background: linear-gradient(135deg,#ff8f1f,#ea580c);
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

<body>

  <main id="main">
    <div class="container contact">
      <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300" data-aos="fade-up">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" class="php-form text-center" id='connecter'>
            <div class="erreur"></div>
            <img src="./assets/img/gusto.png" style="width: 120px;">
            <hr>
            <div class="form-row mt-4">
              <div class="col-lg-12 form-group">
                <input type="text" name="login" class="form-control" placeholder="Votre login" required/>
              </div>
              <div class="col-lg-12 form-group">
                <input type="password" class="form-control" name="password" placeholder="Votre mot de passe" required/>
              </div>
            </div>
            <div class="form-group">
              <button class="loading" type="submit">Connecter</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
  

  <!-- Vendor JS Files -->
  <script src="./assets/vendor/jquery/jquery.min.js"></script>
  <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/vendor/noBack/noBack.js"></script>
  <script src="./assets/vendor/aos/aos.js"></script>
  <script>
    $('#connecter').on('submit', function(e) {
        e.preventDefault();
        $('button.loading').addClass('show-loader').prop('disabled', true);

        var postdata = {
            login: $('input[name="login"]').val(),
            password: $('input[name="password"]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/api-commande/routes/auth.php',
            data: JSON.stringify(postdata),
            contentType: "application/json",
            dataType: "json",
            success: function(result) {
                $('button.loading').removeClass('show-loader').prop('disabled', false);

                if(result.success && result.token && result.role == 0) {
                    // Stocker le token et redirection après 1s
                    localStorage.setItem('token', result.token);
                    $('.erreur').html('<div class="alert alert-block alert-success"><i class="icofont-check" style="margin-right: 10px; font-weight: bold;"></i>Vous êtes connecté</div>').delay(500).hide(function(){ 
                      window.location.href = './dashboard.php';
                    })  
                }
                else{
                  $('.erreur').hide().html('<div class="alert alert-block alert-danger"><i class="icofont-close" style="margin-right: 10px; font-weight: bold;"></i>Information incorrect  </div>').slideDown(500);
                }
            },
            error: function(xhr, status, error) {
                $('button.loading').removeClass('show-loader').prop('disabled', false);
                // On peut afficher un message générique d'erreur serveur
                $('.erreur').hide().html(
                    '<div class="alert alert-block alert-danger">' +
                    '<i class="icofont-close" style="margin-right: 10px; font-weight: bold;"></i>' +
                    'Erreur serveur : ' + error +
                    '</div>'
                ).slideDown();
            }
        });
    });

    AOS.init({
          duration:1000,
          once:true
        });

  </script>

</body>

</html>