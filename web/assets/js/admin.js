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


let table;

// ⚡ Initialisation DataTable
table = $('.dataTable.info-ets').DataTable({});


$('.btn-ets').on('click', function() {
    $('#ets')[0].reset();
    $('#logo').attr('src', '');
    $('#ets input[name="id"]').val('');
    $('.modal-ets .modal-title').text("Ajouter un établissement");
    $('.modal-ets button[type=submit]').text("Ajouter");
    $('.modal-ets').modal({backdrop: 'static', keyboard: false});
});

// 🔹 Bouton "Modifier"
let editingRow; // variable globale pour la ligne en cours d'édition

// Bouton Edit
$(document).on('click', '.edit-btn', async function() {
    const etabId = $(this).data('id');

    // Stocker la ligne DataTable
    editingRow = table.row($(this).closest('tr'));

    try {
        const response = await fetch(`http://gusto/api-commande/routes/etablissement.php?id=${etabId}`, {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const result = await response.json();

        if(result.success) {
            const e = result.data;
            $('#ets input[name="id"]').val(etabId);
            const logos = JSON.parse(e.logo || '[]');
            $('#logo').attr('src', logos[0] || '');
            $('#ets input[name="nom"]').val(e.nom);
            $('#ets input[name="type"]').val(e.type);
            $('#ets input[name="adresse"]').val(e.adresse);
            $('#ets input[name="email"]').val(e.email);
            $('#ets input[name="telephone"]').val(e.telephone);
            $('#ets input[name="site_web"]').val(e.site_web);
            $('#ets textarea[name="description"]').val(e.description);

            $('.modal-ets .modal-title').text("Modifier l'établissement");
            $('.modal-ets button[type=submit]').text("Modifier");
            $('.modal-ets').modal({backdrop:'static', keyboard:false});
        } else {
            alert(result.message);
        }
    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

// Submit form pour modification
$('#ets').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.prop('disabled', true).text("Chargement...");

    try {
        const response = await fetch('http://gusto/api-commande/routes/etablissement.php', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token },
            body: formData
        });

        const result = await response.json();
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");

        if(result.success) {
            $('.modal-ets').modal('hide');
            form.reset();
            $('#logo').attr('src','');

            if(isEdit && editingRow) {
                // ⚡ Mettre à jour uniquement la ligne modifiée
                editingRow.data(result.data).draw(false);
                editingRow = null; // reset la référence
            } else {
                table.row.add(result.data).draw(false);
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

$(document).on('click', '.change-btn', async function () {
    const id = $(this).data('id');

    try {
        const response = await fetch(
            `http://gusto/api-commande/routes/etablissement.php?id=${id}`,
            {
                method: 'PATCH',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            }
        );

        const result = await response.json();

        if (result.success) {
            // Met à jour UNIQUEMENT la ligne concernée
            table.rows().every(function () {
                const row = this.node();
                if ($(row).find('.change-btn').data('id') == id) {
                    this.data(result.data).draw(false);
                }
            });
        } else {
            alert(result.message);
        }

    } catch (err) {
        console.error(err);
        alert("Erreur serveur");
    }
});


