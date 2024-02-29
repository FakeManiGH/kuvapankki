<?php
    $css = 'css/vahvistukset.css';
    $title = 'Vahvistukset';
    include 'header.php';
    checkSession();
?>

<main>
    <!-- Tämä sivu on tarkoitettu rekisteröinnin. -->

    <?php

    // Ilmoitus rekisteröinnin onnistumisesta
    if (isset($_SESSION['register_status'])) {
        echo "<h4 class='green'>".$_SESSION['register_status']."</h4>";
        echo "<p class='red'>Vahvista sähköpostiosoitteesi ennen kirjautumista. Linkki on lähetetty antamaasi sähköpostiosoitteeseen.</p>";
        unset($_SESSION['register_status']);
    

    // ILmoitus sähköpostin vahvistukstuksen onnistumisesta
    } else if (isset($_SESSION['verify_status'])) { 
        echo "<h4 class='green'>".$_SESSION['verify_status']."</h4>";
        unset($_SESSION['verify_status']);
    

    // Jos vahvistus epäonnistuu, näytetään virheilmoitus
    } else if (isset($_SESSION['errors'])) {
        echo "<h4 class='red'>".$_SESSION['errors']['verify_status']."</h4>";
        unset($_SESSION['errors']);

        
    } else {
        header("Location: index.php?pääsy=kielletty");
        exit();
        
    }
    ?>


    <!-- Tämä sivu on tarkoitettu sähköpostin vahvistuslinkin vastaanottoon. -->
    
</main>

<?php
    include 'footer.php';
?>

