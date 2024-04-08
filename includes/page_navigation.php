<?php
checkSession();

$current_page = basename($_SERVER['PHP_SELF']);

// KIRJAUDU
if ($current_page == "kirjaudu.php") {
    echo "<a title='Rekisteröidy' href='rekisteroidy.php'><i class='fa fa-user-plus'></i> Rekisteröidy</a>";
    echo "<a title='Unohtunut Salasana' href='unohtunut_salasana.php'><i class='fa fa-key'></i> Unohtunut Salasana</a>";
}

// UNOHTUNUT SALASANA
if ($current_page == "unohtunut_salasana.php") {
    echo "<a title='Kirjaudu' href='kirjaudu.php'><i class='fa fa-user'></i> Kirjaudu</a>";
    echo "<a title='Tietoa Palvelusta' href='tietoa.php'><i class='fa fa-info-circle'></i> Tietoa Palvelusta</a>";
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
    echo "<a title='Kirjaudu Ulos' href='includes/log_out.php'><i class='fa fa-sign-out'></i> Kirjaudu Ulos</a>";
}

// GALLERIAT
if ($current_page == "galleriat.php" && is_logged_in()) {
    echo "<a title='Lisää Kuvia' href='lisaa_kuvia.php'><i class='fa fa-upload'></i> Lisää Kuvia</a>";
    echo "<a title='Luo Galleria' href='luo_galleria.php'><i class='fa fa-folder-plus'></i> Luo Galleria</a>";
} else if ($current_page == "galleriat.php") {
    echo "<a title='Kirjaudu' href='kirjaudu.php'><i class='fa fa-user'></i> Kirjaudu</a>";
    echo "<a title='Rekisteröidy' href='rekisteroidy.php'><i class='fa fa-user-plus'></i> Rekisteröidy</a>";
}

// GALLERIA
if ($current_page == "galleria.php") {
    echo "<a title='Galleriat' href='galleriat.php'><i class='fa fa-arrow-left'></i> Takaisin gallerioihin</a>";
}

if ($current_page == "luo_galleria.php") {
    echo "<a title='Galleriat' href='galleriat.php'><i class='fa fa-images'></i> Galleriat</a>";
}

// TIETOPANKKI
if ($current_page == "tietopankki.php") {
    echo "<a title='Rekisteröidy' href='rekisteroidy.php'><i class='fa fa-user-plus'></i> Rekisteröidy</a>";
    echo "<a title='Ota Yhteyttä' href='ota_yhteytta.php'><i class='fa fa-envelope'></i> Ota Yhteyttä</a>";
}

// OTA YHTEYTTÄ
if ($current_page == "ota_yhteytta.php") {
    echo "<a title='Tietopankki' href='tietopankki.php'><i class='fa fa-info-circle'></i> Tietopankki</a>";
}

// KAVERIT
if ($current_page == "kaverit.php") {
    echo "<a title='Lisää Kavereita' href='lisaa_kaveri.php'><i class='fa fa-user-plus'></i> Lisää kaveri</a>";
    echo "<a title='kaveripyynnöt' href='lisaa_kaveri.php'><i class='fa fa-bell'></i> Kaveripyynnöt</a>";
}

if ($current_page == "lisaa_kaveri.php") {
    echo "<a title='Kaverit' href='kaverit.php'><i class='fa fa-user-group'></i> Kaverit</a>";
}

// LISÄÄ KUVIA
if ($current_page == "lisaa_kuvia.php") {
    echo "<a title='Galleriat' href='galleriat.php'><i class='fa fa-images'></i> Galleriat</a>";
    echo "<a title='Luo Galleria' href='luo_galleria.php'><i class='fa fa-folder-plus'></i> Luo Galleria</a>";
}