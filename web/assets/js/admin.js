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

$('.btn-ets').on('click', function() {
    $('#ets')[0].reset();
    $('#logo').attr('src', '');
    $('#ets input[name="id"]').val('');
    $('.modal-ets .modal-title').text("Ajouter un établissement");
    $('.modal-ets button[type=submit]').text("Ajouter");
    $('.modal-ets').modal({backdrop: 'static', keyboard: false});
});

$('.btn-user').on('click', function() {
    $('#user')[0].reset();
    $('#user input[name="id"]').val('');
    $('.modal-user .modal-title').text("Ajouter un utilisateur");
    $('.modal-user button[type=submit]').text("Ajouter");
    $('.modal-user').modal({backdrop: 'static', keyboard: false});
});

// ⚡ Initialisation DataTable
let editingRow;
let ets = $('.info-ets').DataTable();
let user = $('.info-user').DataTable();

// Submit 
$('#ets').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const response = await fetch('http://gusto/api-commande/routes/etablissement.php', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token },
            body: formData
        });
        const result = await response.json();
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        if(result.success) {
            submitBtn.removeClass('show-loader').prop('disabled', false);
            $('.modal-ets').modal('hide');
            form.reset();
            $('#logo').attr('src','');

            if(isEdit && editingRow) {
                // ⚡ Mettre à jour uniquement la ligne modifiée
                editingRow.data(result.data).draw(false);
                editingRow = null; // reset la référence
            } else {
                ets.row.add(result.data).draw(false);
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

// Bouton Edit
$(document).on('click', '.edit-ets', async function() {
    const etabId = $(this).data('id');
    editingRow = ets.row($(this).closest('tr'));
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

//change

$(document).on('click', '.change-ets', async function () {
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
        if (result.success && result.data) {
            // Met à jour UNIQUEMENT la ligne concernée
            ets.rows().every(function () {
                const row = this.node();
                if ($(row).find('.change-ets').data('id') == id) {
                    this.data(result.data).draw(false);
                }
            });
        } else {
            alert(result.message || "Impossible de mettre à jour la ligne");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

/*-------------USER------------------------*/

$('#user').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const response = await fetch('http://gusto/api-commande/routes/utilisateur.php', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token },
            body: formData
        });
        const result = await response.json();
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        if(result.success) {
            submitBtn.removeClass('show-loader').prop('disabled', false);
            $('.modal-user').modal('hide');
            form.reset();
            if(isEdit && editingRow) {
                // ⚡ Mettre à jour uniquement la ligne modifiée
                editingRow.data(result.data).draw(false);
                editingRow = null; // reset la référence
            } else {
                user.row.add(result.data).draw(false);
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

// Bouton Edit
$(document).on('click', '.edit-user', async function() {
    const etabId = $(this).data('id');
    editingRow = user.row($(this).closest('tr'));
    try {
        const response = await fetch(`http://gusto/api-commande/routes/utilisateur.php?id=${etabId}`, {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#user input[name="id"]').val(etabId);
            $('#user input[name="nom"]').val(e.nom);
            $('#user input[name="adresse"]').val(e.adresse);
            $('#user input[name="email"]').val(e.email);
            $('#user input[name="telephone"]').val(e.telephone);
            $('#user input[name="login"]').val(e.login);
            $('#user select[name="role"]').val(e.role);
            $('#user select[name="id_etablissement"]').val(e.id_etablissement);
            
            $('.modal-user .modal-title').text("Modifier l'utilisateur");
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

//change

$(document).on('click', '.change-user', async function () {
    const id = $(this).data('id');
    try {
        const response = await fetch(
            `http://gusto/api-commande/routes/utilisateur.php?id=${id}`,
            {
                method: 'PATCH',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            }
        );
        const result = await response.json();
        if (result.success && result.data) {
            // Met à jour UNIQUEMENT la ligne concernée
            user.rows().every(function () {
                const row = this.node();
                if ($(row).find('.change-user').data('id') == id) {
                    this.data(result.data).draw(false);
                }
            });
        } else {
            alert(result.message || "Impossible de mettre à jour la ligne");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});


