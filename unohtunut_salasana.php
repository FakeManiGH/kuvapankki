<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require "includes/pwd_reset.php";

        function check_reset_errors() {
            if (isset($_SESSION['errors_reset'])) {
                $errors = $_SESSION['errors_reset'];
        
                foreach ($errors as $error) {
                    echo $error;
                }
            }
        }
    }

    $title = 'Salasana Unohtunut';
    // $css = '';
    // $js = '';
    include 'header.php';
?>

<main>

    <?php
        if (isset($_SESSION['reset_status'])) {
            echo "<h4 class='green'>" . $_SESSION['reset_status'] . "</h4>";
            unset($_SESSION['reset_status']);
        }
    ?>

    <h1>Unohtuiko Salasana?</h1>

        <p>Jos olet unohtanut salasanasi, voit asettaa uuden salasanan lähettämällä nollaus-linkin profiilisi liitettyyn sähköpostiosoitteeseen.</p>
        
        <form action="unohtunut_salasana.php" class="page_form" method="POST">
            <label for="userinfo">Käyttäjätunnus tai Sähköpostiosoite</label>
            <input type="text" name="userinfo" placeholder="Käyttäjätunnus tai Sähköposti" autofocus>

            <p class='error_msg'><?php if (isset($_SESSION['errors_reset'])) {check_login_errors();} ?></p>

            <span class="buttons">
                <button type="submit">Lähetä</button>
            </span>
        </form>
        
        <p>Etkö muista <strong>käyttäjätunnustasi</strong> tai <strong>sähköpostiosoitettasi</strong>? <a href="ota_yhteytta.php">Ota Yhteyttä</a> palvelun ylläpitoon.</p>

</main>


<?php
    include 'footer.php';
?>