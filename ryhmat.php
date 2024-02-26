<?php

    $title = 'Kuvaryhmät';
    // $css = '';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautumen=vaaditaan');
        die();
    }
?>

<main>

    <h1>Minun Ryhmät</h1>

    <h2>Julkiset ryhmät</h2>

</main>

<?php
    include 'footer.php';
?>