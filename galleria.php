<?php
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautuminen=vaaditaan');
        die();
    }

    require 'includes/gallery_get.php';

    $title = 'Kuvat ja Galleriat';
    // $css = 'css/selaa.css';
    include 'header.php';
    checkSession();

?>

<main>

    <h3>Selaa kuvia ja gallerioita</h3>
    
    

</main>

<?php
    include 'footer.php';
?>