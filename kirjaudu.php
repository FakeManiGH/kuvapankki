<?php
    $title = 'Kirjaudu Sisään';
    $css = 'css/kirjaudu.css';
    include 'header.php';
    checkSession();
    if (is_logged_in()) {
        header('Location: index.php?olet=kirjutunut');
        die();
    }
?>

<main>

    <h1>Kirjaudu</h1>

    <p>Kirjaudu sisään käyttäjätunnuksellasi ja salasanallasi.</p>
        
    <div class="form_container">
        <form action="includes/login.php" class="page_form" method="POST">
            <label for="username">Käyttäjätunnus</label>
            <input type="text" name="username" placeholder="Käyttäjätunnus" autofocus required>

            <label for="pwd">Salasana</label>
            <input type="password" name="pwd" placeholder="Salasana" required>

            <p class='error_msg'>
            <?php 
                if (isset($_SESSION['errors_login'])) {
                    $errors = $_SESSION['errors_login'];
                    unset($_SESSION['errors_login']);   
                    foreach ($errors as $error) {
                        echo $error;
                    }
                }
            ?>
            </p>

            <span class="buttons">
                <button type="submit">Kirjaudu</button>
            </span>
        </form>
    </div>

    <a href="unohtunut_salasana.php">Unohditko salasana?</a>
    <p>Ei vielä tunnuksia? <a href="rekisteroidy.php">Rekisteröidy!</a></p>

</main>


<?php
    include 'footer.php';
?>