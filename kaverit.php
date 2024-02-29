<?php
    $title = 'Kaverit';
    // $css = '';
    include 'header.php';
    checkSession();

    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautuminen=vaaditaan');
        die();
    }
?>

<main>

    <h1>Omat Kaverit</h1>
    
    


</main>

<?php
    include 'footer.php';
?>