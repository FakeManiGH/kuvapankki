<?php
checkSession();

$current_page = basename($_SERVER['PHP_SELF']);

if ($current_page == "index.php") {
    if (isset($_SESSION['user_id'])) {
        echo  "<a class='user_link' title='Profiili' href='profiili.php'><i class='fa fa-user'></i> Profiili</a>";
        echo  "<a class='user_link' title='Kirjaudu ulos' href='includes/log_out.php'><i class='fa fa-sign-out-alt'></i> Kirjaudu ulos</a>";
    } else {
        echo  "<a class='user_link' title='Kirjaudu' href='kirjaudu.php'><i class='fa fa-user'></i> Kirjaudu</a>";
        echo  "<a class='user_link' title='Rekisteröidy' href='rekisteroidy.php'><i class='fa fa-user-plus'></i> Rekisteröidy</a>";
    }
}

if ($current_page == "kirjaudu.php") {
    echo  "<a class='user_link' title='Rekisteröidy' href='rekisteroidy.php'><i class='fa fa-user-plus'></i> Rekisteröidy</a>";
}