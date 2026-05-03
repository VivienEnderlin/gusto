<?php
	require_once 'link.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title><?=htmlspecialchars($etablissements['nom'])?></title>
	<?php
            $logos = json_decode($etablissements['logo'], true);
            $logo = $logos[0] ?? '';
        ?>
        <link href="<?= htmlspecialchars($logo) ?>" rel="icon">
	<link href="./assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
	<link rel="stylesheet" href="./assets/css/style.css" />
	<style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            background: #f5f5f5;
        }
        .card {
	        width: 80%;
	        max-width: 500px; /* 👈 limite sur grand écran */
	    }
    </style>
</head>
	<body>
		<div class="card p-4">
            <h5 class="mb-3">🔐 Entrer le code du service</h5>
			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" id='verify' class="php-form">
				<div class="erreur"></div>
	            <div class="row">
	                <div class="col-lg-12">
	                  <input type="text" name="code" class="form-control">
	                </div>
	                <div class="col-md-12 mt-3">
	                  <button class="btn btn-warning float-right">Verification</button>
	                </div>
	          </div>
	        </form>
	    </div>
        <script src="./assets/vendor/jquery/jquery.min.js"></script>
        <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script>
        	const id_etablissement = "<?= htmlspecialchars($id_etablissement) ?>"
        	const id_table = "<?= htmlspecialchars($id_table) ?>"
        	const qrCode = "<?= htmlspecialchars($code) ?>";
        	$('#verify').on('submit', function(e) {
		        e.preventDefault();
		        const codeInput = $('input[name="code"]').val().trim();
		        $.ajax({
		            url: "http://gusto/api-commande/routes/check.php?id_etablissement=" + id_etablissement + "&id_table=" + id_table,
		            method: "GET",
		            dataType: "json",
		            success: function(result) {
		                if(result.success && result.data) {
		                	const codeBD = result.data.code;
		                	if (codeInput === codeBD) {
		                		localStorage.setItem('code_service', codeBD);
		                        window.location.href = "menu.php?code=" + qrCode;
		                	}
		                	else{
			                  $('.erreur').hide().html('<div class="alert alert-block alert-danger"><i class="icofont-close" style="margin-right: 10px; font-weight: bold;"></i>Wrong service code </div>').slideDown(500);
			                }
			                    
		                }
		                
		            },
		        });
		    });
        </script>
	</body>
</html>
