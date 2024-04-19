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

    <p>Luo uusi galleria täyttämällä alla oleva lomake. Voit samalla määritellä gallerian näkyvyyden ja jäsenet. Voit myös lisätä galleriaan kansikuvan ja avainsanoja.
        Lisätietoa gallerioista löydät <a href="teitopankki.php">tietopankista</a>. <strong class="red">*</strong> -pakollinen kenttä.</p>
    
    <div class="hero_item">
        <form id="create_gallery" method="post" enctype="multipart/form-data">

            <!-- Gallery name -->
            <span class="form_group">
                <label for="name">Gallerian nimi <span class="red">*</span></label>
                <p class="small_txt grey">Anna gallerialle nimi. Gallerian nimi voi olla enintään 75 merkkiä pitkä ja uniikki.</p>
                <input type="text" id="gallery_name" name="name" placeholder="Anna gallerialle nimi" required>
                <p id="name_counter" class="length_counter"></p>

                <!-- Error message -->
                <p id="gallery_name_error" class="error_msg"></p>
            </span>

            <!-- Description -->
            <span class="form_group">
                <label for="description">Kuvaus <span class="red">*</span></label>
                <p class="small_txt grey">Kuvaile galleriaa. Kuvaus voi olla enintään 400 merkkiä pitkä.</p>
                <textarea id="gallery_description" name="description" placeholder="Kuviale galleriaa..." required></textarea>
                <p id="desc_counter" class="length_counter"></p>

                <!-- Error message -->
                <p id="gallery_desc_error" class="error_msg"></p>
            </span>

            <!-- Category -->
            <span class="form_group">
                <label for="category">Kategoria <span class="red">*</span></label>
                <p class="small_txt grey">Valitse gallerialle sopiva kategoria.</p>
                <select id="gallery_category" name="category" required>
                    <option value="0">Valitse Kategoria</option>
                    <option value="1">Luonto</option>
                    <option value="2">Eläimet</option>
                    <option value="3">Ihmiset</option>
                    <option value="4">Kaupunki</option>
                    <option value="5">Maisemat</option>
                    <option value="6">Taide</option>
                    <option value="7">Muoti</option>
                    <option value="8">Ruoka</option>
                    <option value="9">Kulttuuri</option>
                    <option value="10">Urheilu</option>
                    <option value="11">Tekniikka</option>
                    <option value="12">Muu</option>
                </select>

                <!-- Error message -->
                <p id="gallery_category_error" class="error_msg"></p>
            </span>

            <!-- Visibility -->
            <span class="form_group">
                <label for="visibility" id="visibility_info">Näkyvyys <span class="red">*</span> <a href="javascript:void(0)" onclick="showVisibilityPopup()" class="info_btn"> <i class="fa fa-info-circle"></i></a></label>
                <p class="small_txt grey">Valitse gallerian näkyvyys. Määrittää, ketkä näkevät gallerian sisällön ja julkaisut.</p>
                <select id="gallery_visibility" name="visibility" required>
                    <option value="1">Julkinen</option>
                    <option value="2">Kaverit</option>
                    <option value="3">Vain jäsenet</option>
                </select>

                <ul id="visibility_popup" class="info_popup">
                    <button type="button" id="close_visibility_info" class="popup_close func_btn"><i class="fa fa-circle-xmark"></i></button>
                    <h4>Tietoa näkyvyydestä</h4>
                    <li><strong class="green">Julkinen:</strong> Gallerian julkaisut ja kuvat on kaikkien nähtävissä.</li>
                    <li><strong class="green">Kaverit:</strong> Gallerian julkaisut ja kuvat näkyvät jäsenten kavereille.</li>
                    <li><strong class="green">Vain jäsenet:</strong> Gallerian julkaisut ja kuvat näkyvät vain sen jäsenille.</li>
                </ul>

                <!-- Error message -->
                <p id="gallery_visibility_error" class="error_msg"></p>
            </span>


            <!-- Type -->
            <span class="form_group">
                <label for="type" id="type_info">Kuka voi liittyä? <span class="red">*</span> <a href="javascript:void(0)" onclick="showTypePopup()" class="info_btn"> <i class="fa fa-info-circle"></i></a></label>
                <p class="small_txt grey">Valitse, kuka voi liittyä galleriaan. Jäsenet eivät voi automaattisesti lisätä kuvia ja sisältöä. Lisätietoa <i class="fa fa-info-circle"></i> painikkeella.</p>
                <select id="gallery_type" name="type" required>
                    <option id="public" value="1">Kuka tahansa</option>
                    <option id="friends" value="2">Kaverit</option>
                    <option id="invite" value="3">Vain kutsutut</option>
                    <option id="private" value="4" selected>Yksityinen</option>
                </select>

                <ul id="type_popup" class="info_popup">
                    <button type="button" id="close_type_info" class="popup_close func_btn"><i class="fa fa-circle-xmark"></i></button>
                    <h4>Tietoa jäsenistä:</h4>
                    <li><strong class="green">Vapaa kaikille:</strong> Kuka tahansa voi liittyä galleriaan, omistaja ja admin jäsenet hallitsevat jäsenyyksiä.</li>
                    <li><strong class="green">Kavereille:</strong> Kaverisi voivat liittyä galleriaan.</li>
                    <li><strong class="green">Kutsutut jäsenet:</strong> Vain kutsutut jäsenet pääsevät liittymään galleriaan.</li>
                    <li><strong class="green">Yksityinen:</strong> Galleria on henkilökohtainen.</li>
                    <li><strong class="green">Oikeudet:</strong> Jäsenten oikeuksia gallerian sisällä voit muuttaa gallerian asetuksista.</li>
                </ul>

                <!-- Error message -->
                <p id="gallery_type_error" class="error_msg"></p>
            </span>

            <!-- Invite users -->
            <div id="select_users_area">
                <span class="form_group">
                    <label for="selects_users" id="users_info">Kutsu jäseneksi <a href="javascript:void(0)" onclick="showUsersPopup()" class="info_btn"> <i class="fa fa-info-circle"></i></a></label>
                    <p class="small_txt grey">Lähetä kaverille kutsu galleriaan. Toistaiseksi et voi suoraan kutsua käyttäjää, joka ei ole kaverisi. <a href="lisaa_kaveri.php">Lisää kaverieta</a>, joita kutsua.</p>
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
                                    echo '<option value="'. $friend['user_id'] .'">'. $friend['username'] .'</option>';
                                }
                            ?>
                        </select>
                        <button type="button" class="small_btn" id="add_user">Lisää</button>
                        <ul id="users_popup" class="info_popup">
                        <button type="button" id="close_users_popup" class="popup_close func_btn"><i class="fa fa-circle-xmark"></i></button>
                            <h4>Tietoa jäsenistä</h4>
                            <li>Valituille jäsenille lähetetään kutsu galleriaan.</li>
                            <li>Kutsutuilla jäsenillä ei automaattisesti ole oikeutta lisätä kuvia ja sisältöä, mutta he näkevät gallerian sisällön ja voivat ladata kuvia galleriasta.</li>
                        </ul>
                    </span>
                </span>

                <span id="selected_users_container" class="inline">
                    <label for="selected_users[]" type="hidden" class="hidden">Valitut jäsenet</label>
                    <select class="hidden" type="hidden" id="selected_users" name="selected_users[]" multiple></select>
                    <ul id="selected_users_list"></ul>
                </span>

                <!-- Error message -->
                <p id="gallery_users_error" class="error_msg"></p>
            </div>
            

            <!-- Tags -->
            <span class="form_group">
                <label for="tags">Avainsanat <strong class="red">*</strong></label>
                <p class="small_txt grey">Lisää avainsanoja, jotka kuvaavat galleriaa. Avainsanoja tulee olla vähintään 1 ja enintään 15kpl.</p>
                <input type="text" id="gallery_tags" name="tags" placeholder="esim. koiria maisemat ufot...">
                <p id="tags_counter" class="length_counter"></p>

                <!-- Error message -->
                <p id="gallery_tags_error" class="error_msg"></p>
            </span>


            <!-- Bottom buttons -->
            <span class="buttons">
                <button type="submit">Luo Galleria</button>
                <button id="reset" type="reset">Tyhjennä</button>
            </span>

            <!-- Error messages -->
            <p id="form_errors" class="error_msg"></p>
        </form>
    </div>

</main>

<?php
    include 'footer.php';
?>