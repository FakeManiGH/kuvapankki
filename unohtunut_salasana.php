<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require "includes/pwd_reset.php";
    }

    $title = 'Salasana Unohtunut';
    $css = 'css/unohtunut_salasana.css';
    // $js = '';
    include 'header.php';
?>

<main>
    <?php
        if (isset($_SESSION['reset_status'])) {
            echo "<h4 class='green'>".$_SESSION['reset_status']."</h4>";
            unset($_SESSION['reset_status']); 
        }

    if (isset($_GET['pwd_token'])) {?>
        <h1>Aseta Uusi Salasana</h1>
        
        <form action="uusi_salasana.php" class="page_form" method="POST">
            <input type="hidden" name="pwd_token" value="<?=$_GET['pwd_token']?>">
            <label for="password">Uusi Salasana</label>
            <input type="password" name="password" placeholder="Anna uusi salasana" autofocus>
            <label for="password2">Vahvista Salasana</label>
            <input type="password" name="password2" placeholder="Vahvista uusi salasana">
            <span class="buttons">
                <button type="submit">Aseta Salasana</button>
            </span>
        </form>

    <?php } else { ?>
        
        <h1>Unohtuiko Salasana?</h1>
            
        <p>Jos olet unohtanut salasanasi, voit asettaa uuden salasanan lähettämällä nollaus-linkin profiilisi liitettyyn sähköpostiosoitteeseen.</p>
            
        <form action="unohtunut_salasana.php" class="page_form" method="POST">
            <label for="email">Sähköpostiosoite</label>
            <input type="text" name="email" placeholder="Anna sähköpostiosoite" autofocus>

            <p class='error_msg'>
                <?php
                    if (isset($_SESSION['error_reset'])) {
                        echo $_SESSION['error_reset'];
                        unset($_SESSION['error_reset']); 
                    }
                ?>
            </p>

            <span class="buttons">
                <button type="submit">Lähetä</button>
            </span>
        </form>
            
        <p>Etkö muista <strong>käyttäjätunnustasi</strong> tai <strong>sähköpostiosoitettasi</strong>? <a href="ota_yhteytta.php">Ota Yhteyttä</a> palvelun ylläpitoon.</p>
    <?php } ?>
</main>


<?php
    include 'footer.php';
?>