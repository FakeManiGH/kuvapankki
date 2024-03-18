<?php

    $title = 'Lisää Kaveri';
    $css = 'css/kaverit.css';
    $js = 'scripts/kaverit.js';
    include 'header.php';
    
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautuminen=vaaditaan');
        die();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include 'includes/friends_requests_get.php';
        include 'includes/friends_user_search.php';
    } else {
        include 'includes/friends_requests_get.php';
    }
    
?>

<main>

    <h1>Lisää Kaveri</h1>
    
    <p>Tällä sivulla voit lisätä kavereita. Alla olevalla haulla voit lisätä kavereita, sekä näet omat kaveripyyntösi.</p>

    <form class="page_form" method="POST">
    <fieldset>
        <legend>Hae Käyttäjää</legend>
        <label for="search_user" class="hidden"></label>
        <span class="inline">
            <input type="text" id="search_input" name="search_user" placeholder="Anna käyttäjänimi" required>
            <button type="submit">Etsi</button>
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<button type="reset" id="search_reset">Tyhjennä</button>';
            }
            ?>
        </span>
        <p class="error_msg">
            <?php if (isset($_SESSION['search_error'])) {
                echo $_SESSION['search_error'];
                unset($_SESSION['search_error']);
            }
            ?>
        </p>
    </fieldset>
    </form>

    <div id="user_list" class="friend_list">
        <?php
            if (isset($user_list)) {
                foreach ($user_list as $user) {
                    echo '<div class="friend">';
                    echo '<h5><i class="fa fa-user"></i> ' . $user['username'] . '</h5>';
                        if (is_friend($pdo, $_SESSION['user_id'], $user['user_id'])) {
                            echo '<p class="small_txt">Kaverisi</p>';
                        } elseif (request_already_sent($pdo, $_SESSION['user_id'], $user['user_id'])) {
                            echo '<p class="small_txt">Kaveripyyntö lähetetty</p>';
                        } else {
                            echo '<a href="includes/friends_requests_send.php?user='.$user['username'].'"><i class="fa-solid fa-circle-plus"></i> <span class="small_txt">Lisää Kaveriksi</span></a>';
                        }
                    echo '</div>';
                } if (empty($user_list)) {
                    echo '<p class="small_txt grey">Ei hakutuloksia.</p>';
                }
            }
        ?>
    </div>
    
    <?php

        // Kaveripyyntöjen lähetys ilmoitukset
        if (isset($_SESSION['friend_request_error'])) {
            echo "<h4 class='red'>".$_SESSION['friend_request_error']."</h4>";
            unset($_SESSION['friend_request_error']);
        }
        if (isset($_SESSION['friend_request_success'])) {
            echo "<h4 class='green'>".$_SESSION['friend_request_success']."</h4>"; 
            unset($_SESSION['friend_request_success']);
        }

        // Pyyntöjen peruutus ilmoitukset
        if (isset($_SESSION['request_cancel_error'])) {
            echo "<h4 class='red'>".$_SESSION['request_cancel_error']."</h4>";
            unset($_SESSION['request_cancel_error']);
        }
        if (isset($_SESSION['request_cancel_success'])) {
            echo "<h4 class='green'>".$_SESSION['request_cancel_success']."</h4>"; 
            unset($_SESSION['request_cancel_success']);
        }

        // Pyyntöjen hyväksyntä ilmoitukset
        if (isset($_SESSION['request_accept_error'])) {
            echo "<h4 class='red'>".$_SESSION['request_accept_error']."</h4>";
            unset($_SESSION['request_accept_error']);
        }
        if (isset($_SESSION['request_accept_success'])) {
            echo "<h4 class='green'>".$_SESSION['request_accept_success']."</h4>"; 
            unset($_SESSION['request_accept_success']);
        }

        // Pyyntöjen hylkäys ilmoitukset
        if (isset($_SESSION['request_cancel_error'])) {
            echo "<h4 class='red'>".$_SESSION['request_cancel_error']."</h4>";
            unset($_SESSION['request_cancel_error']);
        }
        if (isset($_SESSION['request_cancel_success'])) {
            echo "<h4 class='green'>".$_SESSION['request_cancel_success']."</h4>"; 
            unset($_SESSION['request_cancel_success']);
        }
    ?>

    <h4>Lähetetyt Kaveripyynnöt</h4>

    <div class="friend_requests">
        <?php
        if (isset($sent_requests)) {
            foreach ($sent_requests as $request) {
                echo '<div class="friend">';
                echo '<h5><i class="fa fa-user"></i> ' . $request['username'] . '</h5>';
                echo '<p class="small_txt">Lähetetty: ' . $request['date'] . '</p>';
                echo '<a href="includes/friends_requests_cancel.php?user='.$request['username'].'" title="Peruuta Kaveripyyntö"><i class="fa-solid fa-circle-xmark large_txt red"></i></a>';
                echo '</div>';
            } if (empty($sent_requests)) {
                echo '<p class="small_txt grey">Ei lähetettyjä kaveripyyntöjä.</p>';
            }
        }
        ?>
    </div>

    <h4>Saapuneet Kaveripyynnöt</h4>

    <div class="friend_requests">
        <?php
        if (isset($requests)) {
            foreach ($requests as $request) {
                echo '<div class="friend">';
                echo '<h5><i class="fa fa-user"></i> ' . $request['username'] . '</h5>';
                echo '<p class="small_txt">Saapunut: ' . $request['date'] . '</p>';
                echo '<div class="friend_info">';
                echo '<a href="includes/friends_requests_accept.php?user='.$request['username'].'" title="Hyväksy Kaveripyyntö"><i class="fa-solid fa-circle-check large_txt"></i></a>';
                echo '<a href="includes/friends_requests_decline.php?user='.$request['username'].'" title="Hylkää Kaveripyyntö"><i class="fa-solid fa-circle-xmark large_txt red"></i></a>';
                echo '</div>';
                echo '</div>';
            } if (empty($requests)) {
                echo '<p class="small_txt grey">Ei saapuneita kaveripyyntöjä.</p>';
            }
        }
        
        // Suljetaan tietokantayhteys ja nollaataan muuttujat
        $pdo = null;
        $stmt = null;
        ?>
    </div>

</main>

<?php
    include 'footer.php';
?>