<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gusto Manager</title>
    <link href="./assets/img/gusto.png" class="logo icon" rel="icon">

    <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="./assets/vendor/datatables/datatables.bootstrap4.min.css" rel="stylesheet">
    <link href="./assets/vendor/virtual-select/virtual-select.min.css" rel="stylesheet">
    <link href="./assets/vendor/build/intlTelInput.css" rel="stylesheet">
    <link href="./assets/vendor/font-awesome/css/all.min.css" rel="stylesheet">
    <style>
        :root{
            --primary:#ff7a00;
            --secondary:#ff922b;
            --dark:#111c31;
            --light:#f5f7fb;
            --white:#ffffff;
            --success:#28c76f;
            --danger:#ea5455;
            --warning:#ff9f43;

            --shadow: 0 10px 30px rgba(17,28,49,.08);
            --radius:20px;
        }

        /* ======================
        RESET
        ====================== */
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            background:var(--light);
            min-height:100vh;
            color:#333;
            overflow-x:hidden;
        }

        /* ======================
        SIDEBAR (FIXE À GAUCHE)
        ====================== */
        .sidebar{
            width:280px;
            background:var(--dark);
            color:white;

            position:fixed;
            top:0;
            left:0;
            height:100vh;

            padding:30px 20px;
            overflow-y:auto;
        }

        /* ======================
        MAIN
        ====================== */
        .main{
            margin-left:280px;
            padding:30px;
            min-width:0;
        }

        /* ======================
        LOGO
        ====================== */
        .logo{
            text-align:center;
            margin-bottom:50px;
        }

        .logo span{
            font-size:20px;
            font-weight:800;
            letter-spacing:3px;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        /* ======================
        MENU
        ====================== */
        .menu{
            list-style:none;
        }

        .menu li{
            padding:18px;
            border-radius:15px;
            margin-bottom:10px;
            cursor:pointer;
            transition:.3s;
            display:flex;
            align-items:center;
            gap:15px;
        }

        .menu li:hover{
            background:rgba(255,255,255,.08);
        }

        .menu li.active{
            background:linear-gradient(135deg,var(--primary),var(--secondary));
        }

        .menu li i{
            width:20px;
        }

        /* ======================
        HEADER
        ====================== */
        .header{
            background:white;
            border-radius:var(--radius);
            padding:20px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow:var(--shadow);
            margin-bottom:30px;
        }

        .header h2{
            color:var(--dark);
        }

        .header p{
            color:#888;
            margin-top:5px;
        }

        .header-actions{
            display:flex;
            align-items:center;
            gap:20px;
            margin-left:auto;
        }

        .notification-btn{
            width:50px;
            height:50px;
            border:none;
            border-radius:50%;
            background:white;
            box-shadow:var(--shadow);
            cursor:pointer;
            font-size:18px;
        }

        .avatar{
            width:55px;
            height:55px;
            border-radius:50%;
            overflow:hidden;
            cursor:pointer;
            border:3px solid var(--primary);
        }

        .avatar img{
            width:100%;
            height:100%;
            /*object-fit:cover;
            f*/
        }

        /* ======================
        STATS
        ====================== */
        .stats{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:20px;
            margin-bottom:30px;
        }

        .card{
            background:white;
            border-radius:var(--radius);
            box-shadow:var(--shadow);
        }

        .stat-card{
            padding:25px;
            position:relative;
            overflow:hidden;
        }

        .stat-card::before{
            content:'';
            position:absolute;
            right:-30px;
            top:-30px;
            width:120px;
            height:120px;
            border-radius:50%;
            background:rgba(255,122,0,.08);
        }

        .stat-card i{
            color:var(--primary);
            font-size:28px;
            margin-bottom:15px;
        }

        .stat-card h3{
            color:#777;
            font-size:15px;
            margin-bottom:10px;
        }

        .stat-card h1{
            color:var(--dark);
            font-size:28px;
        }

        /* ======================
        CHARTS
        ====================== */
        .charts{
            display:grid;
            grid-template-columns:2fr 1fr;
            gap:20px;
            margin-bottom:30px;
        }

        .chart-card{
            padding:25px;
            overflow:hidden;
            position:relative;
        }

        .chart-card h3{
            margin-bottom:20px;
            color:var(--dark);
        }

        .chart-card canvas{
            width:100% !important;
        }

        /* ======================
        SECTIONS
        ====================== */
        .content-section{
            background:white;
            padding:25px;
            border-radius:var(--radius);
            box-shadow:var(--shadow);
            margin-bottom:30px;
        }

        .section-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:25px;
        }

        /* ======================
        BUTTONS
        ====================== */
        .btn-primary{
            border:none;
            padding:12px 22px;
            border-radius:12px;
            color:white;
            cursor:pointer;
            font-weight:600;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            transition:.3s;
        }

        .btn-primary:hover{
            transform:translateY(-2px);
        }

        .icon-btn{
            width:40px;
            height:40px;
            border:none;
            border-radius:10px;
            background:#f4f4f4;
            cursor:pointer;
            transition:.3s;
        }

        .icon-btn:hover{
            transform:translateY(-2px);
        }

        .icon-btn.danger{ background:#ffe6e6; color:var(--danger); }
        .icon-btn.qr{ background:#fff5df; color:var(--warning); }
        .icon-btn.view{ background:#e7f4ff; color:#3498db; }

        .badge.success{
            background:#dff9e8;
            color:#28c76f;
        }

        .badge.danger{
            background:#ffe6e6;
            color:#ea5455;
        }

        /* ======================
        TABLES
        ====================== */
        table{
            width:100%;
            border-collapse:collapse;
        }

        table th{
            text-align:left;
            padding:18px;
            background:#f7f8fb;
            color:var(--dark);
        }

        table td{
            padding:18px;
            border-bottom:1px solid #eee;
        }

        table tr:hover{
            background:#fafafa;
        }

        .badge{
            padding:8px 14px;
            border-radius:30px;
            font-size:13px;
            font-weight:600;
        }

        .badge.success{
            background:#dff9e8;
            color:var(--success);
        }

        /* ======================
        PRODUCTS
        ====================== */
        .products-grid{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
            gap:25px;
        }

        .product-card{
            background:white;
            border-radius:20px;
            overflow:hidden;
            box-shadow:var(--shadow);
            transition:.3s;
        }

        .product-card:hover{
            transform:translateY(-5px);
        }

        .product-card img{
            width:100%;
            height:220px;
            object-fit:cover;
        }

        .product-content{
            padding:20px;
        }

        .product-content h3{
            margin-bottom:10px;
            color:var(--dark);
        }

        .product-content p{
            color:var(--primary);
            font-weight:700;
            margin-bottom:15px;
        }

        .product-actions{
            display:flex;
            gap:10px;
        }

        /* ======================
        EMPLOYES
        ====================== */
        .employee-avatar{
            width:50px;
            height:50px;
            border-radius:50%;
            object-fit:cover;
        }


        /* ======================
        SCROLLBAR
        ====================== */
        ::-webkit-scrollbar{
            width:8px;
        }

        ::-webkit-scrollbar-thumb{
            background:var(--primary);
            border-radius:20px;
        }

        /* ======================
        RESPONSIVE
        ====================== */

        /* TABLET */
        @media(max-width:1200px){

            .stats{
                grid-template-columns:repeat(2,1fr);
            }

            .charts{
                grid-template-columns:1fr;
            }
        }

        /* TABLET + SMALL LAPTOP */
        @media(max-width:992px){

            .sidebar{
                width:90px;
            }

            .main{
                margin-left:90px;
            }

            .menu li{
                justify-content:center;
                font-size:0;
            }

            .menu li i{
                font-size:22px;
            }
        }

        /* MOBILE */
        @media(max-width:768px){

            .sidebar{
                width:70px;
            }

            .main{
                margin-left:70px;
                padding:15px;
            }

            .stats{
                grid-template-columns:1fr;
            }

            table{
                display:block;
                overflow-x:auto;
                white-space:nowrap;
            }

        }

        .dataTables_filter{
            margin-bottom:20px;
        }

        .dataTables_filter input{
            border:1px solid #ddd !important;
            border-radius:12px !important;
            padding:10px 15px !important;
            margin-left:10px !important;
            outline:none;
        }

        .dataTables_filter input:focus{
            border-color:var(--primary) !important;
            box-shadow:none !important;
        }

        .dataTables_length select{
            border-radius:10px;
            padding:5px 10px;
            background: #fff;
            border: solid 1px grey;
            margin-bottom: 10px;
        }

        /* Ligne contenant info + pagination */
        .dataTables_wrapper .row:last-child{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-top:20px;
        }

        /* Bloc pagination */
        .dataTables_wrapper .dataTables_paginate{
            display:flex;
            justify-content:flex-end;
            align-items:center;
        }

        /* Liste pagination Bootstrap */
        .dataTables_wrapper .pagination{
            display:flex;
            align-items:center;
            gap:8px;
            margin:0;
        }

        /* Boutons */
        .dataTables_wrapper .page-item .page-link{
            width:42px;
            height:42px;

            display:flex;
            align-items:center;
            justify-content:center;

            border:none;
            border-radius:12px;

            background:#fff;
            color:var(--dark);

            box-shadow:0 4px 12px rgba(0,0,0,.08);

            transition:.3s;
        }

        /* Hover */
        .dataTables_wrapper .page-item .page-link:hover{
            background:var(--primary);
            color:#fff;
        }

        /* Active */
        .dataTables_wrapper .page-item.active .page-link{
            background:linear-gradient(
                135deg,
                var(--primary),
                var(--secondary)
            );
            color:#fff;
        }

        /* Disabled */
        .dataTables_wrapper .page-item.disabled .page-link{
            opacity:.4;
        }

        /* Enlever les puces */
        .dataTables_wrapper .pagination{
            list-style:none !important;
            padding:0;
            margin:0;
        }

        /* Enlever le soulignement */
        .dataTables_wrapper .pagination a,
        .dataTables_wrapper .paginate_button a,
        .dataTables_wrapper .page-link{
            text-decoration:none !important;
        }

        /* Au survol aussi */
        .dataTables_wrapper .pagination a:hover,
        .dataTables_wrapper .paginate_button a:hover,
        .dataTables_wrapper .page-link:hover{
            text-decoration:none !important;
        }

        /* Focus Bootstrap */
        .dataTables_wrapper .page-link:focus{
            box-shadow:none !important;
            outline:none !important;
        }

        #commandesContainer {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .ticket-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .commande-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #eee;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
        }

    </style>
</head>

<body>

    <aside class="sidebar">

    <div class="logo">
        <span id="etabName"></span>
        <hr style="margin-top:20px;">
    </div>

    <ul class="menu">

        <li class="active" data-target="dashboard">
            <i class="fa-solid fa-chart-line"></i>
            Dashboard
        </li>

        <li data-target="tables">
            <i class="fa-solid fa-chair"></i>
            Tables
        </li>

        <li data-target="produits">
            <i class="fa-solid fa-burger"></i>
            Produits
        </li>

        <li data-target="employes">
            <i class="fa-solid fa-users"></i>
            Employés
        </li>

        <li data-target="commandes">
            <i class="fa-solid fa-receipt"></i>
            Commandes
        </li>

        <li data-target="logout" id="logoutBtn">
            <i class="fas fa-sign-out-alt"></i>
            <a href="#" style="color: #fff; text-decoration: none;">Deconnexion</a>
            
        </li>

    </ul>

</aside>

<!-- MAIN -->
<main class="main">

    <!-- HEADER -->
    <header class="header">

        <div>
            <h2>Bonjour <span id="userLogin"></span> 👋</h2>
            <p>Bienvenue sur Gusto Manager</p>
        </div>

        <div class="header-actions">

            <button class="notification-btn">
                <i class="fa-solid fa-bell"></i>
            </button>

            <div class="avatar" id="profileBtn"></div>

        </div>

    </header>

    <!-- DASHBOARD -->
    <section id="dashboard" class="content-section">

    <div class="stats">

        <div class="card stat-card">
            <i class="fa-solid fa-utensils"></i>
            <h3>Services  journalier</h3>
            <h1>14</h1>
        </div>

        <div class="card stat-card">
            <i class="fa-solid fa-money-bill-wave"></i>
            <h3>Gains Journalier</h3>
            <h1>150 000 FCFA</h1>
        </div>

        <div class="card stat-card">
            <i class="fa-solid fa-cart-shopping"></i>
            <h3>Commandes journalier</h3>
            <h1>327</h1>
        </div>

    </div>

    <!-- CHARTS -->
    <div class="charts">

        <div class="card chart-card">
            <h3>Montants Mensuels</h3>
            <canvas id="barChart"></canvas>
        </div>

        <div class="card chart-card">
            <h3>Répartition des Revenus</h3>
            <canvas id="pieChart"></canvas>
        </div>

    </div>

</section>

    <!-- TABLES -->
    <section id="tables" class="content-section" style="display:none">

        <div class="section-header">
            <h2>Gestion des Tables</h2>

            <button class="btn-primary">
                <i class="fa-solid fa-plus"></i> Ajouter
            </button>
        </div>

        <table class="DataTable info-table">
            <thead>
                <tr>
                    <th>Table</th>
                    <th>Etat</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="tableBody"></tbody>
        </table>

    </section>

    <!-- PRODUITS -->
    <section id="produits" class="content-section" style="display:none">

        <div class="section-header">
            <h2>Produits</h2>

            <button class="btn-primary">
                <i class="fa-solid fa-plus"></i> Ajouter Produit
            </button>
        </div>

        <table class="DataTable info-produit">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="produitBody"></tbody>
        </table>

    </section>

    <!-- EMPLOYES -->
    <section id="employes" class="content-section" style="display:none">

        <div class="section-header">
            <h2>Employés</h2>

            <button class="btn-primary">
                <i class="fa-solid fa-plus"></i> Ajouter Employé
            </button>
        </div>

        <table class="DataTable info-user">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>login</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="userBody"></tbody>
        </table>

    </section>

    <section id="commandes" class="content-section" style="display:none">
        <div id="commandesContainer"></div>
    </section>

</main>

    <script src="./assets/vendor/jquery/jquery.min.js"></script>
    <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/vendor/datatables/jquery.datatables.min.js"></script>
    <script src="./assets/vendor/datatables/datatables.bootstrap4.min.js"></script>
    <script src="./assets/vendor/datatables/datatables-demo.js"></script>
    <script src="./assets/vendor/custom-file-input/custom-file-input.js"></script>
    <script src="./assets/vendor/build/intlTelInput.js"></script>
    <script src="./assets/js/chart.js"></script>
    <script src="./assets/js/main.js"></script>

</body>

</html>