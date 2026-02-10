<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Gusto Galaxy - Découvrez nos décelis</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="./assets/img/gusto.ico" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i,900" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="./assets/css/style.css" rel="stylesheet">
</head>

<body>

  <main id="main">
    <div class="container contact">
      <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" class="php-form text-center" id='connecter'>
            <div class="erreur"></div>
            <img src="./assets/img/gusto.ico" style="width: 80px;">
            <hr>
            <div class="form-row">
              <div class="col-lg-12 form-group">
                <input type="text" name="login" class="form-control" placeholder="Votre login" required/>
              </div>
              <div class="col-lg-12 form-group">
                <input type="password" class="form-control" name="id_unique_etablissement" placeholder="Votre mot de passe" required/>
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
<script>
 $('#connecter').on('submit', function(e) {
    e.preventDefault();
    $('button.loading').addClass('show-loader').prop('disabled', true);

    var postdata = {
        login: $('input[name="login"]').val(),
        id_unique_etablissement: $('input[name="id_unique_etablissement"]').val()
    };

    $.ajax({
        type: 'POST',
        url: 'http://gusto/api-commande/routes/auth.php',
        data: JSON.stringify(postdata),
        contentType: "application/json",
        dataType: "json",
        success: function(result) {
            $('button.loading').removeClass('show-loader').prop('disabled', false);

            // On vide le container et on affiche le message de l'API
            $('.erreur').hide().html(result.html).slideDown(500);

            if(result.success && result.token) {
                // Stocker le token et redirection après 1s
                localStorage.setItem('token', result.token);
                $('.erreur').html(result.html).delay(500).hide(function(){ 
                  window.location.href = './dashboard.php';
                })  
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

</script>


</body>

</html>