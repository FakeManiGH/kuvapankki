<?php
    $title = 'Kirjaudu Sisään';
    // $css = '';
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
        
    <div class="hero_item">
        <form action="includes/login.php" method="POST">
            <label for="username">Käyttäjätunnus <strong class="red">*</strong></label>
            <input type="text" name="username" placeholder="Käyttäjätunnus" autofocus required>

            <label for="pwd">Salasana <strong class="red">*</strong></label>
            <input type="password" name="pwd" placeholder="Salasana" required>

            <?php
                if (isset($_SESSION['login_error'])) {
                    foreach ($_SESSION['login_error'] as $error) {
                        echo "<p class='red'>". $error ."</p>";
                    }
                    unset($_SESSION['login_error']);
                }
            ?>

            <span class="buttons">
                <button type="submit"><i class="fa fa-sign-in"></i> Kirjaudu</button>
            </span>
        </form>

        <a href="unohtunut_salasana.php">Unohditko salasana?</a>

        <p>Ei vielä tunnuksia? <a href="rekisteroidy.php">Rekisteröidy!</a></p>
    </div>

</main>


<?php
    include 'footer.php';
?>