<?php
    require 'includes/gallery_view.php';
    $css = 'css/galleria.css';
    $js = 'scripts/galleria.js';
    include 'header.php';

    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautuminen=vaaditaan');
        die();
    }
?>

<main>
    <!-- Serverin ilmoitukset -->
    <?php
        // Kuvien lisäys ilmoitukset
        if (isset($_SESSION['image_upload_success'])) {
            echo '<h5 class="green">' . $_SESSION['image_upload_success'] . '</h5>';
            unset($_SESSION['image_upload_success']);
        }
        if (isset($_SESSION['image_upload_errors'])) {
            echo '<h5 class="red">Kuvien lisäys epäonnistui:</h5>';
            echo '<ul>';
            foreach ($_SESSION['image_upload_errors'] as $error) {
                echo '<li class="red">' . $error . '</li>';
            }
            echo '</ul>';
            unset($_SESSION['image_upload_errors']);
        }


        // Kuvan päivitys ilmoitukset
        if (isset($_SESSION['update_error'])) {
            echo '<h5 class="red">' . $_SESSION['update_error'] . '</h5>';
            unset($_SESSION['update_error']);
        }

        if (isset($_SESSION['update_success'])) {
            echo '<h5 class="green">' . $_SESSION['update_success'] . '</h5>';
            unset($_SESSION['update_success']);
        }
    ?>

    <div class="hero_container">

        <!-- Gallerian kansikuva -->
        <div class="hero_item">
            <?php
                echo '<img class="cover_img" src="' . htmlspecialchars($cover_img) . '" alt="Gallerian Kansikuva">';
                if (isset($user_role) && $user_role === 1) {
                    echo '<a id="change_cover" href="javascript:void(0);"><i class="fa fa-pen"></i> Vaihda kansikuva</a>';
                }
            ?>
        </div>
        
        <!-- Gallerian tiedot -->
        <div class="hero_item">
            <?php
                echo '<nav class="buttons">';
                if ($user_id == $owner) {
                    echo '<button id="edit_details" data-id="'. $gallery_id .'" data-name="'. $title .'" data-desc="'. $description .'" data-visi="'. $visibility .'" class="btn small_btn"><i class="fa fa-pen"></i> Tiedot</a>';
                    echo '<button class="btn small_btn"><i class="fa fa-gear"></i> Asetukset</a>';
                    echo '<button class="btn small_btn"><i class="fa fa-user-plus"></i> Kutsu</a>';
                } else if ($user_role === 1 && $user_id !== $owner) {
                    echo '<button id="edit_details" data-id="'. $gallery_id .'" data-name="'. $title .'" data-desc="'. $description .'" class="btn small_btn"><i class="fa fa-pen"></i> Tiedot</a>';
                    echo '<button class="btn small_btn"><i class="fa fa-user-plus"></i> Kutsu</a>';
                    echo '<a class="btn small_btn" href="poistu_galleriasta.php?g=' . $gallery_id . '"><i class="fa fa-sign-out-alt"></i> Poistu galleriasta</a>';
                } else if ($user_role == 2 || $user_role == 3) {
                    echo '<a class="btn small_btn" href="poistu_galleriasta.php?g=' . $gallery_id . '"><i class="fa fa-sign-out-alt"></i> Poistu galleriasta</a>';
                } else if (!isset($user_role)) {
                    echo '<a class="btn small_btn" href="liity_galleriaan.php?g=' . $gallery_id . '"><i class="fa fa-sign-in-alt"></i> Liity Galleriaan</a>';
                }
                echo '</nav>';

                echo '<h1>'. $title .'</h1>';
                echo '<p>'. $description .'</p>';
                echo '<p><strong>Omistaja:</strong> '. $owner_username .'</p>';
                echo '<p><strong>Luotu:</strong> '. $created .'</p>';
                echo '<p><strong>Päivitetty:</strong> '. $updated .'</p>';
                echo '<p><strong>Jäsenet:</strong> '. $member_count .' - <a href="javascript:void(0)">Näytä</a></p>';
                echo '<p><strong>Kuvia:</strong> '. $image_count .'</p>';
            
                if ($visibility == '1') {
                    echo '<p><strong>Näkyvyys:</strong> Yksityinen</p>';
                } else if ($visibility == '2') {
                    echo '<p><strong>Näkyvyys:</strong> Kaverit</p>';
                } else if ($visibility == '3') {
                    echo '<p><strong>Näkyvyys:</strong> Julkinen</p>';
                }
                ?>
    
        </div>
    </div>

    <!-- Kuvien lisäys ja hallinta -->
    <nav class="gallery_options">
        <?php
            if ($user_role == 1) {
                echo '<a href="lisaa_kuvia.php?g='. $gallery_id .'"><i class="fa fa-upload"></i> Lisää Kuvia</a>';
                echo '<a href="javascript:void(0)" id="manage_images"><i class="fa fa-pen-to-square"></i> Muokkaa Kuvia</a>';
            } else if ($user_role == 2) {
                echo '<a href="lisaa_kuvia.php?g='. $gallery_id .'"><i class="fa fa-upload"></i> Lisää Kuvia</a>';
                echo '<a href="javascript:void(0)" id="manage_images"><i class="fa fa-pen-to-square"></i> Muokkaa Kuvia <span class="small_txt">(omat)</span></a>';
            }
        ?>
    </nav>
    
    <!-- Kuvien lajittelu ja rajaus -->
    <div class="filter_container">
        <div class="filters">
            <label for="filter_images"><i class="fa fa-magnifying-glass"></i></label>
            <input type="text" id="filter_images" placeholder="Etsi kuvaa">
    
            <label for="sort_images"><i class="fa fa-sort"></i></label>
            <select id="sort_images" name="sort_images">
                <option value="new">Uusin Ensin</option>
                <option value="old">Vanhin Ensin</option>
                <option value="az">Nimi A-Z</option>
                <option value="za">Nimi Z-A</option>
            </select>
        </div>

        <div class="buttons">
            <button id="grid_view" title="Grid-view" class="func_btn view_btn active"><i class="fa fa-grip"></i></button>
            <button id="list_view" title="List-view" class="func_btn view_btn"><i class="fa fa-list"></i></button>
        </div>
    </div>
    
    <!-- Kuvagalleria -->
    <div class="gallery_container">
        <?php
            if (isset($gallery_images) && !empty($gallery_images)) {
                usort($gallery_images, function($a, $b) {
                    return strtotime($b['uploaded']) - strtotime($a['uploaded']);
                });
                foreach ($gallery_images as $key => $image) {
                    $gallery_images[$key] = array_map('htmlspecialchars', $image);

                    // Kuvan tiedot (kuvaelementti)
                    echo '<div class="gallery_item" data-name="'. $image['title'] .'" data-author="'. $image['username'] .'" data-date="'. $image['uploaded'] .'">';
                        echo '<div class="image_container">';
                            echo '<a href="kuva.php?k='. $image['image_id'] .'"><img src="../'.  $image['url'] .'" alt="'.  $image['description'] .'">';
                                echo '<p class="view_txt">Näytä kuva</p>';
                            echo '</a>';
                        echo '</div>';
                        echo '<div class="image_info">';
                            if (strlen($image['title']) === 0) {
                                echo '<h5 class="title_txt grey">Ei otsikkoa</h5>';
                            } else {
                                echo '<h5 class="title_txt">'.  $image['title'] .'</h5>';
                            }    
                            if (strlen($image['description']) === 0) {
                                echo '<p class="desc_txt grey">Ei kuvausta</p>';
                            } else {
                                echo '<p class="desc_txt">'. $image['description'] .'</p>';
                            }
                            echo '<p>'.  $image['username'] .'</p>';
                            echo '<p>'.  $image['uploaded'] .'</p>';
                            echo '<div class="buttons">';
                                echo '<button class="func_btn small_btn"><i class="fa fa-heart"></i></button>';
                                echo '<button onclick="" class="func_btn small_btn"><i class="fa fa-comment"></i></button>';
                                echo '<button class="func_btn small_btn"><i class="fa fa-share"></i></button>';
                                echo '<button data-url="'. $image['url'] .'" data-name="'. $image['title'] .'" class="func_btn small_btn download_btn"><i class="fa fa-download"></i></button>';
                                echo '<span class="edit_btns">';
                                    if ($user_role == 1 || $user_id == $image['user_id']) {
                                        echo '<button data-gallery="'. $gallery_id .'" data-id="'. $image['image_id'] .'" data-url="'. $image['url'] .'" data-title="'. $image['title'] .'" data-desc="'. $image['description'] .'" class="func_btn small_btn edit_btn green"><i class="fa fa-pen"></i></button>';
                                        echo '<button data-gallery="'. $gallery_id .'" data-id="'. $image['image_id'] .'" data-url="'. $image['url'] .'" data-title="'. $image['title'] .'" class="func_btn small_btn delete_btn red"><i class="fa fa-trash"></i></button>';
                                    } else if ($user_role == 2 && $image['user_id'] == $user_id) {
                                        echo '<button data-id="'. $image['user_id'] .'" data-url="'. $image['url'] .'" data-title="'. $image['title'] .'" data-desc="'. $image['description'] .'" class="func_btn small_btn edit_btn green"><i class="fa fa-pen"></i></button>';
                                        echo '<button data-gallery="'. $gallery_id .'" data-id="'. $image['image_id'] .'" data-url="'. $image['url'] .'" data-title="'. $image['title'] .'" class="func_btn small_btn delete_btn red"><i class="fa fa-trash"></i></button>';
                                    } else {
                                        echo '<p class="red"><i class="fa fa-eye-slash"></i></p>';
                                    }
                                echo '</span>';
                            echo '</div>';
                            
                        echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="large_txt grey">Ei vielä kuvia</p>';
            }
        ?>
    </div>


            
    
    
    

</main>

<?php
    include 'footer.php';
?>