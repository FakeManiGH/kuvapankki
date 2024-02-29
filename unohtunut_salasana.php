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
        if (isset($_SESSION['reset_link'])) {
            echo "<h4 class='green'>".$_SESSION['reset_link']."</h4>";
            unset($_SESSION['reset_link']); 
        }
    ?>
        
    <h1>Unohtuiko Salasana?</h1>
            
    <p>Jos olet unohtanut salasanasi, voit asettaa uuden salasanan lähettämällä nollaus-linkin profiilisi liitettyyn sähköpostiosoitteeseen.</p>
    
    <div class="form_container">
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
    </div>
        
    <p>Etkö muista <strong>käyttäjätunnustasi</strong> tai <strong>sähköpostiosoitettasi</strong>? <a href="ota_yhteytta.php">Ota Yhteyttä</a> palvelun ylläpitoon.</p>
</main>


<?php
    include 'footer.php';
?>