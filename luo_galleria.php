<?php
    $title = 'Luo Uusi Galleria';
    $css = 'css/luo_galleria.css';
    $js = 'scripts/luo_galleria.js';
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

    <p>Luo uusi galleria täyttämällä alla oleva lomake. <span class="red"><strong>( * pakollinen kenttä )</strong></span></p>
    
    <form id="create_gallery" class="page_form" action="includes/gallery_create.php" method="post" enctype="multipart/form-data">

        <!-- Perustiedot -->
        <span class="inline">
            <label for="name">Gallerian Nimi <span class="red">*</span></label>
            <input type="text" id="name" name="name" placeholder="Gallerian nimi" required>
        </span>
            <p id="name_counter" class="length_counter">0 / 75</p>

        <span class="inline">
            <label for="description">Kuvaus <span class="red">*</span></label>
            <textarea id="description" name="description" placeholder="Gallerian kuvaus" required></textarea>
        </span>
            <p id="desc_counter" class="length_counter">0 / 400</p>


        <!-- Näkyvyys -->
        <span class="inline">
            <label for="visibility" id="visibility_info" >Näkyvyys <span class="red">*</span> <a href="javascript:void(0)" onclick="showVisibilityPopup()" class="info_btn"> <i class="fa fa-info-circle"></i></a></label>
            <select id="visibility" name="visibility" required>
                <option id="private" value="1">Yksityinen</option>
                <option id="friends" value="2">Kaverit</option>
                <option id="public" value="3">Julkinen</option>
            </select>

            <ul id="visibility_popup" class="info_popup">
                <button id="close_visibility" class="popup_close red"><i class="fa fa-circle-xmark"></i></button>
                <h4>Tietoa näkyvyydestä</h4>
                <li><strong>Yksityinen:</strong> Vain sinä näet gallerian.</li>
                <li><strong>Kaverit:</strong> Vain sinä ja lisäämäsi kaverit näkevät gallerian.</li>
                <li><strong>Julkinen:</strong> Kaikki näkevät gallerian, mutta vain jäsenet voivat lisätä sisältöä.</li>
            </ul>
        </span>


        <!-- Jäsenet -->
        <fieldset id="select_users_area">
            <span class="inline" style="margin-bottom: 20px;">
                <label for="selects_users" id="users_info">Valitse Jäesenet <a href="javascript:void(0)" onclick="showUsersPopup()" class="info_btn"> <i class="fa fa-info-circle"></i></a></label>
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
                    <ul id="users_popup" class="info_popup">
                    <button id="close_users_popup" class="popup_close red"><i class="fa fa-circle-xmark"></i></button>
                        <h4>Tietoa jäsenistä</h4>
                        <li>Valituille jäsenille lähetetään kutsu galleriaan. Kutsu on voimassa 7 päivää ja poistuu automaattisesti, jos kutsuun ei vastata.</li>
                        <li>Kutsu antaa kutsutulle käyttäjälle lukuoikeuden galleriaan, kun tämä hyväksyy kutsun. Voit muuttaa jäsenten oikeuksia gallerian asetuksista.</li>
                    </ul>
                </span>
            </span>

            <span id="selected_users_container" class="inline">
                <label for="selected_users[]">Valitut Jäsenet</label>
                <select class="hidden" type="hidden" id="selected_users" name="selected_users[]" multiple></select>
                <ul id="selected_users_list"></ul>
            </span>
        </fieldset>
        

        <!-- Kansikuva -->
        <span class="inline">
            <label for="cover_img">Kansikuva</label>
            <span class="inline_x2">
                <div class="image_container">
                    <div class="cover_img_wrapper">
                        <img id="cover_img_preview" src="img/gallery_default.jpg" alt="Gallery cover image preview">
                    </div>
                </div>
                <input type="file" id="cover_img" name="cover_img">
                <a class="func_btn red" id="clear_preview_img" href="javascript:void(0);"><i class="fa fa-trash"></i></a>
            </span>
        </span>

        <p id="file_err">
            <?php if (isset($_SESSION['image_update_err'])) {
                echo $_SESSION['image_update_err'];
                unset($_SESSION['image_update_err']);
            }
            ?>
        </p>
        

        <!-- Avainsanat -->
        <span class="inline">
            <label for="tags">Avainsanat</label>
            <input type="text" id="tags" name="tags" placeholder="esim. koiria maisemat ufot...">
        </span>
        <p id="tags_counter" class="length_counter">0 / 15kpl</p>

        <p>
            <?php if (isset($_SESSION['gallery_create_err'])) {
                echo $_SESSION['gallery_create_err'];
                unset($_SESSION['gallery_create_err']);
            } ?>
        </p>
        
        <!-- Napit -->
        <span class="buttons">
            <button type="submit">Luo Galleria</button>
            <button id="reset" type="reset">Tyhjennä</button>
        </span>

        <!-- Virheilmoitukset -->
        <p id="form_errors" class="error_msg"></p>
    </form>


</main>

<?php
    include 'footer.php';
?>