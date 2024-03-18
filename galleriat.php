<?php
    $title = 'Omat Galleriat';
    $css = 'css/galleriat.css';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautuminen=vaaditaan');
        die();
    }

    require 'includes/connections.php';
    require 'includes/gallery_model.php';
    $owned_galleries = view_owned_galleries($pdo, $_SESSION['user_id']);
    $joined_galleries = view_joined_galleries($pdo, $_SESSION['user_id']);
?>


<main>

    <h1>Galleriat</h1>

    <p>Tältä sivilta löydät kaikki omat galleriasi. Luo gallerioita lempi aiheillesi ja jaa ne kavereiden kanssa.
        Voit myös päättää pitää gallereiasi yksityisinä tai tehdä niistä julkisia. Tällöin kaikki voivat nähdä sen.</p>

    <form class="page_form">
        <fieldset>
            <legend><i class="fa fa-magnifying-glass"></i> Etsi Galleriaa</legend>
            <label for="gallery_search" class="hidden"></label>
            <span class="inline">
                <input type="text" id="gallery_search" name="gallery_search" placeholder="Anna hakusana" required>
                <button type="submit" id="gallery_search_button">Hae</button>
            </span>
        </fieldset>
    </form>

    <section>
        <div class="filtering">
            <h4>Omat Galleriat</h4>
            <label for="sort_galleries_own" class="hidden"></label>
            <select id="sort_galleries_own" name="sort_galleries_own">
                <option value="newest">UUSIMMAT</option>
                <option value="oldest">VANHIMMAT</option>
                <option value="name">NIMI</option>
            </select>
        </div>

        <div class="galleria_container">

            <?php
            if (isset($owned_galleries) && !empty($owned_galleries)) {
                foreach ($owned_galleries as $key => $gallery) {
                    $owned_galleries[$key] = array_map('htmlspecialchars', $gallery);

                    echo '<a class="gallery" href="galleria.php?galleria_id=' . $gallery['gallery_id'] . '">';
                    echo '<img class="gallery_cover" src="../' . $gallery['cover_img'] .'" alt="Gallerian Kansikuva">';
                    echo '<div class="gallery_info">';
                    echo '<h4>' . $gallery['name'] . '</h4>';
                    if ($gallery['visibility'] === 1) {
                            echo '<p class="small_txt" title="Yksityinen">Yksityinen <i class="fa fa-lock"></i></p>';
                        } else if ($gallery['visibility'] === 2){
                            echo '<p class="small_txt" title="Jaettu">Kaverit <i class="fa fa-users"></i></p>';
                        } else {
                            echo '<p class="small_txt" title="Julkinen">Julkinen <i class="fa fa-signal"></i></p>';
                        }
                    echo '</div>';
                    echo '</a>';  
                }

            } else {
                echo '<p class="small_txt grey">Ei Gallerioita.</p>';
            }
            ?>

        </div>
    </section>

    <section>
        <div class="filtering">
            <h4>Jäsenyydet</h4>
            <label for="sort_galleries_joined" class="hidden"></label>
            <select id="sort_galleries_joined" name="sort_galleries_own">
                <option value="newest">UUSIMMAT</option>
                <option value="oldest">VANHIMMAT</option>
                <option value="name">NIMI</option>
            </select>
        </div>

        <div class="galleria_container">

            <?php
            if (isset($joined_galleries) && !empty($joined_galleries)) {
                foreach ($joined_galleries as $gallery) {
                    $joined_galleries[$key] = array_map('htmlspecialchars', $gallery);
                    
                    echo '<a class="gallery" href="galleria.php?galleria_id=' . $gallery['gallery_id'] . '">';
                    echo '<img class="gallery_cover" src="../' . $gallery['cover_img'] .'" alt="Gallerian Kansikuva">';
                    echo '<div class="gallery_info">';
                    echo '<h4>' . $gallery['name'] . '</h4>';
                    if ($gallery['visibility'] === 2) {
                            echo '<p class="small_txt" title="Jaettu">Kaverit <i class="fa fa-users"></i></p>';
                        } else {
                            echo '<p class="small_txt" title="Julkinen">Julkinen <i class="fa fa-signal"></i></p>';
                        }
                    echo '</div>';
                    echo '</a>';   
                }

            } else {
                echo '<p class="small_txt grey">Ei Gallerioita.</p>';
            }
            ?>

        </div>
    </section>

</main>

<?php
    include 'footer.php';
?>