<?php
    $title = 'Omat Galleriat';
    $css = 'css/galleriat.css';
    $js = 'scripts/galleriat.js';
    include 'header.php';
    checkSession();

    if (is_logged_in()) {
        require 'includes/connections.php';
        require 'includes/gallery_model.php';
        $owned_galleries = view_owned_galleries($pdo, $_SESSION['user_id']);
        $joined_galleries = view_joined_galleries($pdo, $_SESSION['user_id']);
    }
?>




<main>

    <h1>Galleriat</h1>

    <div class="page_navigation">
        <nav class="page_links">
            <?php include 'includes/page_navigation.php';?>
        </nav>    
    </div>

    <?php if (!is_logged_in()) {
        echo '<p class="red">Kirjaudu sisään tai rekisteröidy luodaksesi omia gallerioita tai liittyäksesi kaverin galleriaan.</p>';
    } else {
        echo '<p>Tältä sivilta löydät kaikki omat galleriasi. Luo gallerioita lempi aiheillesi ja jaa ne kavereiden kanssa.
        Voit myös päättää pitää gallereiasi yksityisinä tai tehdä niistä julkisia. Tällöin kaikki voivat nähdä sen.</p>';
    } ?>
    

    <?php if (is_logged_in()) { ?>
    <!-- Omat Galleriat -->
    <section>
        <div class="filter_container">
            <h3>Omat Galleriat</h3>
            <div id="filters_owned" class="filters">
                <label for="filter_galleries_owned" title="Rajaa Gallerioita"><i class="fa fa-sliders"></i></label>
                <select id="filter_galleries_owned" name="filter_galleries_owned">
                    <option value="all" class="bold_txt">Rajaa</option>
                    <option value="3">Julkinen</option>
                    <option value="2">Kaverit</option>
                    <option value="1">Yksityinen</option>
                </select>

                <label for="sort_galleries_owned" title="Järjestä Gallerioita"><i class="fa fa-sort"></i></label>
                <select id="sort_galleries_owned" name="sort_galleries_own">
                    <option value="newest">Uusin ensin</option>
                    <option value="oldest">Vanhin ensin</option>
                    <option value="name">Nimi A-Z</option>
                    <option value="name_desc">Nimi Z-A</option>
                </select>
            </div>
        </div>  

        <ul id="owned_galleries" class="gallery_list">

            <?php
            if (isset($owned_galleries) && !empty($owned_galleries)) {
                usort($owned_galleries, function($a, $b) {
                    return strtotime($b['created']) - strtotime($a['created']);
                });
                foreach ($owned_galleries as $key => $gallery) {
                    $owned_galleries[$key] = array_map('htmlspecialchars', $gallery);

                    echo '<li class="gallery_item gallery_owned" data-name="'.$gallery['name'].'" data-visibility="'. $gallery['visibility'] .'" data-date="'.$gallery['created'].'">';
                    echo '<a href="galleria.php?g=' . $gallery['gallery_id'] . '" title="Näytä galleria">'.$gallery['name'].'</a>';
                    echo '<p class="small_txt" title="Omistaja"><i class="fa fa-user-tie"></i> ' . $gallery['username'] . '</p>';
                    if ($gallery['visibility'] === 1) {
                        echo '<p class="small_txt" title="Näkyvyys"><i class="fa fa-key"></i> Yksityinen</p>';
                    } else if ($gallery['visibility'] === 2) {
                        echo '<p class="small_txt" title="Näkyvyys"><i class="fa fa-key"></i> Kaverit</p>';
                    } else {
                        echo '<p class="small_txt" title="Näkyvyys"><i class="fa fa-key"></i> Julkinen</p>';
                    }
                    echo '<p class="small_txt hide_mobile" title="Luotu"><i class="fa fa-calendar"></i> ' . $gallery['created'] . '</p>';
                    echo '</li>';
                }

            } else {
                echo '<p class="small_txt grey">Ei Gallerioita.</p>';
            }
            ?>

        </ul>
    </section>


    <!-- Jäsenyydet -->     
    <section>
        <div class="filtering">
            <h3>Jäsenyydet</h3>
            <div id="filters_joined" class="filters">
                <label for="filter_galleries_joined" title="Rajaa Gallerioita"><i class="fa fa-sliders"></i></label>
                <select id="filter_galleries_joined" name="filter_galleries_joined">
                    <option value="all" class="bold_txt">Rajaa</option>
                    <option value="all">Kaikki</option>
                    <option value="3">Julkinen</option>
                    <option value="2">Kaverit</option>
                </select>

                <label for="sort_galleries_joined" title="Järjestä Gallerioita"><i class="fa fa-sort"></i></label>
                <select id="sort_galleries_joined" name="sort_galleries_joined">
                    <option value="newest">Uusin Ensin</option>
                    <option value="oldest">Vanhin Ensin</option>
                    <option value="name">Nimi A-Z</option>
                    <option value="name_desc">Nimi Z-A</option>
                    <option value="owner">Omistaja A-Z</option>
                    <option value="owner_desc">Omistaja Z-A</option>
                </select>
            </div>
        </div>

        <ul id="joined_galleries" class="gallery_list">

            <?php
            if (isset($joined_galleries) && !empty($joined_galleries)) {
                usort($joined_galleries, function($a, $b) {
                    return strtotime($b['created']) - strtotime($a['created']);
                });
                foreach ($joined_galleries as $gallery) {
                    $joined_galleries[$key] = array_map('htmlspecialchars', $gallery);
                    $members = get_gallery_member_count($pdo, $gallery['gallery_id']);
                    
                    echo '<li class="gallery_item gallery_joined" data-name="'.$gallery['name'].'" data-owner="'.$gallery['username'].'" data-visibility="'. $gallery['visibility'] .'" data-date="'.$gallery['created'].'">';
                    echo '<a href="galleria.php?g=' . $gallery['gallery_id'] . '" title="Näytä galleria">'.$gallery['name'].'</a>';
                    echo '<p class="small_txt" title="Omistaja"><i class="fa fa-user-tie"></i> ' . $gallery['username'] . '</p>';
                    if ($gallery['visibility'] === 1) {
                        echo '<p class="small_txt" title="Näkyvyys"><i class="fa fa-key"></i> Yksityinen</p>';
                    } else if ($gallery['visibility'] === 2) {
                        echo '<p class="small_txt" title="Näkyvyys"><i class="fa fa-key"></i> Kaverit</p>';
                    } else {
                        echo '<p class="small_txt" title="Näkyvyys"><i class="fa fa-key"></i> Julkinen</p>';
                    }
                    echo '<p class="small_txt hide_mobile" title="Luotu"><i class="fa fa-calendar"></i> ' . $gallery['created'] . '</p>';
                    echo '</li>';
                }

            } else {
                echo '<p class="small_txt grey">Ei Gallerioita.</p>';
            }
            ?>

        </ul>
    </section>

    <?php } ?>

</main>

<?php
    include 'footer.php';
    $pdo = null;
?>