<?php
checkSession();

$current_page = basename($_SERVER['PHP_SELF']);

// ETUSIVU
if ($current_page == "index.php") {
    if (isset($_SESSION['user_id'])) {
        echo "<a title='Profiili' href='profiili.php'><i class='fa fa-user'></i> Profiili</a>";
        echo "<a title='Kirjaudu ulos' href='includes/log_out.php'><i class='fa fa-sign-out-alt'></i> Kirjaudu ulos</a>";
    } else {
        echo "<a title='Kirjaudu' href='kirjaudu.php'><i class='fa fa-user'></i> Kirjaudu</a>";
        echo "<a title='Rekisteröidy' href='rekisteroidy.php'><i class='fa fa-user-plus'></i> Rekisteröidy</a>";
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
    echo "<a title='Viestit' href='viestit.php'><i class='fa fa-envelope'></i> Viestit</a>";
    echo "<a title='Kaverit' href='kaverit.php'><i class='fa fa-users'></i> Kaverit</a>";
}

// GALLERIAT
if ($current_page == "galleriat.php") {
    echo "<a title='Lisää Kuvia' href='lisaa_kuvia.php'><i class='fa fa-upload'></i> Lisää Kuvia</a>";
    echo "<a title='Uusi Galleria' href='profiili.php'><i class='fa fa-plus'></i> Uusi Galleria</a>";
}

// TIETOA
if ($current_page == "tietoa.php") {
    echo "<a title='Rekisteröidy' href='rekisteroidy.php'><i class='fa fa-user-plus'></i> Rekisteröidy</a>";
    echo "<a title='Ota Yhteyttä' href='ota_yhteytta.php'><i class='fa fa-envelope'></i> Ota Yhteyttä</a>";
}

// OTA YHTEYTTÄ
if ($current_page == "ota_yhteytta.php") {
    echo "<a title='Tietoa Palvelusta' href='tietoa.php'><i class='fa fa-info-circle'></i> Tietoa Palvelusta</a>";
}