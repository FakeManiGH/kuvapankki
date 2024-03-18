<?php
checkSession();

$current_page = basename($_SERVER['PHP_SELF']);

// ETUSIVU
if ($current_page == "index.php") {
    if (isset($_SESSION['user_id'])) {
        echo "<a title='Profiili' href='profiili.php'><i class='fa fa-user'></i> Profiili</a>";
    } 
}

// KIRJAUDU
if ($current_page == "kirjaudu.php") {
    echo "<a title='Rekisteröidy' href='rekisteroidy.php'><i class='fa fa-user-plus'></i> Rekisteröidy</a>";
    echo "<a title='Unohtunut Salasana' href='unohtunut_salasana.php'><i class='fa fa-key'></i> Unohtunut Salasana</a>";
}

// REKISTERÖIDY
if ($current_page == "rekisteroidy.php") {
    echo "<a title='Kirjaudu' href='kirjaudu.php'><i class='fa fa-user'></i> Kirjaudu</a>";
    echo "<a title='Unohtunut Salasana' href='unohtunut_salasana.php'><i class='fa fa-key'></i> Unohtunut Salasana</a>";
    echo "<a title='Tietoa Palvelusta' href='tietoa.php'><i class='fa fa-info-circle'></i> Tietoa Palvelusta</a>";
}

// PROFIILI
if ($current_page == "profiili.php") {
    echo "<a title='Asetukset' href='asetukset.php'><i class='fa fa-cog'></i> Asetukset</a>";
    echo "<a title='Ilmoitukset' href='ilmoitukset.php'><i class='fa fa-bell'></i> Ilmoitukset</a>";
    echo "<a title='Viestit' href='viestit.php'><i class='fa fa-envelope-open-text'></i> Viestit</a>";
    echo "<a title='Kaverit' href='kaverit.php'><i class='fa fa-users'></i> Kaverit</a>";
}

// GALLERIAT
if ($current_page == "galleriat.php") {
    echo "<a title='Lisää Kuvia' href='lisaa_kuvia.php'><i class='fa fa-upload'></i> Lisää Kuvia</a>";
    echo "<a title='Uusi Galleria' href='lisaa_galleria.php'><i class='fa fa-folder-plus'></i> Uusi Galleria</a>";
}

if ($current_page == "lisaa_galleria.php") {
    echo "<a title='Galleriat' href='galleriat.php'><i class='fa fa-images'></i> Galleriat</a>";
}

// TIETOA
if ($current_page == "tietoa.php") {
    echo "<a title='Rekisteröidy' href='rekisteroidy.php'><i class='fa fa-user-plus'></i> Rekisteröidy</a>";
    echo "<a title='Ota Yhteyttä' href='ota_yhteytta.php'><i class='fa fa-envelope-open-text'></i> Ota Yhteyttä</a>";
}

// OTA YHTEYTTÄ
if ($current_page == "ota_yhteytta.php") {
    echo "<a title='Tietoa Palvelusta' href='tietoa.php'><i class='fa fa-info-circle'></i> Tietoa Palvelusta</a>";
}

// KAVERIT
if ($current_page == "kaverit.php") {
    echo "<a title='Lisää Kavereita' href='lisaa_kaveri.php'><i class='fa fa-user-plus'></i> Lisää kaveri</a>";
    echo "<a title='kaveripyynnöt' href='lisaa_kaveri.php'><i class='fa fa-bell'></i> Kaveripyynnöt</a>";
    echo "<a title='Viestit' href='viestit.php'><i class='fa fa-envelope-open-text'></i> Viestit</a>";
}

if ($current_page == "lisaa_kaveri.php") {
    echo "<a title='Kaverit' href='kaverit.php'><i class='fa fa-users'></i> Kaverit</a>";
}

// LISÄÄ KUVIA
if ($current_page == "lisaa_kuvia.php") {
    echo "<a title='Uusi Galleria' href='lisaa_galleria.php'><i class='fa fa-folder-plus'></i> Uusi Galleria</a>";
    echo "<a title='Galleriat' href='galleriat.php'><i class='fa fa-images'></i> Galleriat</a>";
}