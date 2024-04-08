<?php
    $css = 'css/vahvistukset.css';
    $title = 'Vahvistukset';
    include 'header.php';
    checkSession();
?>

<main>
    <!-- Tämä sivu on tarkoitettu rekisteröinnin jälkeiselle sähköpostiosoitteen vahvistukselle. -->

    <?php

    // Ilmoitus rekisteröinnin onnistumisesta.
    if (isset($_SESSION['register_status'])) {
        echo "<h4 class='green'>".$_SESSION['register_status']."</h4>";
        echo "<p class='red'>Vahvista sähköpostiosoitteesi ennen kirjautumista. Linkki on lähetetty antamaasi sähköpostiosoitteeseen.</p>";
        unset($_SESSION['register_status']);
    

    // ILmoitus sähköpostin vahvistukstuksen onnistumisesta..
    } else if (isset($_SESSION['verify_status'])) { 
        echo "<h4 class='green'>".$_SESSION['verify_status']."</h4><br>";
        echo "<button><a href='kirjaudu.php'>Kirjaudu sisään</a></button>";
        unset($_SESSION['verify_status']);
    

    // Jos vahvistus epäonnistuu, näytetään virheilmoitus.
    } else if (isset($_SESSION['errors'])) {
        echo "<h4 class='red'>".$_SESSION['errors']['verify_status']."</h4>";
        unset($_SESSION['errors']);

        
    } else {
        $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
        header("Location: ../404.php?virhe");
        die();
    
    }
    
    ?>
    
</main>

<?php
    include 'footer.php';
?>

