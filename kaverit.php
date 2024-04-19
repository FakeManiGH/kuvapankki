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

    <?php
        if (isset($_SESSION['friend_error'])) {
            echo '<h4 class="red">' . $_SESSION['friend_error'] . '</h4>';
            unset($_SESSION['friend_error']);
        }

        if (isset($_SESSION['friend_success'])) {
            echo '<h4 class="green">' . $_SESSION['friend_success'] . '</h4>';
            unset($_SESSION['friend_success']);
        }
    ?>

    <h1>Kaverit</h1>

    <div class="page_navigation">
        <nav class="page_links">
            <?php include 'includes/page_navigation.php';?>
        </nav>    
    </div>

    <p>Tältä sivulta löydät listan kavereistasi. Jos sinulla ei vielä ole kavereita, <a href="lisaa_kaveri.php">lisää kaveri</a>.</p>

    <div class="filter_container">
        <h3>Omat Kaverit</h3>
        <div class="filters">
            <label for="filter_friends"><i class="fa fa-magnifying-glass"></i></label>
            <input type="text" id="filter_friends" name="filter_friends" placeholder="Etsi kaveria">

            <label for="sort_friends"><i class="fa fa-sort"></i></label>
            <select id="sort_friends" name="sort_friends">
                <option value="za" class="bold_txt">Lajittele</option>
                <option value="az">Nimi A-Z</option>
                <option value="za">Nimi Z-A</option>
            </select>       
        </div>
    </div>
    

    <div id="friend_list" class="friend_list">
        <?php
            foreach ($friends as $key => $friend) {
                $friends[$key] = array_map('htmlspecialchars', $friend);

                echo '<div class="friend" data-name="'.$friend['username'].'">';
                echo '<div class="profile_info">';
                    echo '<img class="profile_picture" src="' . $friend['user_img'] . '" alt="Profiilikuva">';
                    echo '<a class="func_btn" href="profiili.php?u=' . $friend['user_id'] . '">' . $friend['username'] . '</a>';
                echo '</div>';
                echo '<nav class="friend_info">';
                    echo '<a class="func_btn" href="laheta_viesti.php?u=' . $friend['user_id'] . '" title="Lähetä viesti"><i class="fa-regular fa-message"></i> <span class="small_txt">Viesti</span></a>';
                    echo '|';
                    echo '<a class="func_btn" href="includes/friends_remove.php?u=' . $friend['user_id'] . '" title="Poista kaveri"><i class="fa-solid fa-trash"></i> <span class="small_txt">Poista</span></a>';
                echo '</nav>';
                echo '</div>';
            }
        ?>
    </div>

    

</main>

<?php
    include 'footer.php';
    $pdo = null;
?>