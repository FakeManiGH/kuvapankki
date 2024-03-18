<?php
    $title = 'Luo Uusi Galleria';
    $css = 'css/lisaa_galleria.css';
    $js = 'scripts/lisaa_galleria.js';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautuminen=vaaditaan');
        die();
    }

    // Haetaan kaverilista
    require_once 'includes/friends_view.php';


?>

<main>

    <h1>Luo Uusi Galleria</h1>

    <p>Luo uusi galleria täyttämällä alla oleva lomake.</p>
    
    <form id="create_gallery" class="page_form" action="includes/gallery_create.php" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Perustiedot</legend>

            <span class="inline">
                <label for="name">Nimi <span class="red">*</span></label>
                <input type="text" id="name" name="name" placeholder="Gallerian nimi" required>
            </span>
                <p id="name_counter" class="length_counter">0 / 75</p>

            <span class="inline">
                <label for="description">Kuvaus <span class="red">*</span></label>
                <textarea id="description" name="description" placeholder="Gallerian kuvaus" required></textarea>
            </span>
                <p id="desc_counter" class="length_counter">0 / 400</p>

            <span class="inline">
                <label for="visibility">Näkyvyys <a href="javascript:void(0)" onclick="showVisibilityPopup()" id="visibility_info"> <i class="fa fa-info-circle"></i></a></label>
                <select id="visibility" name="visibility" required>
                    <option id="private" value="1">Yksityinen</option>
                    <option id="friends" value="2">Kaverit</option>
                    <option id="public" value="3">Julkinen</option>
                </select>
                <ul id="visibility_popup">
                <a href="javascript:void(0)" id="close_popup">X</a>
                <li><strong>Yksityinen:</strong> Vain sinä näet gallerian.</li>
                <li><strong>Kaverit:</strong> Vain sinä ja lisäämäsi kaverit näkevät gallerian.</li>
                <li><strong>Julkinen:</strong> Kaikki näkevät gallerian, mutta vain jäsenet voivat lisätä sisältöä.</li>
                </ul>
            </span>

            <p id="basic_info_err" class="error_msg"></p>
        </fieldset>

        <fieldset id="select_users_area">
            <legend>Jäsenet</legend>
            
            <span class="inline">
                <label for="selects_users">Valitse Jäesenet</label>
                <span class="inline_x2">
                    <select id="select_users">
                        <?php
                            if (empty($friends)) {
                                echo '<option value="0">Ei Kavereita</option>';
                            } else {
                                echo '<option value="0">Valitse Kaveri</option>';
                            }
                            asort($friends);
                            foreach ($friends as $friend) {
                                echo '<option value="' . $friend['user_id'] . '">' . $friend['username'] . '</option>';
                            }
                        ?>
                    </select>
                    <button type="button" id="add_user">Lisää</button>
                </span>
            </span>

            <span id="selected_users_area" class="inline">
                <label for="selected_users[]">Valitut Jäsenet</label>
                <select id="selected_users" name="selected_users[]" size="0" multiple></select>
            </span>
        </fieldset>
        
        <fieldset>
            <legend>Lisätiedot</legend>

            <span class="inline">
                <label for="cover_img">Kansikuva</label>
                <span class="inline_x2">
                    <div class="image_container">
                        <div class="cover_img_wrapper">
                            <img id="cover_img_preview" src="img/gallery_default.jpg" alt="Gallery cover image preview">
                        </div>
                    </div>
                    <input type="file" id="cover_img" name="cover_img">
                    <a id="clear_preview_img" href="javascript:void(0);"><i class="fa fa-trash"></i></a>
                </span>
            </span>

            <p id="file_err">
                <?php if (isset($_SESSION['image_update_err'])) {
                    echo $_SESSION['image_update_err'];
                    unset($_SESSION['image_update_err']);
                }
                ?>
            </p>

            <span class="inline">
                <label for="tags">Avainsanat</label>
                <input type="text" id="tags" name="tags" placeholder="esim. koiria maisemat ufot...">
            </span>
            <p id="tags_counter" class="length_counter">0 / 15kpl</p>

            <p id="more_info_err" class="error_msg"></p>
        </fieldset>

        <p>
            <?php if (isset($_SESSION['gallery_create_err'])) {
                echo $_SESSION['gallery_create_err'];
                unset($_SESSION['gallery_create_err']);
            } ?>
        </p>
        
        <span class="buttons">
            <button type="submit">Luo Galleria</button>
            <button id="reset" type="reset">Tyhjennä</button>
        </span>
    </form>


</main>

<?php
    include 'footer.php';
?>