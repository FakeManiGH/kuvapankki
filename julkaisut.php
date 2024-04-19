<?php
    $title = '# Julkaisut';
    $css = 'css/julkaisut.css';
    $js = 'scripts/julkaisut.js';
    include 'header.php';
    checkSession();

    if (is_logged_in()) {
        require_once 'includes/connections.php';
        require_once 'includes/gallery_model.php';
        $galleries = get_write_access_galleries($pdo, $_SESSION['user_id']);
    }
?>

<main>

    <!-- Show post form only if user is logged in -->
    <?php if (is_logged_in()) { ?>

        <button id="post_button">Luo uusi julkaisu <i class="fa fa-chevron-down"></i></button>
        
        <div id="form_container" class="hero_item">
            <!-- Post form -->
            <form id="post_form" method="POST" enctype="multipart/form-data">
                <label for="images[]">Valitse kuva(t) <strong class="red">*</strong></label>
                <input style="display: none;" id="image_input" type="file" name="images[]" multiple>

                <button onclick="document.getElementById('image_input').click();" type="button" class="small_btn"><i class="fa fa-upload"></i> Valitse kuvat</button>

                <div id="preview_container" class="post_images"></div>

                <label for="gallery"> Valitse galleria <strong class="red">*</strong></label>
                <select id="gallery_select" name="gallery" required>
                    <option value="empty">Valitse galleria:</option>
                    <?php
                        $galleries = get_write_access_galleries($pdo, $_SESSION['user_id']);
                        foreach ($galleries as $key => $gallery) {
                            $galleries[$key] = array_map('htmlspecialchars', $gallery);
                            echo "<option value='". $gallery['gallery_id'] ."'>". $gallery['name'] ."</option>";
                        }
                    ?>
                </select>

                <p class="grey">Eikö sinulla ole galleriaa? <a href="luo_galleria.php">Luo uusi</a> tai <a href="etsi.php">Etsi galleriaa</a> mihin liittyä.</p>

                <label for="description">Kuvaus <strong class="red">*</strong></label>
                <textarea id="post_desc" name="description" placeholder="Anna kuvaus julkaisulle..." required></textarea>

                <span class="buttons">
                    <button type="submit"><i class="fa fa-hashtag"></i> Julkaise</button>
                    <button id="form_reset" class="func_btn small_txt" type="reset">Peruuta</button>
                </span>
            </form>

            <!-- Post errors and success messages -->
            <p id="post_error" class="error_msg"></p>
        </div>
        <p id="post_success" class="success_msg"></p>
    <?php } ?>

    <!-- errors, while getting posts -->
    <p id="posts_get_error" class="error_msg"></p>

    <!-- Post list -->
    <ul id="post_list" class="post_list"></ul>

</main>


<?php
    include 'footer.php';
    $pdo = null;
?>