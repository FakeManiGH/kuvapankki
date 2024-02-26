<?php
    $title = 'Omat Galleriat';
    // $css = '';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautumen=vaaditaan');
        die();
    }
?>

<main>

    <h1>Omat Galleriat</h1>

    <h2>Julkiset Galleriat</h2>



</main>

<?php
    include 'footer.php';
?>