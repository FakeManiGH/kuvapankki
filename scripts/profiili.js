
// Asettaa käyttäjänimen muokattavaksi
document.getElementById('edit_username').addEventListener('click', function() {
    document.getElementById('username').removeAttribute('readonly');
    document.getElementById('username').focus();
});

// Asettaa etunimen muokattavaksi
document.getElementById('edit_first_name').addEventListener('click', function() {
    document.getElementById('first_name').removeAttribute('readonly');
    document.getElementById('first_name').focus();
});

// Asettaa sukunimen muokattavaksi
document.getElementById('edit_last_name').addEventListener('click', function() {
    document.getElementById('last_name').removeAttribute('readonly');
    document.getElementById('last_name').focus();
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
    document.getElementById('first_name').setAttribute('readonly', '');
    document.getElementById('last_name').setAttribute('readonly', '');
    document.getElementById('phone').setAttribute('readonly', '');
    document.getElementById('email').setAttribute('readonly', '');
});