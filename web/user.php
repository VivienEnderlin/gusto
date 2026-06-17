<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Gusto Manager</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <link href="./assets/img/gusto.png" rel="icon">
        <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i,900" rel="stylesheet">
        <style>
            *{
                margin:0;
                padding:0;
                box-sizing:border-box;
                font-family:'Poppins',sans-serif;
            }

            body{
                min-height:100vh;
                background:#111c31;
                overflow:hidden;
            }

            .container{
                display:flex;
                min-height:100vh;
            }

            /* LEFT */

            .left-side{
                flex:1;
                background:#111c31;
                position:relative;
                display:flex;
                flex-direction:column;
                justify-content:center;
                padding:80px;
                overflow:hidden;
            }

            /* COURBE */
            .left-side::after{
                content:'';
                position:absolute;
                top:0;
                right:-120px;
                width:300px;
                height:100%;
                background:#fff;
                clip-path: ellipse(60% 70% at 100% 50%);
                z-index:1;
            }

            .left-side::before{
                content:'';
                position:absolute;
                width:500px;
                height:500px;
                background:linear-gradient(135deg,#ff7a00,#ff922b);
                border-radius:50%;
                top:-150px;
                left:-150px;
                filter:blur(80px);
                opacity:.35;
            }

            .logo{
                position:absolute;
                top:40px;
                left:50px;
                color:white;
                font-size:32px;
                font-weight:700;
                letter-spacing:3px;
                text-decoration: none;
            }

            .content{
                max-width:550px;
                z-index:2;
            }

            .content h1{
                color:white;
                font-size:60px;
                line-height:1.1;
                margin-bottom:20px;
            }

            .content p{
                color:rgba(255,255,255,.8);
                font-size:18px;
                line-height:1.8;
            }

            /* RIGHT */

            .right-side{
                width:600px;
                display:flex;
                justify-content:center;
                align-items:center;
                background:white;
            }

            .login-card{
                width:380px;
            }

            .login-card h2{
                color:#111c31;
                font-size:34px;
                margin-bottom:10px;
            }

            .subtitle{
                color:#777;
                margin-bottom:35px;
            }

            .input-group{
                margin-bottom:20px;
            }

            .input-group input{
                width:100%;
                padding:18px;
                border:2px solid #eaeaea;
                border-radius:14px;
                font-size:15px;
                transition:.3s;
            }

            .input-group input:focus{
                border-color:#ff7a00;
                outline:none;
            }

            button{
                width:100%;
                padding:18px;
                border:none;
                border-radius:14px;
                background:linear-gradient(135deg,#ff7a00,#ff922b);
                color:white;
                font-size:16px;
                font-weight:600;
                cursor:pointer;
                transition:.3s;
            }

            button:hover{
                transform:translateY(-2px);
                box-shadow:0 15px 30px rgba(255,122,0,.35);
            }

            .forgot{
                display:block;
                margin-top:20px;
                text-align:center;
                color:#ff7a00;
                text-decoration:none;
                font-weight:500;
            }

            @media(max-width:950px){

                .left-side{
                    display:none;
                }

                .right-side{
                    width:100%;
                }

            }

            button.loading::after {
              content: "";
              display: inline-block;
              border-radius: 50%;
              width: 22px;
              height: 22px;
              margin: 0 -18px -5px 0;
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

            .custom-alert{
                display:flex;
                align-items:center;
                gap:15px;
                padding:15px 20px;
                border-radius:15px;
                box-shadow:0 10px 25px rgba(0,0,0,0.08);
                animation: fadeIn .3s ease;
                font-family: 'Segoe UI', sans-serif;
                margin-bottom: 15px;
            }

            /* ERROR */
            .error-alert{
                background:#ffe8e8;
                border-left:5px solid #ea5455;
                color:#b10000;
            }

            .success-alert{
                background:#e7f9ee;
                border-left:5px solid #28c76f;
                color:#0f7a3a;
            }


        </style>
    </head>

    <body>

        <div class="container">
            <div class="left-side">
                <div>
                   <a href="" class="logo">GUSTO</a> 
                </div>
                <div class="content">
                    <h1>Gestion simplifiée.</h1>
                    <p>Gérez vos commandes, vos équipes et vos performances depuis une seule plateforme.</p>
                </div>
            </div>

            <div class="right-side">

                <div class="login-card">

                    <h2>Connexion Gérant</h2>

                    <p class="subtitle">
                        Accédez à votre espace d'administration
                    </p>

                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='connecter'>
                        <div class="erreur"></div>

                        <div class="input-group">
                            <input type="text" name="login" placeholder="Votre identifiant" required>
                        </div>

                        <div class="input-group">
                            <input type="password" name="password" placeholder="Votre mot de passe" required>
                        </div>

                        <button class="loading" type="submit">Se connecter</button>

                    </form>

                    <a href="#" class="forgot">
                        Mot de passe oublié ?
                    </a>

                </div>

            </div>

        </div>
        <script src="./assets/vendor/jquery/jquery.min.js"></script>
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

                        if (result.success && result.token && result.role == 1) {

                            localStorage.setItem('token', result.token);
                            $('.erreur').html('<div class="custom-alert success-alert"><i class="icofont-check" style="margin-right: 10px; font-weight: bold;"></i>Vous êtes connecté</div>').delay(500).hide(
                                function(){ 
                                    window.location.href = './gerant.php';
                                });

                        } else {

                            if (result.statu === "Expiré") {

                                $('.erreur') .hide().html("<div class='custom-alert error-alert'><i class='icofont-close' style='margin-right: 10px; font-weight: bold;'></i>Votre abonnement a expiré veuillez contacter l'administrateur</div>").slideDown(500);

                            } else {

                            $('.erreur').hide().html("<div class='custom-alert error-alert'><i class='icofont-close' style='margin-right: 10px; font-weight: bold;'></i>Information incorrect</div>").slideDown(500);
                            }
                        }
                        
                    },
                    error: function(xhr, status, error) {
                        $('button.loading').removeClass('show-loader').prop('disabled', false);
                        // On peut afficher un message générique d'erreur serveur
                        $('.erreur').hide().html(
                            '<div class="custom-alert error-alert">' +
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