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
    $('.modal-user .modal-title').text("Ajouter un client");
    $('.modal-user button[type=submit]').text("Ajouter");
    $('.modal-user').modal({backdrop: 'static', keyboard: false});
});

$('.btn-app').on('click', function() {
    $('#dispositif')[0].reset();
    $('#dispositif input[name="id"]').val('');
    $('.modal-app .modal-title').text("Ajouter un appareil");
    $('.modal-app button[type=submit]').text("Ajouter");
    $('.modal-app').modal({backdrop: 'static', keyboard: false});
});


$('.userLogin').on('click', function() {
    $('.modal-login .modal-title').text("Modifier mes informations");
    $('.modal-login button[type=submit]').text("Modifier");
    let login = $('#userLogin').text().trim();
    $('#modalLoginField').val(login);
    $('.modal-login').modal({backdrop:'static', keyboard:false});
});

$('#userLoginForm').on('submit', function(e){
    e.preventDefault();
    const form = this;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);

    $.ajax({
        url: '/api-commande/routes/updateLogin.php',
        type: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token // token actuel
        },
        data: $(this).serialize(),
        success: function(res){
            submitBtn.removeClass('show-loader').prop('disabled', false);
            if(res.success){
                localStorage.setItem('token', res.token);
                document.getElementById('userLogin').textContent = parseJwt(res.token).data.login;

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



// ⚡ Initialisation DataTable
let editingRow;
let ets = $('.info-ets').DataTable();
let user = $('.info-user').DataTable();
let app = $('.info-app').DataTable();
let licence = $('.info-licence').DataTable();

// Submit 

$('#ets').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const number = iti.getNumber(); // +237...
        const country = iti.getSelectedCountryData().iso2; // cm

        formData.set('telephone', number);
        formData.set('country', country);
        const response = await fetch('/api-commande/routes/etablissement.php', {
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

             iti.setCountry("cm");

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
        const response = await fetch(`/api-commande/routes/etablissement.php?id=${etabId}`, {
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
            $('#ets input[name="pays"]').val(e.pays);
            $('#ets select[name="devise"]').val(e.devise);
            $('#ets input[name="ville"]').val(e.ville);
            $('#ets input[name="adresse"]').val(e.adresse);
            $('#ets input[name="email"]').val(e.email);
            $('#phone').val(e.telephone);
            if (e.country) {
                iti.setCountry(e.country); // 🇨🇲 drapeau
            }

            iti.setNumber(e.telephone); // numéro formaté
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

/*-------------USER------------------------*/

$('#user').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const response = await fetch('/api-commande/routes/utilisateur.php', {
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
    const userId = $(this).data('id');
    editingRow = user.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-commande/routes/utilisateur.php?id=${userId}`, {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#user input[name="id"]').val(userId);
            $('#user input[name="nom"]').val(e.nom);
            $('#user input[name="adresse"]').val(e.adresse);
            $('#user input[name="email"]').val(e.email);
            $('#user input[name="telephone"]').val(e.telephone);
            $('#user input[name="login"]').val(e.login);
            $('#user select[name="role"]').val(e.role);
            $('#user select[name="id_etablissement"]').val(e.id_etablissement);
            
            $('.modal-user .modal-title').text("Modifier le client");
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

/*-------------APPAREIL------------------------*/

$('#dispositif').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const response = await fetch('/api-commande/routes/appareil.php', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token },
            body: formData
        });
        const result = await response.json();
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        if(result.success) {
            submitBtn.removeClass('show-loader').prop('disabled', false);
            $('.modal-app').modal('hide');
            form.reset();
            if(isEdit && editingRow) {
                // ⚡ Mettre à jour uniquement la ligne modifiée
                editingRow.data(result.data).draw(false);
                editingRow = null; // reset la référence
            } else {
                app.row.add(result.data).draw(false);
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

$(document).on('click', '.edit-app', async function() {
    const appId = $(this).data('id');
    editingRow = app.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-commande/routes/appareil.php?id=${appId}`, {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#dispositif input[name="id"]').val(appId);
            $('#dispositif input[name="marque"]').val(e.marque);
            $('#dispositif input[name="model"]').val(e.model);
            $('#dispositif input[name="numero_serie"]').val(e.numero_serie);
            $('#dispositif input[name="systeme_exploitation"]').val(e.systeme_exploitation);
            $('#dispositif input[name="annee_fabrication"]').val(e.annee_fabrication);
            $('#dispositif input[name="date_fin_support"]').val(e.date_fin_support);
            $('#dispositif select[name="id_etablissement"]').val(e.id_etablissement);
            $('#dispositif textarea[name="description"]').val(e.description);
            
            $('.modal-app .modal-title').text("Modifier l'appareil");
            $('.modal-app button[type=submit]').text("Modifier");
            $('.modal-app').modal({backdrop:'static', keyboard:false});
        } else {
            alert(result.message);
        }
    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

//supprimer

$(document).on('click', '.drop-app', async function () {
    const id = $(this).data('id');
    if (!confirm("Voulez-vous vraiment supprimer cet appareil ?")) return;

    try {
        const response = await fetch(
            `/api-commande/routes/appareil.php?id=${id}`,
            {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            }
        );
        const result = await response.json();
        if (result.success) {
            // Supprime uniquement la ligne concernée dans le DataTable
            app.rows().every(function () {
                const row = this.node();
                if ($(row).find('.drop-app').data('id') == id) {
                    this.remove().draw(false);
                }
            });
        } else {
            alert(result.message || "Impossible de supprimer l'appareil");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});

//-----------------contrat--------------------

$('#contrat').on('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const isEdit = formData.get('id') ? true : false;
    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.addClass('show-loader').prop('disabled', true);
    try {
        const response = await fetch('/api-commande/routes/contrat.php', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token },
            body: formData
        });
        const result = await response.json();
        submitBtn.prop('disabled', false).text(isEdit ? "Modifier" : "Ajouter");
        if(result.success) {
            submitBtn.removeClass('show-loader').prop('disabled', false);
            $('.modal-licence').modal('hide');
            form.reset();
            if(isEdit && editingRow) {
                // ⚡ Mettre à jour uniquement la ligne modifiée
                editingRow.data(result.data).draw(false);
                editingRow = null; // reset la référence
            } else {
                licence.row.add(result.data).draw(false);
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

$(document).on('click', '.edit-contrat', async function() {
    const licenceId = $(this).data('id');
    editingRow = licence.row($(this).closest('tr'));
    try {
        const response = await fetch(`/api-commande/routes/contrat.php?id=${licenceId}`, {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const result = await response.json();
        if(result.success) {
            const e = result.data;
            $('#contrat input[name="id"]').val(licenceId);
            $('#contrat select[name="id_etablissement"]').val(e.id_etablissement);
            $('#contrat input[name="date_validite"]').val(e.date_validite);
            $('#contrat textarea[name="description"]').val(e.description);
            
            $('.modal-licence .modal-title').text("Modifier le contrat");
            $('.modal-licence button[type=submit]').text("Modifier");
            $('.modal-licence').modal({backdrop:'static', keyboard:false});
        } else {
            alert(result.message);
        }
    } catch(err) {
        console.error(err);
        alert("Erreur serveur : " + err.message);
    }
});


   
