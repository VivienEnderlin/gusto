const token = localStorage.getItem('token');
let allOrders = []; // on stocke toutes les commandes
const tableMap = new Map();
const menuItems = document.querySelectorAll(".menu li");
const sections = document.querySelectorAll(".content-section");

document.getElementById('dateDebut').addEventListener('change', renderFilteredOrders);
document.getElementById('dateFin').addEventListener('change', renderFilteredOrders);


document.addEventListener("DOMContentLoaded", async function () {
    const aujourdHui = new Date();
    const annee = aujourdHui.getFullYear();

    document.getElementById('dateDebut').value = `${annee}-01-01`;
    document.getElementById('dateFin').value = aujourdHui.toISOString().split('T')[0];

    if (!token) {
        console.error("Token manquant !");
        return;
    }

    const payload = token ? parseJwt(token) : null;
    const idEtab = payload?.data?.id_etablissement;

    fetch('/api-commande/routes/etablissement.php', {
        headers: {'Authorization': 'Bearer ' + token}
    })
    .then(res => res.json())
    .then(result => {

        if (!result?.success) return;

        // on cherche l’établissement du token
        const etab = result.data.find(item => item[5] == idEtab);

        if (!etab) return;

        // 🖼️ logo
        document.querySelector('.avatar').innerHTML = etab[0];

        // 🏷️ nom
        document.getElementById('etabName').textContent = etab[1];

    });

    try {

        // ======================
        // CHARGEMENT PARALLÈLE
        // ======================
        const [statsRes, tableRes, produitRes, userRes, orderRes, catsRes] = await Promise.all([

            fetch('/api-commande/routes/statistique.php', {
                method: 'GET',
                headers: {'Authorization': 'Bearer ' + token}
            }).then(r => r.json()),

            fetch('/api-commande/routes/table.php', {
                method: 'GET',
                headers: {'Authorization': 'Bearer ' + token}
            }).then(r => r.json()),

            fetch('/api-commande/routes/produit.php', {
                method: 'GET',
                headers: {'Authorization': 'Bearer ' + token}
            }).then(r => r.json()),

            fetch('/api-commande/routes/employe.php', {
                method: 'GET',
                headers: {'Authorization': 'Bearer ' + token}
            }).then(r => r.json()),

            fetch('/api-commande/routes/commande.php', {
                method: 'GET',
                headers: {'Authorization': 'Bearer ' + token}
            }).then(r => r.json()),

            fetch('/api-commande/routes/categorie.php', {
                method: 'GET',
                headers: {'Authorization': 'Bearer ' + token}
            }).then(r => r.json())

        ]);

        console.log("STATS:", statsRes);
        console.log("TABLES:", tableRes);
        console.log("PRODUITS:", produitRes);
        console.log("USERS:", userRes);
        console.log("ORDERS:", orderRes);
        console.log("CATS:", catsRes);

        // ======================
        // GRAPHIQUES
        // ======================
        
        if (statsRes?.success && Array.isArray(statsRes.stats)) {

            const moisNoms = [
                "Jan", "Fév", "Mar", "Avr", "Mai", "Juin",
                "Juil", "Août", "Sep", "Oct", "Nov", "Déc"
            ];

            // ======================
            // GRAPHIQUE BARRE
            // ======================

            const labels = statsRes.stats.map(i => moisNoms[i.mois - 1]);
            const values = statsRes.stats.map(i => Number(i.total));

            new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Total',
                        data: values,
                        backgroundColor: '#ff7a00',
                        borderRadius: 10
                    }]
                }
            });

            // ======================
            // GRAPHIQUE CAMEMBERT
            // ======================

            new Chart(document.getElementById('pieChart'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#ff7a00', '#ff8924', '#ff9535', '#ffa245',
                            '#ffaf57', '#ffbc69', '#ffd08a', '#ffdca6',
                            '#ffe6c0', '#fff0d6', '#fff9f0'
                        ]
                    }]
                }
            });

            // ======================
            // CARDS DASHBOARD
            // ======================

            if (statsRes.vals) {

                let totalServices = 0;
                let totalCmd = 0;
                let totalGain = 0;

                // Services
                if (Array.isArray(statsRes.vals.services)) {
                    statsRes.vals.services.forEach(item => {
                        totalServices += Number(item.nb_services || 0);
                    });
                }

                // Commandes
                if (Array.isArray(statsRes.vals.commandes)) {
                    statsRes.vals.commandes.forEach(item => {
                        totalCmd += Number(item.nb_commandes || 0);
                    });
                }

                // Gains
                if (Array.isArray(statsRes.vals.gains)) {
                    statsRes.vals.gains.forEach(item => {
                        totalGain += Number(item.sum_commandes || 0);
                    });
                }

                // Affichage
                $("#svc").text(totalServices);
                $("#cmd").text(totalCmd);
                $("#gain").text(totalGain.toLocaleString('fr-FR') + " FCFA");
            }
        }

        // ======================
        // TABLES
        // ======================
        if (tableRes?.success && Array.isArray(tableRes.data)) {

            tables.clear();
            tableMap.clear();

            tableRes.data.forEach(table => {
                tableMap.set(table.id_table, table.nom);

                const isOpen = table.statu === "Ouvert";

                tables.row.add([
                    table.nom,
                    `<span class="badge ${isOpen ? "success" : "danger"}">
                        ${isOpen ? "Ouvert" : "Fermé"}
                    </span>`,
                    `
                    <button class="icon-btn view view-service" data-id="${table.id_table}">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <button class="icon-btn edit-table" data-id="${table.id_table}">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="icon-btn qr" data-id="${table.id_table}">
                        <i class="fa-solid fa-qrcode"></i>
                    </button>
                    <button class="icon-btn danger delete-table" data-id="${table.id_table}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    `
                ]);

            });

            tables.draw();
        }

        // ======================
        // PRODUITS
        // ======================
        if (produitRes?.success && Array.isArray(produitRes.data)) {

            produits.clear();

            produitRes.data.forEach(produit => {

                const image = produit.image?.length
                    ? `<img src="${produit.image[0]}" width="50" height="50" style="object-fit:cover;border-radius:5px;">`
                    : 'Aucune image';

                produits.row.add([
                    image,
                    produit.nom,
                    produit.prix,
                    `<button class="icon-btn edit-produit" data-id="${produit.id_produit}">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="icon-btn danger delete-produit" data-id="${produit.id_produit}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    `
                ]);

            });

            produits.draw();
        }

        // ======================
        // CATEGORIE
        // ======================

        if (catsRes?.success && Array.isArray(catsRes.data)) {

            cats.clear();

            catsRes.data.forEach(cat => {

                cats.row.add([
                    cat.libelle,
                    `<button class="icon-btn edit-cat" data-id="${cat.id_categorie}">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="icon-btn danger delete-cat" data-id="${cat.id_categorie}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    `
                ]);

            });

            cats.draw();
        }

        // ======================
        // UTILISATEUR
        // ======================
        if (userRes?.success && Array.isArray(userRes.data)) {

            users.clear();

            userRes.data.forEach(utilisateur => {

                users.row.add([
                    utilisateur.nom,
                    utilisateur.login,
                    utilisateur.date_enreg,
                    `
                    <button class="icon-btn edit-user" data-id="${utilisateur.id_utilisateur}">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="icon-btn danger delete-user" data-id="${utilisateur.id_utilisateur}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    `
                ]);

            });

            users.draw();
        }

        const selects = document.querySelectorAll('.select');

        if (catsRes.success && Array.isArray(catsRes.data)) {
            catsRes.data.forEach(categorie => {
                selects.forEach(select => {
                    const option = document.createElement('option');

                    option.value = categorie.id_categorie;
                    option.textContent = "\u00A0\u00A0\u00A0" + categorie.libelle;

                    select.appendChild(option);
                });
            });
        }

        // ======================
        // COMMANDES
        // ======================
        if (orderRes?.success && Array.isArray(orderRes.data)) {
            allOrders = orderRes.data; // stock global

            renderFilteredOrders(); // affichage initial
        }
    } catch (err) {
        console.error("ERREUR GLOBAL:", err);
    }


});

function renderFilteredOrders() {

    const container = document.getElementById('commandesContainer');
    container.innerHTML = '';

    const dateDebut = document.getElementById('dateDebut').value;
    const dateFin = document.getElementById('dateFin').value;

    let filtered = [...allOrders];

    filtered = filtered.map(ticket => ({
        ...ticket,
        table_nom: tableMap.get(ticket.id_table) || "Table inconnue"
    }));

    if (dateDebut) {
        filtered = filtered.filter(ticket =>
            new Date(ticket.date_enreg.replace(' ', 'T')) >= new Date(dateDebut)
        );
    }

    if (dateFin) {
        filtered = filtered.filter(ticket =>
            new Date(ticket.date_enreg.replace(' ', 'T')) <= new Date(dateFin)
        );
    }

    filtered.forEach(ticket => {

        let itemsHTML = '';

        ticket.commandes.forEach(cmd => {

            const badgeClass = cmd.etat === "Servi"
                ? "badge-success"
                : "badge-warning";

            itemsHTML += `
                <div class="commande-item">
                    <div>
                        <strong>${cmd.libelle}</strong><br>
                        <small>${cmd.quantite} x ${cmd.prix} ${ticket.devise}</small>
                    </div>

                    <div>
                        <span class="${badgeClass}">
                            ${cmd.etat}
                        </span>
                        <div><b>${cmd.total} ${ticket.devise}</b></div>
                    </div>
                </div>
            `;
        });

        container.innerHTML += `
            <div class="ticket-card">

                <div class="ticket-header">
                    <div>
                        <h3>${ticket.table_nom}: ${ticket.id_ticket}</h3>
                        <small>${ticket.date_enreg}</small>
                    </div>

                    <div>
                        <h3>${ticket.montant_total} ${ticket.devise}</h3>
                    </div>
                </div>

                <div class="ticket-body">
                    ${itemsHTML}
                </div>

            </div>
        `;
    });
}



function showSection(id) {
    sections.forEach(sec => {
        sec.style.display = (sec.id === id) ? "block" : "none";
    });
}

menuItems.forEach(item => {
    item.addEventListener("click", () => {

        menuItems.forEach(i => i.classList.remove("active"));
        item.classList.add("active");

        const target = item.getAttribute("data-target");
        showSection(target);

    });
});


function parseJwt(token) {
    try {
        // Extraire la partie "payload" du token (la deuxième section après les points)
        const base64Url = token.split('.')[1];

        // Remplacer les caractères spécifiques URL-safe par des caractères base64 standard
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');

        // Décoder la chaîne base64 en JSON
        const jsonPayload = decodeURIComponent(
            atob(base64) // atob décode la chaîne base64 en texte
            .split('')    // transformer chaque caractère en tableau
            .map(function(c) { 
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2); 
            })
            .join('')     // reconstituer la chaîne encodée pour decodeURIComponent
        );

        // Convertir la chaîne JSON en objet JavaScript
        return JSON.parse(jsonPayload);
    } catch(e) {
        // En cas d'erreur (token malformé ou absent), retourner null
        return null;
    }
}

// const headers = {
//     'Content-Type': 'application/json',
//     'Authorization': 'Bearer ' + token
// };

if (token) {
    const payload = parseJwt(token);

    if (payload && payload.data && payload.data.login) {
        document.getElementById('userLogin').textContent = payload.data.login;
    } 
    else {
        document.getElementById('userLogin').textContent = 'Invité';
    }
} 
else {
  window.location.href = './user.php';
}

document.getElementById('logoutBtn').addEventListener('click', function(e) {
  e.preventDefault();
  // Supprimer le token du localStorage
  localStorage.removeItem('token');
  // Rediriger vers la page de login
  window.location.href = './user.php';
});


$('#profileBtn').on('click', function() {
    $('.modal-login .modal-title').text("Modifier mes informations");
    $('.modal-login button[type=submit]').text("Modifier");
    let login = $('#userLogin').text().trim();
    $('#modalLoginField').val(login);
    $('.modal-login').modal({backdrop:'static', keyboard:false});
});

$('.btn-table').on('click', function() {
    $('#table')[0].reset();
    $('#table input[name="id"]').val('');
    $('.modal-table .modal-title').text("Ajouter une table");
    $('.modal-table button[type=submit]').text("Ajouter");
    $('.modal-table').modal({backdrop: 'static', keyboard: false});
});

$('.btn-produit').on('click', function() {
    $('#produit')[0].reset();
    $('#image').attr('src', '');
    $('#produit input[name="id"]').val('');
    $('.modal-produit .modal-title').text("Ajouter un produit");
    $('.modal-produit button[type=submit]').text("Ajouter");
    $('.modal-produit').modal({backdrop: 'static', keyboard: false});
});

$('.btn-user').on('click', function() {
    $('#user')[0].reset();
    $('#user input[name="id"]').val('');
    $('.modal-user .modal-title').text("Ajouter un employé");
    $('.modal-user button[type=submit]').text("Ajouter");
    $('.modal-user').modal({backdrop: 'static', keyboard: false});
});

$('.btn-cat').on('click', function() {
    $('#categorie')[0].reset();
    $('#categorie input[name="id"]').val('');
    $('.modal-cat .modal-title').text("Ajouter une catégorie");
    $('.modal-cat button[type=submit]').text("Ajouter");
    $('.modal-cat').modal({backdrop: 'static', keyboard: false});
});

imgInp.onchange = evt=>{
  const [file] = imgInp.files
  if (file) {
    image.src = URL.createObjectURL(file)
  }
}


$('#userLoginForm').on('submit', function(e){
    e.preventDefault();
    const form = this;
    const submitBtn = $(form).find('button[type="submit"]');

    submitBtn.addClass('show-loader').prop('disabled', true);

    const payload = {
        login: $(form).find('[name="login"]').val(),
        password: $(form).find('[name="password"]').val()
    };

    $.ajax({
        url: '/api-commande/routes/updateLogin.php',
        type: 'POST',
        headers: {'Authorization': 'Bearer ' + token},
        data: JSON.stringify(payload),

        success: function(res){

            submitBtn.removeClass('show-loader').prop('disabled', false);

            if(res.success){

                localStorage.setItem('token', res.token);

                document.getElementById('userLogin').textContent =
                    parseJwt(res.token).data.login;

                $('.modal-login').modal('hide');

            } else {
                alert(res.message);
            }
        },

        error: function(){
            submitBtn.removeClass('show-loader').prop('disabled', false);
            alert('Erreur serveur');
        }
    });
});

let editingRow;
let tables = $('.info-table').DataTable({
    pageLength: 5,
    language:{
        paginate:{
            previous:"<i class='fas fa-angle-left'></i>",
            next:"<i class='fas fa-angle-right'></i>"
        }
    }
});
let produits = $('.info-produit').DataTable({
    pageLength: 5,
    language:{
        paginate:{
            previous:"<i class='fas fa-angle-left'></i>",
            next:"<i class='fas fa-angle-right'></i>"
        }
    }
});
let users = $('.info-user').DataTable({
    pageLength: 5,
    language:{
        paginate:{
            previous:"<i class='fas fa-angle-left'></i>",
            next:"<i class='fas fa-angle-right'></i>"
        }
    }
});

let cats = $('.info-cat').DataTable({
    pageLength: 5,
    language:{
        paginate:{
            previous:"<i class='fas fa-angle-left'></i>",
            next:"<i class='fas fa-angle-right'></i>"
        }
    }
});

/* =========================
       TABLE
    ========================= */

$('#table').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const response = await fetch('/api-commande/routes/table.php', {
            method: 'POST',
            headers: {'Authorization': 'Bearer ' + token},
            body: formData
        });
        const result = await response.json();
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        if(result.success) {
            submitBtn.removeClass('show-loader').prop('disabled', false);
            $('.modal-table').modal('hide');
            form.reset();
            const isOpen = result.data.statu === "Ouvert";
            const rowData = [
                result.data.nom,
                `<span class="badge ${isOpen ? "success" : "danger"}">
                    ${isOpen ? "Ouvert" : "Fermé"}
                </span>`,
                `
                <button class="icon-btn view view-service" data-id="${result.data.id_table}">
                    <i class="fa-solid fa-eye"></i>
                </button>
                <button class="icon-btn edit-table" data-id="${result.data.id_table}">
                    <i class="fa-solid fa-pen"></i>
                </button>
                <button class="icon-btn qr" data-id="${result.data.id_table}">
                    <i class="fa-solid fa-qrcode"></i>
                </button>
                <button class="icon-btn danger delete-table" data-id="${result.data.id_table}">
                    <i class="fa-solid fa-trash"></i>
                </button>`,
                result.data.id_table,
                result.data.id_etablissement
            ];

            if(isEdit && editingRow) {
                // ⚡ Mettre à jour uniquement la ligne modifiée
                editingRow.data(rowData).draw(false);
                editingRow = null; // reset la référence
            } else {
                tables.row.add(rowData).draw(false);
            }
        } else {
            alert(result.message || "Erreur lors de l'enregistrement");
        }
    } catch(err) {
        console.error(err);
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        alert("Erreur serveur : " + err.message);
    }
});



$(document).on('click', '.view-service', async function() {
    const idTable = $(this).data('id');
    editingRow = tables.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-commande/routes/service.php?id_table=${idTable}`, {
            headers: {'Authorization': 'Bearer ' + token}
        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('.modal-service input[name="id"]').val(idTable);
            $('.code').html('<b>Code du service</b>'+' : '+e.code);
            $('.date_ouverture').html("<b>Date d'ouverture</b>"+' : '+e.date_heure_ouverture);
            $('.date_fermeture').html('<b>Date de fermeture</b>'+' : '+e.date_heure_fermeture);
            $('.user').html('<b>Serveur</b>'+' : '+e.login);
            $('.modal-service .modal-title').text("detail du service");
            $('.modal-service').modal({backdrop:'static', keyboard:false});
        } else {
            alert(result.message);
        }
    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});



$(document).on('click', '.edit-table', async function() {
    const tableId = $(this).data('id');
    editingRow = tables.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-commande/routes/table.php?id=${tableId}`, {
            headers: {'Authorization': 'Bearer ' + token}
        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#table input[name="id"]').val(tableId);
            $('#table input[name="nom"]').val(e.nom);
            $('.modal-table .modal-title').text("Modifier la table");
            $('.modal-table button[type=submit]').text("Modifier");
            $('.modal-table').modal({backdrop:'static', keyboard:false});
        } else {
            alert(result.message);
        }
    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});



$(document).on('click', '.delete-table', async function () {
    const id = $(this).data('id');
    if (!confirm("Voulez-vous vraiment supprimer cette table ?")) return;
    try {
        const response = await fetch(`/api-commande/routes/table.php?id=${id}`, {
                method: 'DELETE',
                headers: {'Authorization': 'Bearer ' + token}
            }
        );
        const result = await response.json();
        if (result.success) {
            // Supprime uniquement la ligne concernée dans le DataTable
            tables.rows().every(function () {
                const row = this.node();
                if ($(row).find('.delete-table').data('id') == id) {
                    this.remove().draw(false);
                }
            });
        } else {
            alert(result.message || "Impossible de supprimer la table");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});



$(document).on('click', '.qr', async function () {
    const id = $(this).data('id');
    try {
        const response = await fetch(`/api-commande/routes/qrcode.php?id=${id}`, {
            method: 'GET',
            headers: {'Authorization': 'Bearer ' + token}
        });
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(errorText || 'Erreur génération QR');
        }
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = `qrcode_table_${id}.png`;

        document.body.appendChild(a);
        a.click();

        a.remove();
        window.URL.revokeObjectURL(url);

    } catch (err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

/* =========================
       CATEGORIE
    ========================= */


$('#categorie').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const response = await fetch('/api-commande/routes/categorie.php', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token},
            body: formData
        });
        const result = await response.json();
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        if(result.success) {
            submitBtn.removeClass('show-loader').prop('disabled', false);
            $('.modal-cat').modal('hide');
            form.reset();
            const rowData = [
                result.data.libelle,
                `<button class="icon-btn edit-cat" data-id="${result.data.id_categorie}">
                    <i class="fa-solid fa-pen"></i>
                </button>
                <button class="icon-btn danger delete-cat" data-id="${result.data.id_categorie}">
                    <i class="fa-solid fa-trash"></i>
                </button>`,
                result.data.id_categorie,
                result.data.id_etablissement
            ];

            if(isEdit && editingRow) {
                editingRow.data(rowData).draw(false);
                editingRow = null; // reset la référence
            } else {
                cats.row.add(rowData).draw(false);
            }
        } else {
            alert(result.message || "Erreur lors de l'enregistrement");
        }
    } catch(err) {
        console.error(err);
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        alert("Erreur serveur : " + err.message);
    }
});


$(document).on('click', '.edit-cat', async function() {
    const catId = $(this).data('id');
    editingRow = cats.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-commande/routes/categorie.php?id=${catId}`, {
            headers: {'Authorization': 'Bearer ' + token}
        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#categorie input[name="id"]').val(catId);
            $('#categorie input[name="libelle"]').val(e.libelle);
            $('.modal-cat .modal-title').text("Modifier la catégorie");
            $('.modal-cat button[type=submit]').text("Modifier");
            $('.modal-cat').modal({backdrop:'static', keyboard:false});

        } else {
            alert(result.message);
        }

    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

$(document).on('click', '.delete-cat', async function () {
    const id = $(this).data('id');
    if (!confirm("Voulez-vous vraiment supprimer cette categorie ?")) return;
    try {
        const response = await fetch(`/api-commande/routes/categorie.php?id=${id}`, {
                method: 'DELETE',
                headers: {'Authorization': 'Bearer ' + token}
            }
        );
        const result = await response.json();
        if (result.success) {
            // Supprime uniquement la ligne concernée dans le DataTable
            cats.rows().every(function () {
                const row = this.node();
                if ($(row).find('.delete-cat').data('id') == id) {
                    this.remove().draw(false);
                }
            });
        } else {
            alert(result.message || "Impossible de supprimer la categorie");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});


/* =========================
       PRODUIT
    ========================= */


$('#produit').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const response = await fetch('/api-commande/routes/produit.php', {
            method: 'POST',
            headers: {'Authorization': 'Bearer ' + token},
            body: formData
        });
        const result = await response.json();
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        if(result.success) {
            submitBtn.removeClass('show-loader').prop('disabled', false);
            $('.modal-produit').modal('hide');
            form.reset();
            $('#image').attr('src','');

            const image = result.data.image?.length
                    ? `<img src="${result.data.image[0]}" width="50" height="50" style="object-fit:cover;border-radius:5px;">`
                    : 'Aucune image';
            const rowData = [
                image,
                result.data.nom,
                result.data.prix,
                `<button class="icon-btn edit-produit" data-id="${result.data.id_produit}">
                    <i class="fa-solid fa-pen"></i>
                </button>
                <button class="icon-btn danger delete-produit" data-id="${result.data.id_produit}">
                    <i class="fa-solid fa-trash"></i>
                </button>`,
                result.data.id_produit,
                result.data.id_etablissement
            ];

            if(isEdit && editingRow) {
                editingRow.data(rowData).draw(false);
                editingRow = null; // reset la référence
            } else {
                produits.row.add(rowData).draw(false);
            }
        } else {
            alert(result.message || "Erreur lors de l'enregistrement");
        }
    } catch(err) {
        console.error(err);
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        alert("Erreur serveur : " + err.message);
    }
});


$(document).on('click', '.edit-produit', async function() {
    const produitId = $(this).data('id');
    editingRow = produits.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-commande/routes/produit.php?id=${produitId}`, {
            headers: {'Authorization': 'Bearer ' + token}
        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#produit input[name="id"]').val(produitId);
            const image = Array.isArray(e.image) ? e.image: JSON.parse(e.image || '[]');
            $('#image').attr('src', image[0] || '');
            $('#produit input[name="nom"]').val(e.nom);
            $('#produit select[name="id_categorie"]').val(e.id_categorie).trigger('change');
            $('#produit input[name="prix"]').val(e.prix);
            $('#produit textarea[name="description"]').val(e.description);
            $('.modal-produit .modal-title').text("Modifier le produit");
            $('.modal-produit button[type=submit]').text("Modifier");

            $('.modal-produit').modal({backdrop:'static', keyboard:false});

        } else {
            alert(result.message);
        }

    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

$(document).on('click', '.delete-produit', async function () {
    const id = $(this).data('id');
    if (!confirm("Voulez-vous vraiment supprimer ce produit ?")) return;
    try {
        const response = await fetch(`/api-commande/routes/produit.php?id=${id}`, {
                method: 'DELETE',
                headers: {'Authorization': 'Bearer ' + token}
            }
        );
        const result = await response.json();
        if (result.success) {
            // Supprime uniquement la ligne concernée dans le DataTable
            produits.rows().every(function () {
                const row = this.node();
                if ($(row).find('.delete-produit').data('id') == id) {
                    this.remove().draw(false);
                }
            });
        } else {
            alert(result.message || "Impossible de supprimer le produit");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});


/* =========================
       EMPLOYE
    ========================= */


$('#user').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const response = await fetch('/api-commande/routes/employe.php', {
            method: 'POST',
            headers: {'Authorization': 'Bearer ' + token},
            body: formData
        });
        const result = await response.json();
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        if(result.success) {
            submitBtn.removeClass('show-loader').prop('disabled', false);
            $('.modal-user').modal('hide');
            form.reset();
            const rowData = [
                result.data.nom,
                result.data.login,
                result.data.date_enreg,
                `<button class="icon-btn edit-user" data-id="${result.data.id_utilisateur}">
                    <i class="fa-solid fa-pen"></i>
                </button>
                <button class="icon-btn danger delete-user" data-id="${result.data.id_utilisateur}">
                    <i class="fa-solid fa-trash"></i>
                </button>`,
                result.data.id_utilisateur,
                result.data.id_etablissement
            ];

            if(isEdit && editingRow) {
                editingRow.data(rowData).draw(false);
                editingRow = null; // reset la référence
            } else {
                users.row.add(rowData).draw(false);
            }
        } else {
            alert(result.message || "Erreur lors de l'enregistrement");
        }
    } catch(err) {
        console.error(err);
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        alert("Erreur serveur : " + err.message);
    }
});


$(document).on('click', '.edit-user', async function() {
    const userId = $(this).data('id');
    editingRow = users.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-commande/routes/employe.php?id=${userId}`, {
            headers: {'Authorization': 'Bearer ' + token}
        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#user input[name="id"]').val(userId);
            $('#user input[name="nom"]').val(e.nom);
            $('#user input[name="login"]').val(e.login);
            $('#user input[name="telephone"]').val(e.telephone);
            $('#user input[name="email"]').val(e.email);
            $('#user input[name="adresse"]').val(e.adresse);
            $('#user select[name="role"]').val(e.role).trigger('change');
            $('.modal-user .modal-title').text("Modifier l'employé");
            $('.modal-user button[type=submit]').text("Modifier");
            $('.modal-user').modal({backdrop:'static', keyboard:false});

        } else {
            alert(result.message);
        }

    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

$(document).on('click', '.delete-user', async function () {
    const id = $(this).data('id');
    if (!confirm("Voulez-vous vraiment supprimer cet employé ?")) return;
    try {
        const response = await fetch(`/api-commande/routes/employe.php?id=${id}`, {
                method: 'DELETE',
                headers: {'Authorization': 'Bearer ' + token}
            }
        );
        const result = await response.json();
        if (result.success) {
            // Supprime uniquement la ligne concernée dans le DataTable
            users.rows().every(function () {
                const row = this.node();
                if ($(row).find('.delete-user').data('id') == id) {
                    this.remove().draw(false);
                }
            });
        } else {
            alert(result.message || "Impossible de supprimer l'employé");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});







