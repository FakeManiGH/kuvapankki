<?php
    $title = 'Kaverit';
    $css = 'css/kaverit.css';
    $js = 'scripts/kaverit.js';
    include 'header.php';
    checkSession();

    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautuminen=vaaditaan');
        die();
    }

    require_once 'includes/friends_view.php';
?>

<main>

    <h1>Kaverit</h1>

    <p>Tällä sivulla näet kaverilistasi. Voit lisätä kavereita ja poistaa kavereita.</p>
    
    <form class="page_form">
        <fieldset>
            <legend>Hae Kaveria</legend>
            <label for="search_user" type="hidden" class="hidden"></label>
            <span class="inline">
                <input type="text" id="search_input" name="search_user" placeholder="Anna kaverin nimi" required>
            </span>
        </fieldset>
    </form>

    <h3>Omat Kaverit</h3>

    <div class="friend_list">
        <?php
            foreach ($friends as $friend) {
                echo '<div class="friend" onclick="window.location.href=\'view_user.php?user_id='. $_SESSION['user_id'] . '\'">';
                echo '<div class="profile_info">';
                echo '<img class="profile_picture" src="' . $friend['user_img'] . '" alt="Profiilikuva">';
                echo '<h4>' . $friend['username'] . '</h4>';
                echo '</div>';
                echo '<nav class="friend_info">';
                echo '<a href="profiili.php?user_id=' . $friend['user_id'] . '"><i class="fa-solid fa-circle-info"></i> <span class="small_txt link_text">Profiili</span></a>';
                echo '<a href="laheta_viesti.php?user_id=' . $friend['user_id'] . '"><i class="fa-regular fa-message"></i> <span class="small_txt link_text">Viesti</span></a>';
                echo '<a href="poista_kaveri.php?user_id=' . $friend['user_id'] . '"><i class="fa-solid fa-trash"></i> <span class="small_txt link_text">Poista</span></a>';
                echo '</nav>';
                echo '</div>';
            }
        ?>
    </div>

    

</main>

<?php
    include 'footer.php';
?>