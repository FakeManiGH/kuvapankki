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

            <button type="button" class="small_btn" onclick="document.getElementById('images').click()"><i class="fa fa-upload"></i> Valitse tiedostot</button>
        </form>
    </div>

    <!-- Kuvien tiedot -->
    <form action="includes/images_add.php" id="image_form" method="post" enctype="multipart/form-data">  

        <label for="selected_gallery" class="hidden"></label>
        <select name="gallery" id="selected_gallery">
            <option value="0">Valitse Galleria:</option>
            <?php
                foreach ($galleries as $key => $gallery) {
                    $galleries[$key] = array_map('htmlspecialchars', $gallery);
                    echo '<option value="' . $gallery['gallery_id'] . '">';

                    echo $gallery['name'] . ' - ';

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

        <p class="red" id="gallery_error"></p>

        <label for="images[]" class="hidden"></label>
        <input style="display: none;" type="file" id="fileInput" class="hidden" name="images[]" multiple>
    </form>
    

</main>

<?php
    include 'footer.php';
    $pdo = null;
?>