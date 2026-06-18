const token = localStorage.getItem('token');


document.addEventListener("DOMContentLoaded", async function () {

    if (!token) {
        console.error("Token manquant !");
        return;
    }

    const payload = token ? parseJwt(token) : null;
    const idEtab = payload?.data?.id_etablissement;

    fetch('/api-commande/routes/etablissement.php', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
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
        const [statsRes, tableRes, produitRes, userRes, orderRes] = await Promise.all([

            fetch('/api-commande/routes/statistique.php', {
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + token }
            }).then(r => r.json()),

            fetch('/api-commande/routes/table.php', {
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + token }
            }).then(r => r.json()),

            fetch('/api-commande/routes/produit.php', {
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + token }
            }).then(r => r.json()),

            fetch('/api-commande/routes/employe.php', {
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + token }
            }).then(r => r.json()),

            fetch('/api-commande/routes/commande.php', {
                method: 'GET',
                headers: { 'Authorization': 'Bearer ' + token }
            }).then(r => r.json())

        ]);

        console.log("STATS:", statsRes);
        console.log("TABLES:", tableRes);
        console.log("PRODUITS:", produitRes);
        console.log("USERS:", userRes);
        console.log("ORDERS:", orderRes);

        // ======================
        // GRAPHIQUES
        // ======================
        if (statsRes?.success && Array.isArray(statsRes.data)) {

            const moisNoms = [
                "Jan","Fév","Mar","Avr","Mai","Juin",
                "Juil","Août","Sep","Oct","Nov","Déc"
            ];

            const labels = statsRes.data.map(i => moisNoms[i.mois - 1]);
            const values = statsRes.data.map(i => Number(i.total));

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

            new Chart(document.getElementById('pieChart'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#ff7a00','#ff8924','#ff9535','#ffa245',
                            '#ffaf57','#ffbc69','#ffd08a','#ffdca6',
                            '#ffe6c0','#fff0d6','#fff9f0'
                        ]
                    }]
                }
            });
        }

        // ======================
        // TABLES
        // ======================
        if (tableRes?.success && Array.isArray(tableRes.data)) {

            tables.clear();

            tableRes.data.forEach(table => {

                const isOpen = table.statu === "Ouvert";

                tables.row.add([
                    table.nom,
                    `<span class="badge ${isOpen ? "success" : "danger"}">
                        ${isOpen ? "Ouvert" : "Fermé"}
                    </span>`,
                    `
                    <button class="icon-btn edit-table" data-id="${table.id_table}">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="icon-btn danger drop-table" data-id="${table.id_table}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <button class="icon-btn qr" data-id="${table.id_table}">
                        <i class="fa-solid fa-qrcode"></i>
                    </button>
                    <button class="icon-btn view view-service" data-id="${table.id_table}">
                        <i class="fa-solid fa-eye"></i>
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
                    produit.id_categorie,
                    `${produit.prix} ${produit.devise}`,
                    `
                    <button class="icon-btn edit-produit" data-id="${produit.id_produit}">
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
        // UTILISATEUR
        // ======================
        if (userRes?.success && Array.isArray(userRes.data)) {

            users.clear();

            userRes.data.forEach(utilisateur => {

                users.row.add([
                    utilisateur.nom,
                    utilisateur.telephone,
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

        // ======================
        // COMMANDES
        // ======================
        if (orderRes?.success && Array.isArray(orderRes.data)) {

            const container = document.getElementById('commandesContainer');
            container.innerHTML = '';

            orderRes.data.forEach(ticket => {

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
                                <h3>Ticket #${ticket.id_ticket}</h3>
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


    } catch (err) {
        console.error("ERREUR GLOBAL:", err);
    }

});


document.addEventListener("DOMContentLoaded", () => {

    const menuItems = document.querySelectorAll(".menu li");
    const sections = document.querySelectorAll(".content-section");

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

});


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

const headers = {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + token
};

if (token) {
  const payload = parseJwt(token);

  if (payload && payload.data && payload.data.login) {
      document.getElementById('userLogin').textContent = payload.data.login;
  } else {
      document.getElementById('userLogin').textContent = 'Invité';
  }
} else {
  window.location.href = './user.php';
}

document.getElementById('logoutBtn').addEventListener('click', function(e) {
  e.preventDefault();
  // Supprimer le token du localStorage
  localStorage.removeItem('token');
  // Rediriger vers la page de login
  window.location.href = './user.php';
});