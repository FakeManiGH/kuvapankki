<?php
if (is_logged_in()) {
    echo "<a href='javascript:void(0);' id='close_user_menu' title='Sulje Valikko'><i class='fa fa-arrow-circle-right'></i></a>";

    echo "<div class='user_container'>";
        echo "<div class='user_image_wrapper'>";
            echo "<img src='".$_SESSION['user_img']."' class='user_image' alt='Profiilikuva'>";
        echo "</div>";
        echo "<h4>" .$_SESSION['username'] . "</h4>";
    echo "</div>";

    echo "<div class='user_links'>";
        echo "<a href='profiili.php' class='". active('profiili', $active) ."' title='Profiili'><i class='fa fa-user'></i> Profiili</a>";
        echo "<a href='asetukset.php' class='". active('asetukset', $active) ." title='Asetukset'><i class='fa fa-cog'></i> Asetukset</a>";
        echo "<a href='ilmoitukset.php' class='". active('ilmoitukset', $active) ." title='Ilmoitukset'><i class='fa fa-bell'></i> Ilmoitukset</a>";
        echo "<a href='viestit.php' class='". active('viestit', $active) ." title='Viestit'><i class='fa fa-envelope'></i> Viestit</a>";
        echo "<a href='kaverit.php' class='". active('kaverit', $active) ." title='Kaverit'><i class='fa fa-users'></i> Kaverit</a>";
    echo "</div>";

    echo "<a href='includes/log_out.php' title='Kirjaudu Ulos'><i class='fa fa-sign-out-alt'></i> Kirjaudu Ulos</a>";

} else {
    echo "<h4>Et ole kirjautunut sisään</h4>";
}