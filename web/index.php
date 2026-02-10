<?php
session_start();

// Récupérer l'id depuis le QR code et le stocker en session
if (isset($_GET['id'])) {
    $_SESSION['restaurant_id'] = $_GET['id'];
}
$restaurantId = $_SESSION['restaurant_id'] ?? ''; // on récupère l'id
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gusto Galaxy</title>

<style>
/* Ton style existant */
body {
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f0f2f5;
    font-family: Arial, sans-serif;
}

.container {
    text-align: center;
}

.input-table {
    padding: 12px;
    font-size: 16px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-bottom: 20px;
    width: 250px;
    text-align: center;
}

.btn {
    background-color: #d97706;
    color: white;
    padding: 15px 30px;
    font-size: 18px;
    border-radius: 10px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    pointer-events: none;
    opacity: 0.5;
}

.btn.active {
    pointer-events: auto;
    opacity: 1;
}

.btn.active:hover {
    background-color: #b45309;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}
</style>
</head>

<body>

<div class="container">
    <input
        type="text"
        class="input-table"
        id="tableNumber"
        placeholder="Entrez le numéro de table"
    >

    <br>

    <a class="btn" id="goBtn" href="#">
        Cliquez ici
    </a>
</div>

<script>
const input = document.getElementById('tableNumber');
const button = document.getElementById('goBtn');

// Récupération de l'id unique depuis PHP
const restaurantId = "<?= htmlspecialchars($restaurantId, ENT_QUOTES) ?>";

input.addEventListener('input', () => {
    if (input.value.trim() !== '') {
        button.classList.add('active');
    } else {
        button.classList.remove('active');
        button.href = '#';
    }
});

button.addEventListener('click', (e) => {
    e.preventDefault(); // empêcher href="#" de bloquer le clic
    const tableNumber = input.value.trim();

    if (tableNumber === '') return;

    // Redirection vers menu.php avec l'id et la table
    window.location.href = `./menu.php?id=${encodeURIComponent(restaurantId)}&table=${encodeURIComponent(tableNumber)}`;
});
</script>

</body>
</html>
