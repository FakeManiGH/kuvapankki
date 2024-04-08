<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require "includes/pwd_reset.php";
    }

    $title = 'Salasana Unohtunut';
    // $css = '';
    $js = 'scripts/unohtunut_salasana.js';
    include 'header.php';
?>

<main>
        
    <h1>Unohtuiko Salasana?</h1>
            
    <p>Nollataksesi salasanaasi, tulee sinun lähettää nollauspyyntö sähköpostiisi.</p>
    
    <div class="hero_item">
        <h3>Anna sähköpostiosoitteesi</h3>

        <p>Sähköpostiosoitteen tulee olla liitetty Kuvapankki-tiliisi. (<strong class="red">*</strong> pakollinen kenttä).</p>

        <form id="pwd_form" method="POST">

            <label for="email">Sähköpostiosoite <strong class="red">*</strong></label>
            <input type="text" id="email" name="email" placeholder="Anna sähköpostiosoite">

            <span class="buttons">
                <button type="submit"><i class="fa fa-envelope"></i> Lähetä</button>
            </span>
        </form>

        <p id="pwd_error" class="error_msg"></p>
        <p id="pwd_success" class="success_msg"></p>

        <p>Etkö muista <strong>sähköpostiosoitettasi</strong>? <a href="ota_yhteytta.php">Ota Yhteyttä</a> palvelun ylläpitoon.</p>
    </div>
        
</main>


<?php
    include 'footer.php';
?>