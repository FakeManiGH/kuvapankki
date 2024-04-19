<?php
if (is_logged_in()) {
    echo "<a href='javascript:void(0);' id='close_user_menu' title='Sulje Valikko'><i class='fa fa-arrow-circle-right'></i></a>";

    echo "<div class='user_container'>";
        echo "<div class='user_image_wrapper'>";
            echo "<img src='".$_SESSION['user_img']."' id='menu_profile_img' class='user_image' alt='Profiilikuva'>";
        echo "</div>";
        echo "<h4 id='menu_username'>" .$_SESSION['username'] . "</h4>";
    echo "</div>";

    echo "<div class='user_links'>";
        echo "<a href='profiili.php' class='". active('profiili', $active) ."' title='Profiili'><i class='fa fa-user'></i> Profiili</a>";
        echo "<a href='ilmoitukset.php' class='". active('ilmoitukset', $active) ." title='Ilmoitukset'><i class='fa fa-bell'></i> Ilmoitukset</a>";
        echo "<a href='viestit.php' class='". active('viestit', $active) ." title='Viestit'><i class='fa fa-message'></i> Viestit</a>";
        echo "<a href='asetukset.php' class='". active('asetukset', $active) ." title='Asetukset'><i class='fa fa-cog'></i> Asetukset</a>";
    echo "</div>";

    echo "<div class='user_links'>";
        echo "<a href='tallenustila.php' class='". active('tallennustila', $active) ." title='Tallennustila'><i class='fa-regular fa-folder-open'></i> Hallitse Tiedostoja</a>";
        echo "<a href='tilaus.php' class='". active('tilaus', $active) ." title='Tilaus'><i class='fa fa-shopping-cart'></i> Tilaus</a>";
        echo "<a href='ota_yhteytta.php' class='". active('ota_yhteytta', $active) ." title='Ota Yhteyttä'><i class='fa fa-envelope'></i> Ota Yhteyttä</a>";
        echo "<a href='tietopankki.php' class='". active('tietopankki', $active) ." title='Tietopankki'><i class='fa fa-info-circle'></i> Tietopankki</a>";
    echo "</div>";

    echo "<div class='user_links'>";
        echo "<a href='includes/log_out.php' title='Kirjaudu Ulos'><i class='fa fa-sign-out-alt'></i> Kirjaudu Ulos</a>";
    echo "</div>";

} else {
    echo "<h4>Et ole kirjautunut sisään</h4>";
}