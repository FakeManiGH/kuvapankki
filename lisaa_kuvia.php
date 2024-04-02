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

    // Haetaan tarvittaessa galleria osoiteriviltä:
    if (isset($_GET['g'])) {
        $gallery_id = htmlspecialchars($_GET['g']);
    } else {
        $gallery_id = 0;
    }

    require_once 'includes/connections.php';
    require_once 'includes/gallery_model.php';
    $galleries = get_write_access_galleries($pdo, $_SESSION['user_id']);
?>

<main>

    <h1>Lisää Kuvia</h1>

    <div class="page_navigation">
        <nav class="page_links">
            <?php include 'includes/page_navigation.php';?>
        </nav>    
    </div>

    <p>Tällä sivulla voit lisätä kuvia gallerioihisi helposti ja nopeasti. Valitse haluamasi kuvat, valitse galleria, anna kuvaille tiedot ja lähetä kuvat.</p>

    <!-- Kuvien raahausalue -->
    <div id="droparea">
        <form class="page_form" method="post" enctype="multipart/form-data">
            <h4>Raahaa haluamasi kuvat tähän (max 10kpl)</h4>
            
            <ul>
                <li>Kuvatiedostot voivat olla <strong>.JPG</strong>, <strong>.JPEG</strong>, tai <strong>.PNG</strong> -muotoisia.</li>
                <li>Kuvatiedoston maksimikoko on 5MB.</li>
            </ul>

            <label type="hidden" for="images"></label>
            <input type="file" id="images" name="images" multiple>

            <button type="button" onclick="document.getElementById('images').click()">Valitse tiedostot</button>
        </form>
    </div>

    <!-- Kuvien tiedot -->
    <form action="includes/images_add.php" class="image_form" id="image_form" method="post" enctype="multipart/form-data">
        <h3>Kuvien Tiedot</h3>    

        <span class="inline">
            <label for="selected_gallery">Valitse Galleria <span class="red">*</span></label>
            <select name="gallery" id="selected_gallery">
                <option value="0">Valitse Galleria:</option>
                <?php
                    foreach ($galleries as $key => $gallery) {
                        $galleries[$key] = array_map('htmlspecialchars', $gallery);
                        echo '<option value="' . $gallery['gallery_id'] . '" '. ($gallery_id == $gallery['gallery_id'] ? 'selected' : '') .'>'. $gallery['name'] .' - ';

                        if ($gallery['owner_id'] == $_SESSION['user_id']) {
                            echo 'OMA';
                        } elseif ($gallery['owner_id'] !== $_SESSION['user_id'] && $gallery['role_id'] == 1) {
                            echo 'ADMIN';
                        } elseif ($gallery['role_id'] == 2) {
                            echo 'OIKEUDET';
                        } 

                        if ($gallery['visibility'] == 1) {
                            echo ' (Yksityinen)';
                        } else if ($gallery['visibility'] == 2) {
                            echo ' (Kaverit)';
                        } else {
                            echo ' (Julkinen)';
                        }

                        '</option>';
                    }
                ?>
            </select>
        </span>

        <label for="images[]" class="hidden"></label>
        <input type="file" id="fileInput" class="hidden" name="images[]" multiple>
    </form>
    

</main>

<?php
    include 'footer.php';
    $pdo = null;
?>