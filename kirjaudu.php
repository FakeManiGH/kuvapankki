<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include "includes/login.php";

        function check_login_errors() {
            if (isset($_SESSION['errors_login'])) {
                $errors = $_SESSION['errors_login'];
        
                foreach ($errors as $error) {
                    echo $error;
                }
            }
        }
    }



    $title = 'Kirjaudu Sisään';
    $css = 'css/kirjaudu.css';
    include 'header.php';
?>

<main>

    <h1>Kirjaudu</h1>


        <p>Kirjaudu sisään käyttäjätunnuksellasi ja salasanallasi.</p>
        <p><span class="red"> * pakollinen kenttä.</span></p>
        
        <form action="kirjaudu.php" class="page_form" method="POST">
            <span class="inline">
                <label for="username">Käyttäjätunnus</label>
                <input type="text" name="username" placeholder="Käyttäjätunnus" autofocus>
                <span class="required">*</span>
            </span>

            <span class="inline">
                <label for="pwd">Salasana</label>
                <input type="password" name="pwd" placeholder="Salasana">
                <span class="required">*</span>
            </span>
            <p class='error_msg'><?php if (isset($_SESSION['errors_login'])) {check_login_errors();} ?></p>

            <span class="buttons">
                <button type="submit">Kirjaudu</button>
            </span>
        </form>
        <a href="unohtunut_salasana.php">Unohditko salasana?</a>
        <p>Ei vielä tunnuksia? <a href="rekisteroidy.php">Rekisteröidy!</a></p>

</main>


<?php
    include 'footer.php';
?>