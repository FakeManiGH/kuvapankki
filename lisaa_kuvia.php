<?php

    $title = 'Kuvavirta';
    $css = 'css/kuvat.css';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautumen=vaaditaan');
        die();
    }
?>

<main>

    <h1>Kuvavirta</h1>


</main>

<?php
    include 'footer.php';
?>