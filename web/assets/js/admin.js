//nkui
function test(){
    var tabsNewAnim = $('#navbarSupportedContent');
    var selectorNewAnim = $('#navbarSupportedContent').find('.nav-item').length;
    var activeItemNewAnim = tabsNewAnim.find('.active');
    var activeWidthNewAnimHeight = activeItemNewAnim.innerHeight();
    var activeWidthNewAnimWidth = activeItemNewAnim.innerWidth();
    var itemPosNewAnimTop = activeItemNewAnim.position();
    var itemPosNewAnimLeft = activeItemNewAnim.position();
    $(".hori-selector").css({
        "top":itemPosNewAnimTop.top + "px", 
        "left":itemPosNewAnimLeft.left + "px",
        "height": activeWidthNewAnimHeight + "px",
        "width": activeWidthNewAnimWidth + "px"
    });
    $("#navbarSupportedContent").on("click","li",function(e){
        $('#navbarSupportedContent ul li').removeClass("active");
        $(this).addClass('active');
        var activeWidthNewAnimHeight = $(this).innerHeight();
        var activeWidthNewAnimWidth = $(this).innerWidth();
        var itemPosNewAnimTop = $(this).position();
        var itemPosNewAnimLeft = $(this).position();
        $(".hori-selector").css({
            "top":itemPosNewAnimTop.top + "px", 
            "left":itemPosNewAnimLeft.left + "px",
            "height": activeWidthNewAnimHeight + "px",
            "width": activeWidthNewAnimWidth + "px"
        });
    });
}


$(document).ready(function(){
    setTimeout(function(){ test(); });
});
$(window).on('resize', function(){
    setTimeout(function(){ test(); }, 500);
});
$(".navbar-toggler").click(function(){
    $(".navbar-collapse").slideToggle(300);
    setTimeout(function(){ test(); });
});

// --------------add active class-on another-page move----------
jQuery(document).ready(function($){
    // Get current path and find target link
    var path = window.location.pathname.split("/").pop();

    // Account for home page with empty path
    if ( path == '' ) {
        path = 'index.html';
    }

    var target = $('#navbarSupportedContent ul li a[href="'+path+'"]');
    // Add active class to target link
    target.parent().addClass('active');
});

$(document).ready(function(){
    $('.link_page a').click(function(e){
        e.preventDefault()
        var cible=$(this).data('target')
        $('.content').hide()
        $('#' + cible).show()
        $('.link_page a').removeClass('active')
        $(this).addClass('active')
    })
})

$('.info-con').click(function(){
    $('.modal-info').modal({backdrop:'static',keyboard:false})
})


$('.btn-menu').click(function(){
    $('.modal-m button[type=submit]').text("Ajouter")
    $('input, textarea, select').val("")
    $('#blah, #logo').attr('src','')
    $('.modal-m .modal-title').text("Ajouter un produit")
    $('.modal-m').modal({backdrop:'static',keyboard:false})
})

$('.btn-serveur').click(function(){
    $('.modal-s button[type=submit]').text("Ajouter")
    $('input, textarea , select').val("")
    $('.modal-s .modal-title').text("Ajouter un serveur")
    $('.modal-s').modal({backdrop:'static',keyboard:false})
})

$('.btn-user').click(function(){
    $('.modal-user button[type=submit]').text("Ajouter")
    $('input, textarea , select').val("")
    $('#logo, #blah').attr('src','')
    $('.modal-user .modal-title').text("Ajouter un utilisateur")
    $('.modal-user').modal({backdrop:'static',keyboard:false})
})



$('#server').on('submit', async function(e){
  e.preventDefault();
  $('button.loading').addClass('show-loader').prop('disabled', true);
  const form = $('#server')[0];
  const data = new FormData(form);
  await fetch('./../controller/serveur.php', {
    method: 'POST',
    body: data
  })
  .then((typeRep) => {
    if(typeRep.ok){
      return typeRep.json();
    }
  })
  .then((result) =>{
    if (result[1]) {  
         $('button.loading').removeClass('show-loader').prop('disabled', false);
      $('.modal-s').modal('hide');
      const tb = $('.info-serveur');
      if ($('.id').val()!="") {
        tb.find('tbody').find('tr[data-id="'+ result['id'] +'"]').replaceWith(result['data']);
      }
      else{
        $('.dataTables_empty').hide()
        tb.find('tbody').append(result['data']);
      }
    }  
  })
})

$(document).on('click','.editserveur', function(e){
    e.preventDefault()
  elt = $(this) 
  url = elt.attr('href')
  $.post(url)
    .done(function(data){
    data=JSON.parse(data)
        if (data['backData']) {
          $('.id').val(data['idserveur'])
          $('.nom').val(data['nom']) 
          $('.login').val(data['login'])  
          $('.id').val(data['iserveur'])  
        }
    })
    $('.modal-s .modal-title').text("Modifier le serveur")
    $('.modal-s button[type=submit]').text("Mettre à jour")
    $('.modal-s').modal({backdrop:'static',keyboard:false})
})



$('#plat').on('submit', async function(e){
  e.preventDefault();
  $('button.loading').addClass('show-loader').prop('disabled', true);
  const form = $('#plat')[0];
  const data = new FormData(form);
  await fetch('./../controller/menu.php', {
    method: 'POST',
    body: data
  })
  .then((typeRep) => {
    if(typeRep.ok){
      return typeRep.json();
    }
  })
  .then((result) =>{
    if (result[1]) {  
        $('button.loading').removeClass('show-loader').prop('disabled', false);
      $('.modal-m').modal('hide');
      const tb = $('.info-menu');
      if ($('.id').val()!="") {
        tb.find('tbody').find('tr[data-id="'+ result['id'] +'"]').replaceWith(result['data']);
      }
      else{
        $('.dataTables_empty').hide()
        tb.find('tbody').append(result['data']);
      }
      $('#SumMenu').text(result['total'] + ' FCFA')
    }  
  })
})

$(document).on('click','.editmenu', function(e){
    e.preventDefault()
  elt = $(this) 
  url = elt.attr('href')
  $.post(url)
    .done(function(data){
    data=JSON.parse(data)
        if (data['backData']) {
          $('.id').val(data['idmenu'])
          $('#blah').attr('src',data['image']);
          $('.libelle').val(data['libelle'])  
          $('.categorie').val(data['idcategorie'])   
          $('.prix').val(data['prix']) 
          $('.description').val(data['description']) 
        }
    })
    $('.modal-m .modal-title').text("Modifier le produit")
    $('.modal-m button[type=submit]').text("Mettre à jour")
    $('.modal-m').modal({backdrop:'static',keyboard:false})
})

$('#user').on('submit', async function(e){
  e.preventDefault();
  $('button.loading').addClass('show-loader').prop('disabled', true);
  const form = $('#user')[0];
  const data = new FormData(form);
  await fetch('./../controller/utilisateur.php', {
    method: 'POST',
    body: data
  })
  .then((typeRep) => {
    if(typeRep.ok){
      return typeRep.json();
    }
  })
  .then((result) =>{
    if (result[1]) {  
         $('button.loading').removeClass('show-loader').prop('disabled', false);
      $('.modal-user').modal('hide');
      const tb = $('.info-user');
      if ($('.id').val()!="") {
        tb.find('tbody').find('tr[data-id="'+ result['id'] +'"]').replaceWith(result['data']);
      }
      else{
        $('.dataTables_empty').hide()
        tb.find('tbody').append(result['data']);
      }
    }  
  })
})

$(document).on('click','.edituser', function(e){
    e.preventDefault()
  elt = $(this) 
  url = elt.attr('href')
  $.post(url)
    .done(function(data){
    data=JSON.parse(data)
        if (data['backData']) {
          $('.id').val(data['idutilisateur'])
          $('.idunique').val(data['idunique'])
          $('#logo').attr('src',data['logo']);
          $('.etablisement').val(data['nomutilisateur'])  
          $('.adresse').val(data['adresse'])   
          $('.proprietaire').val(data['nomproprietaire']) 
          $('.telephone').val(data['telephone']) 
          $('.email').val(data['email']) 
          $('.description').val(data['description']) 
        }
    })
    $('.modal-user .modal-title').text("Modifier l'utilisateur")
    $('.modal-user button[type=submit]').text("Mettre à jour")
    $('.modal-user').modal({backdrop:'static',keyboard:false})
})

let tableCommande;

$(document).ready(function () {
    const table = $('.info-commande');
    if (!table.length) return console.error("❌ Tableau '.info-commande' introuvable !");

    if ($.fn.DataTable.isDataTable(table)) table.DataTable().destroy();

    tableCommande = table.DataTable({
        order: [[3, 'desc']],
        footerCallback: function () {
            const api = this.api();
            const total = api.column(2, { page: 'current' }).data().reduce((a, b) => {
                const x = typeof a === 'string' ? a.replace(',', '.') : a;
                const y = typeof b === 'string' ? b.replace(',', '.') : b;
                return parseFloat(x || 0) + parseFloat(y || 0);
            }, 0);
            $('#SumCommande').html(Math.round(total) + ' FCFA');
        }
    });

    // 🔹 ID unique du restaurant passé depuis PHP
    const ID_UNIQUE_RESTAURANT = document.body.dataset.idunique;

    // 🔹 Connexion WebSocket
    const socket = new WebSocket("ws://192.168.100.238:8080");

    socket.onopen = () => {
        console.log("✅ WebSocket admin connecté");

        // 🔹 Enregistrement du client admin
        socket.send(JSON.stringify({
            type: "register",
            idunique: ID_UNIQUE_RESTAURANT
        }));
    };

    socket.onerror = (err) => console.error("❌ Erreur WebSocket admin", err);
    socket.onclose = () => console.warn("⚠️ WebSocket admin déconnecté");

    socket.onmessage = async (event) => {
        let rawData = event.data instanceof Blob ? await event.data.text() : event.data;

        let data;
        try { data = JSON.parse(rawData); } 
        catch { console.warn("⚠️ Message ignoré (pas JSON)"); return; }

        //table terminer

        if (data.type === "table_terminee") {

        // 🔐 Sécurité : uniquement le bon restaurant
        if (data.idunique !== ID_UNIQUE_RESTAURANT) return;

        $('.notification').html(`🧾 La table <b>${data.table}</b> a terminé sa commande <i class="icofont-close" style="cursor: pointer;"></i>`).slideDown();

        new Audio("./../notification.mp3").play();

        console.log(`ℹ️ Table ${data.table} terminée`);
        return; // ⛔ on s’arrête ici
    }

        if (data.type !== "nouvelle_commande") return;

        let commande = data.commande;
        if (typeof commande === "string") {
            try { commande = JSON.parse(commande); } 
            catch { console.warn("❌ Commande invalide :", data.commande); return; }
        }
        if (!Array.isArray(commande)) return console.warn("❌ Commande non conforme");

        // 🔔 Notification
        $('.notification').html(`Vous avez une nouvelle commande de la table <b>${data.table}</b> en attente <i class="icofont-close" style="cursor: pointer;"></i>`).slideDown();
        new Audio("./../notification.mp3").play();

        // 🔹 Construire le texte pour le tableau
        const commandeStr = commande.map(item => `${item.libelle} (x${item.quantite}) : ${item.total}`).join(", ");

        // 🔹 Ajouter à DataTable
        let newRow = tableCommande.row.add([
            data.table,
            commandeStr,
            data.montant,
            data.date,
            "<span class='position' style='color:red;font-weight:bold;'>En attente</span>",
            `<a href="./../controller/viewcommande.php?id=${data.idcommande}" class='btn btn-warning viewcommande icofont-eye'></a>`
        ]).draw(false).node();

        $(newRow).attr('data-id', data.idcommande);
        $(newRow).prependTo('.info-commande tbody');

        console.log(`✅ Nouvelle commande reçue pour la table ${data.table}`);
    };
});




$(document).on('click', '.icofont-close', function() {
    $('.notification').slideUp();
});


$(document).ready(function() {
    var table = $('.info-menu');

    if ( $.fn.DataTable.isDataTable( table ) ) {
        table.DataTable().destroy(); // détruit l’ancienne instance
    }

    table.DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();

            // Calculer la somme uniquement sur les lignes visibles
            var total = api
                .column(2, { page: 'current' }) // colonne Montant
                .data()
                .reduce(function (a, b) {
                    var x = typeof a === 'string' ? a.replace(',', '.') : a;
                    var y = typeof b === 'string' ? b.replace(',', '.') : b;
                    return parseFloat(x) + parseFloat(y);
                }, 0);

            // Mettre à jour le pied de tableau
            $('#SumMenu').html(Math.round(total) + ' FCFA')
        }
    });
});


$(document).on('click','.viewcommande', function(e){
    e.preventDefault()
    elt = $(this) 
    url = elt.attr('href')
    $.post(url)
    .done(function(data){
        data=JSON.parse(data)
            if (data['backData']) {
              $('.modal-f .modal-title').html("Table N°"+data['idtable']);
              $('.fichePanier').html('');
              // Parser la colonne commande
                let commandes = JSON.parse(data.commande);

                let totalGeneral = 0;

                // Boucle sur les commandes
                commandes.forEach(function(item){
                    totalGeneral += parseInt(item.total);

                    $('.fichePanier').append(`
                        <tr>
                            <td>${item.libelle}</td>
                            <td>${item.quantite}</td>
                            <td>${item.total} FCFA</td>
                        </tr>
                    `);
                });
                $('.id').val(data['idcommande'])  
              $('.montantFinal').html(data['montant_a_payer'] + ' FCFA')  
            }
    })
    $('.modal-f').modal({backdrop:'static',keyboard:false});
});


$(document).on('click', '#btn-print', function (e) {
    e.preventDefault();
    let commande = [];
    let idCommande = $(this).closest('.modal').find('.id').val();
    let idtable = $(this).closest('.modal').find('.modal-title').text();
    // Parcourir les lignes du tableau
    $('.fichePanier tr').each(function () {
        let libelle = $(this).find('td').eq(0).text();
        let qte = $(this).find('td').eq(1).text();
        let total = $(this).find('td').eq(2).text().replace(' FCFA', '');

        commande.push({
            libelle: libelle,
            quantite: qte,
            total: total
        });
    });
    let montantFinal = $('.montantFinal').text().replace(' FCFA', '');
    // Envoi vers PHP
    $.ajax({
        url: './../controller/print_ticket.php',
        method: 'POST',
        data: {
            idcommande: idCommande,
            idtable: idtable,
            commande: JSON.stringify(commande),
            montantFinal: montantFinal
        },
        success: function (response) {
            result = JSON.parse(response)  
             if (result[1]) {
                // Récupérer l'instance de DataTable
                let tb = $('.info-commande').DataTable();

                // Trouver la ligne correspondant à l'idcommande
                let row = tb.rows().nodes().to$().filter('[data-id="' + result['id'] + '"]');

                // Mettre à jour la cellule "Statut" (5ème colonne, index 4)
                tb.cell(row, 5).data('<span style="color:green;font-weight:bold;">Achevé</span>').draw(false);

                // Fermer le modal
                $('.modal-f').modal('hide');

             }
        },
        error: function () {
            alert('Erreur lors de l’impression');
        }
    });
});


 







