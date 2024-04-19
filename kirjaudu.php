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
    
    <!-- Login form -->
    <div class="hero_container">
        <div class="hero_item" style="min-height: -moz-fit-content; min-height: fit-content;">
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
                    <button type="submit"><i class="fa fa-sign-in"></i> Kirjaudu sisään</button>
                </span>
            </form>
        </div>
    </div>

    <a href="unohtunut_salasana.php">Unohditko salasana?</a>

    <p>Ei vielä tunnuksia? <a href="rekisteroidy.php">Rekisteröidy!</a></p>

</main>


<?php
    include 'footer.php';
?>