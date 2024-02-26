<?php
    $title = 'Tervetuloa Kuvapankkiin';
    $css = 'css/index.css';
    include 'header.php';
    checkSession();
?>

<main>

    <h1>Tervetuloa Kuvapankkiin</h1>

    <?php 
    if (isset($_SESSION['username'])) {
        echo "<p><strong>Tervetuloa Kuvapankkiin ".$_SESSION['username']."</strong></p>";
    }
    ?>
    <p>Kuvapankki on kuvien jakamiseen ja tallentamiseen tarkoitettu palvelu. 
    Kuvapankin avulla voit jakaa kuvia ystäviesi kanssa ja luoda kuvaryhmiä, joihin voit kutsua ystäviäsi mukaan. 
    Kuvapankki on ilmainen ja helppokäyttöinen.</p>

    <p>Rekisteröitymällä voit tallentaa kuvia ja luoda kuvaryhmiä.</p>


</main>

<?php
    include 'footer.php';
?>
