<?php

    $title = 'Kuvavirta';
    $css = 'css/lisaa_kuvia.css';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautuminen=vaaditaan');
        die();
    }
?>

<main>

    <h1>Lisää Uusi Kuva</h1>

    <form action="upload.php" class="page_form" method="post" enctype="multipart/form-data">
        <span class="inline">
            <label for="file">Valitse Kuva</label>
            <input type="file" name="file" id="file" class="form-control" >
        </span>

        <span class="inline">
            <label for="title">Otsikko</label>
            <input type="text" name="title" id="title" class="form-control">
        </span>

        <span class="inline">
            <label for="description">Kuvaus</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </span>

        <span class="inline">
            <label for="gallery">Valitse Galleria</label>
            <select name="gallery" id="gallery">
                <option value="0">Valitse Galleria</option>
                <option value="1">Galleria 1</option>
                <option value="2">Galleria 2</option>
                <option value="3">Galleria 3</option>
            </select>
        </span>


</main>

<?php
    include 'footer.php';
?>