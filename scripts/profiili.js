
// Asettaa käyttäjänimen muokattavaksi
document.getElementById('edit_username').addEventListener('click', function() {
    document.getElementById('username').removeAttribute('readonly');
    document.getElementById('username').focus();
});

// Asettaa puhelinnumeron muokattavaksi
document.getElementById('edit_phone').addEventListener('click', function() {
    document.getElementById('phone').removeAttribute('readonly');
    document.getElementById('phone').focus();
});

// Asettaa sähköpostin muokattavaksi
document.getElementById('edit_email').addEventListener('click', function() {
    document.getElementById('email').removeAttribute('readonly');
    document.getElementById('email').focus();
});

// Nollaa muokkauslomakkeen ja lukitsee kentät
document.getElementById('reset').addEventListener('click', function() {
    document.getElementById('username').setAttribute('readonly', '');
    document.getElementById('phone').setAttribute('readonly', '');
    document.getElementById('email').setAttribute('readonly', '');
});

document.getElementById('user_info').addEventListener('submit', function(e) {
    if (
        document.getElementById('username').hasAttribute('readonly', '') &&
        document.getElementById('phone').hasAttribute('readonly', '') &&
        document.getElementById('email').hasAttribute('readonly', '')
    ) {
        e.preventDefault();
        document.getElementById('update_errors').innerHTML = 'Muokkaa tietoja ennen tallentamista.';
    } 
});





// PROFIILIKUVAN MUOKKAUS

// Tallennetaan tarvittavat muuttujat
var oldProfileImg = document.getElementById('profile_img').src; // Vanha profiilikuva

// Maksimikoko kuvatiedostolle
document.getElementById('user_img').addEventListener('change', function(e) {
    var fileErr = document.getElementById('file_err');
    var maxSize = 2 * 1024 * 1024; // 2MB
    var file = e.target.files[0];

    if(file.size > maxSize) {
        fileErr.innerHTML = 'Tiedosto on liian suuri, maksimikoko on 2MB';
        e.target.value = ''; // Clear the input
    }
    if(file.size <= maxSize) {
        fileErr.innerHTML = '';
    }
});

// Tarkistaa, että kuvatiedosto on oikeaa tyyppiä
document.getElementById('user_img').addEventListener('change', function(e) {
    var fileErr = document.getElementById('file_err');
    var file = e.target.files[0];
    var fileType = file.type;

    if(fileType != 'image/jpeg' && fileType != 'image/png') {
        fileErr.innerHTML = 'Tiedosto ei ole kuvatiedosto, vain JPG ja PNG sallittu.';
        e.target.value = ''; // Clear the input
    }
    if(fileType == 'image/jpeg' || fileType == 'image/png') {
        fileErr.innerHTML = '';
    }
});

// Valitun kuvan esikatselu
document.getElementById('user_img').addEventListener('change', function(e) {
    var file = e.target.files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        document.getElementById('profile_img').src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
        document.getElementById('clear_file_select').style.display = 'block';
        document.getElementById('accept_file_select').style.display = 'block';
    } else {
        document.getElementById('profile_img').src = oldProfileImg;
    }
});

// Valitun kuvan poisto
document.getElementById('clear_file_select').addEventListener('click', function() {
    if(document.getElementById('user_img').value != '') {
        document.getElementById('user_img').value = '';
        document.getElementById('profile_img').src = oldProfileImg;
        document.getElementById('clear_file_select').style.display = 'none';
        document.getElementById('accept_file_select').style.display = 'none';
    }
});

// Kuvan hyväksyminen
document.getElementById('accept_file_select').addEventListener('click', function() {
    document.getElementById('clear_file_select').style.display = 'none';
    document.getElementById('accept_file_select').style.display = 'none';
});





// SALASANAT NÄKYVÄKSI

// Näytytään salasana teksti muodossa
document.getElementById('show_pwd').addEventListener('click', function() {
    var x = document.getElementById('pwd');
    if(x.type === 'password') {
        x.type = 'text';
    } else {
        x.type = 'password';
    }
});

// Näytetään VANHA salasana teksti muodossa
document.getElementById('show_old_pwd').addEventListener('click', function() {
    var x = document.getElementById('old_pwd');
    if(x.type === 'password') {
        x.type = 'text';
    } else {
        x.type = 'password';
    }
});

// Näytetään UUSI salasana teksti muodossa
document.getElementById('show_new_pwd').addEventListener('click', function() {
    var x = document.getElementById('new_pwd');
    if(x.type === 'password') {
        x.type = 'text';
    } else {
        x.type = 'password';
    }
});