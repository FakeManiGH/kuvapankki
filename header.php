<?php
    include 'includes/config_session.php';

    $active = basename($_SERVER['PHP_SELF'], ".php");
    function active($sivu,$active){
        return $active == $sivu ? 'active' : '';  
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <script src="scripts/scripts.js" defer></script>
    <script src="https://kit.fontawesome.com/bfc21bc165.js" crossorigin="anonymous" defer></script>
    <?php
        if (isset($css)) echo "<link rel='stylesheet' href='$css'>";
        if (isset($js)) echo "<script defer src='$js'></script>";
    ?>
    <title><?=$title?></title>
</head>
<body>

<header>
    <div class="logo_container">
        <p class="logo" title="Etusivu" onclick="window.location.href='index.php'">KUVA<span class="green">PANKKI</span><i class="fa fa-home home_icon"></i></p>

        <?php
            if (is_logged_in()) {  
                echo "<a id='open_user_menu' title='Käyttäjä Valikko' href='javascript:void(0);'>". $_SESSION['username'] ." <img class='tiny_img' src=".$_SESSION['user_img']." alt='Profiilikuva'></a>";
            } else {
                echo "<a href='kirjaudu.php' title='Kirjaudu Sisään' class='" . active('kirjaudu', $active) . "'><i class='fa fa-sign-in'></i> Kirjaudu Sisään</a>";
            }
        ?>
    </div>

    <nav id="user_navigation">
        <?php include 'includes/user_navigation.php';?>
    </nav>
    <span class="menu_overlay"></span>

    <nav class="top_navi">
        <a href="selaa.php" title="Selaa Kuvia" class="<?=active('selaa',$active);?>"><i class="fa fa-bars-staggered"></i> <span class="nav_txt">Selaa</span></a>
        <div class="dropdown">
            <a href="galleriat.php" title="Galleriat" class="<?=active('galleriat',$active);?>"><i class="fa-regular fa-images"></i> <span class="nav_txt">Galleriat <i class="fa-solid fa-caret-down"></i></span></a>
            <div class="drop_links">
                <a href="galleriat.php" title="Galleriat" class="<?=active('galleriat',$active);?>">Galleriat</a>
                <a href="lisaa_kuvia.php" title="Lisää Kuvia" class="<?=active('lisaa_kuvia',$active);?>">Lisää Kuvia</a>
                <a href="lisaa_galleria.php" title="Uusi Galleria" class="<?=active('lisaa_galleria',$active);?>">Uusi Galleria</a>
            </div>
        </div>
        <a href="ilmoitukset.php" title="Ilmoitukset" class="<?=active('ilmoitukset',$active);?>"><i class="fa fa-bell"></i> <span class="nav_txt">Ilmoitukset</span></a>
        <div class="dropdown">
            <a id="friends" href="kaverit.php" title="Kaverit" class="<?=active('kaverit',$active);?>"><i class="fa fa-users"></i> <span class="nav_txt">Kaverit <i class="fa-solid fa-caret-down"></i></span></a>
            <div class="drop_links">
                <a href="kaverit.php" title="Kaverit" class="<?=active('kaverit',$active);?>">Kaverit</a>
                <a href="lisaa_kaveri.php" title="Lisää Kaveri" class="<?=active('lisaa_kaveri',$active);?>">Lisää Kaveri</a>
            </div>
        </div>
        <a href="viestit.php" title="Viestit" class="<?=active('viestit',$active);?>"><i class="fa fa-envelope-open-text"></i> <span class="nav_txt">Viestit</span></a>
    </nav>
</header>

<nav class="page_navbar">
    <?php include 'includes/page_navigation.php';?>
</nav>