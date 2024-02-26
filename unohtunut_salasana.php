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



    $title = 'Salasana Unohtunut';
    $css = 'css/kirjaudu.css';
    include 'header.php';
?>

<main>

    <h1>Unohtuiko Salasana?</h1>

        <p>Jos olet unohtanut salasanasi, voit asettaa uuden salasanan lähettämällä nollaus-linkin profiilisi liitettyyn sähköpostiosoitteeseen.</p>
        
        <form action="kirjaudu.php" class="page_form" method="POST">
            <label for="userinfo">Käyttäjätunnus tai Sähköpostiosoite</label>
            <input type="text" name="userinfo" placeholder="Käyttäjätunnus tai Sähköposti" autofocus>

            <p class='error_msg'><?php if (isset($_SESSION['errors_pass_reset'])) {check_login_errors();} ?></p>

            <span class="buttons">
                <button type="submit">Lähetä</button>
            </span>
        </form>
        
        <p>Etkö muista <strong>käyttäjätunnustasi</strong> tai <strong>sähköpostiosoitettasi</strong>? <a href="ota_yhteytta.php">Ota Yhteyttä</a> palvelun ylläpitoon.</p>

</main>


<?php
    include 'footer.php';
?>