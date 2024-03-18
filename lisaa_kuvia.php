<?php

    $title = 'Lisää Kuvia';
    $css = 'css/lisaa_kuvia.css';
    $js = 'scripts/lisaa_kuvia.js';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautuminen=vaaditaan');
        die();
    }
?>

<main>

    <h1>Lisää Kuvia</h1>

    <p>Lisää hienoja kuvia galleriaasi. Voit ladata helposti jopa 10 kuvaa kerrallaan.</p>

    <div id="droparea">
        <form class="page_form" method="post" enctype="multipart/form-data">
            <h4>Siirrä haluamasi kuvat tähän, max 10kpl kerrallaan</h4>
            <ul>
                <li>Voit siirtää kuvia raahaamalla ne tähän tai valitsemalla ne tietokoneeltasi.</li>
                <li>Kuvatiedostot voivat olla .jpg, .jpeg, tai .png -muotoisia.</li>
                <li>Kuvatiedostojen maksimikoko on 5MB.</li>
                <li>Voit ladata useita kuvia kerrallaan.</li>
            </ul>

            <label type="hidden" for="images"></label>
            <input type="file" id="images" name="images" multiple>
            <button type="button" onclick="document.getElementById('images').click()">Valitse tiedostot</button>
        </form>
    </div>

    <form action="images_add.php" class="image_form" id="image_form" method="post"></form>
    

</main>

<?php
    include 'footer.php';
?>