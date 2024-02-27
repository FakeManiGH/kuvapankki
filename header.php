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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="scripts/scripts.js" defer></script>
    <?php
        if (isset($css)) echo "<link rel='stylesheet' href='$css'>";
        if (isset($js)) echo "<script defer src='$js'></script>";
    ?>
    <title><?=$title?></title>
</head>
<body>

<header>
    <div class="user_menu">
        <p class="logo">KUVA<span class="green">PANKKI</span></p>

        <?php
            if (is_logged_in()) {  
                echo "<a href='profiili.php' id='user_menu' title='Käyttäjä Valikko'><i class='fa fa-user'></i> " . $_SESSION['username'] . "</a>";
            } else {
                echo "<a href='kirjaudu.php' title='Kirjaudu Sisään' class='" . active('kirjaudu', $active) . "'><i class='fa fa-sign-in-alt'></i> Kirjaudu</a>";
            }
        ?>
    </div>

    <!-- <form class="search_form" action="haku.php" method="get">
            <label for="haku" class="hidden">Hae Kuvapankista</label>
            <input type="text" name="haku" placeholder="Hae Kuvapankista">
            <label for="sisalto" class="hidden">Sisältö</label>
            <select name="sisalto">
                <option value="kaikki">Kaikki</option>
                <option value="kuvat">Kuvat</option>
                <option value="galleriat">Galleriat</option>
                <option value="ryhmat">Ryhmät</option>
            </select>
        <button type="submit"><i class="fa fa-search"></i> <span class="nav_txt">Etsi</span></button>
    </form> -->

    <nav class="top_navi">
        <a href="index.php" title="Etusivu" class="<?=active('index',$active);?>"><i class="fa fa-home"></i> <span class="nav_txt">Etusivu</span></a>
        <a href="lisaa_kuvia.php" title="Lisää Kuvia" class="<?=active('lisaa_kuvia',$active);?>"><i class="fa fa-upload"></i> <span class="nav_txt">Lisää Kuvia</span></a>
        <a href="galleriat.php" title="Galleriat" class="<?=active('galleriat',$active);?>"><i class="fa fa-images"></i> <span class="nav_txt">Galleriat</span></a>
        <a href="tietoa.php" title="Tietoa" class="<?=active('tietoa',$active);?>"><i class="fa fa-info-circle"></i> <span class="nav_txt">Tietoa Palvelusta</span></a>
        <a href="ota_yhteytta.php" title="Ota Yhteyttä" class="<?=active('ota_yhteytta',$active);?>"><i class="fa fa-envelope"></i> <span class="nav_txt">Ota Yhteyttä</span></a>
    </nav>
</header>

<nav class="page_navbar">
    <?php include 'includes/navigation.php';?>
</nav>