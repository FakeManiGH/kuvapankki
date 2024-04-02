<?php

    $title = 'Ota Yhteyttä';
    $css = 'css/ota_yhteytta.css';
    include 'header.php';
    checkSession();
?>

<main>
    <h1>Ota Yhteyttä</h1>

    <p>Ota yhteyttä meihin täyttämällä alla oleva lomake. Vastaamme sinulle mahdollisimman pian ( <span class="red"><strong>*</strong> Pakollinen kenttä</span> ).</p>

    <form class="page_form" action="ota_yhteytta.php" method="post">
        <span class="inline">
            <label for="name">Nimi <strong class="red">*</strong></label>
            <input type="text" id="name" name="name" value="<?php if (isset($_SESSION['username'])) {echo $_SESSION['username'];} ?>" placeholder="Nimi">
        </span>
        
        <span class="inline">
            <label for="email">Sähköpostiosoite <strong class="red">*</strong></label>
            <input type="email" id="email" name="email" value="<?php if (isset($_SESSION['email'])) {echo $_SESSION['email'];} ?>" placeholder="Sähköpostiosoite">
        </span>

        <span class="inline">
            <label for="subject">Aihe <strong class="red">*</strong></label>
            <input type="text" id="subject" name="subject" placeholder="Aihe">
        </span>

        <span class="inline">
            <label for="message">Viesti <strong class="red">*</strong></label>
            <textarea id="message" name="message" placeholder="Kirjoita viesti"></textarea>
        </span>

        <button type="submit">Lähetä Viesti</button>
    </form>
</main>

<?php
    include 'footer.php';
?>