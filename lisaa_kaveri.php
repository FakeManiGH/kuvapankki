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
        include 'includes/user_search.php';
    } else {
        include 'includes/friends_requests_get.php';
    }
    
?>

<main>

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

    <h1>Lisää Kaveri</h1>

    <div class="page_navigation">
        <nav class="page_links">
            <?php include 'includes/page_navigation.php';?>
        </nav>    
    </div>
    
    <p>Tällä sivulla voit lisätä kavereita. Alla olevalla haulla voit lisätä kavereita, sekä näet omat kaveripyyntösi.</p>
    
    <!-- Käyttäjän haku lomake -->
    <form class="page_form" method="POST">
        <h3>Etsi Käyttäjää</h3>
        <label for="search_user" class="hidden"></label>
        <span class="inline">
            <input type="text" id="search_input" name="search_user" placeholder="Anna käyttäjänimi" required>
            <button type="submit">Etsi</button>
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<a href="javascript:void(0)" id="search_reset">Tyhjennä</a>';
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
    </form>

    <div id="user_list" class="friend_list">
        <?php
            if (isset($user_list)) {
                foreach ($user_list as $key => $user) {
                    $user_list[$key] = array_map('htmlspecialchars', $user);
                    echo '<div class="friend">';
                    echo '<div class="profile_info">';
                    echo '<a href="kayttaja.php?u='.$user['user_id'].'"><i class="fa fa-user"></i> ' . $user['username'] . '</a>';
                    echo '</div>';
                        if (is_friend($pdo, $_SESSION['user_id'], $user['user_id'])) {
                            echo '<p class="small_txt">Kaverisi</p>';
                        } else if (request_already_sent($pdo, $_SESSION['user_id'], $user['user_id'])) {
                            echo '<p class="small_txt">Kaveripyyntö lähetetty</p>';
                        } else {
                            echo '<a href="includes/friends_requests_send.php?u=' . $user['user_id'] . '"><i class="fa-solid fa-circle-plus"></i> <span class="small_txt">LISÄÄ KAVERIKSI</span></a>';
                        }
                    echo '</div>';
                } if (empty($user_list)) {
                    echo '<p class="small_txt grey">Ei hakutuloksia.</p>';
                }
            }
        ?>
    </div>

    <!-- Lähetetyt Kaveripyynnöt -->
    <h3>Lähetetyt Kaveripyynnöt</h3>

    <div class="friend_requests">
        <?php
        if (isset($sent_requests)) {
            foreach ($sent_requests as $key => $request) {
                $sent_requests[$key] = array_map('htmlspecialchars', $request);
                echo '<div class="friend">';
                echo '<a href="kayttaja.php?u=' . $request['friend_id'] . '" title="Näytä Käyttäjä"><i class="fa fa-user"></i> ' . $request['username'] . '</a>';
                echo '<p class="small_txt">Lähetetty: ' . $request['date'] . '</p>';
                echo '<a href="includes/friends_requests_cancel.php?u=' . $request['friend_id'] . '" title="Peruuta Kaveripyyntö"><i class="fa-solid fa-circle-xmark large_txt red"></i></a>';
                echo '</div>';
            } if (empty($sent_requests)) {
                echo '<p class="small_txt grey">Ei lähetettyjä kaveripyyntöjä.</p>';
            }
        }
        ?>
    </div>

    <!-- Saapuneet Kaveripyynnöt -->
    <h3>Saapuneet Kaveripyynnöt</h3>

    <div class="friend_requests">
        <?php
        if (isset($requests)) {
            foreach ($requests as $key => $request) {
                $requests[$key] = array_map('htmlspecialchars', $request);
                echo '<div class="friend">';
                echo '<a href="kayttaja.php?u=' . $request['your_id'] . '" title="Näytä Käyttäjä"><i class="fa fa-user"></i> ' . $request['username'] . '</a>';
                echo '<p class="small_txt">Saapunut: ' . $request['date'] . '</p>';
                echo '<div class="friend_info">';
                echo '<a href="includes/friends_requests_accept.php?u=' . $request['your_id'] . '" title="Hyväksy Kaveripyyntö"><i class="fa-solid fa-circle-check large_txt green"></i></a>';
                echo '<a href="includes/friends_requests_reject.php?u=' . $request['your_id'] . '" title="Hylkää Kaveripyyntö"><i class="fa-solid fa-circle-xmark large_txt red"></i></a>';
                echo '</div>';
                echo '</div>';
            } if (empty($requests)) {
                echo '<p class="small_txt grey">Ei saapuneita kaveripyyntöjä.</p>';
            }
        }
        ?>
    </div>

</main>

<?php
    include 'footer.php';
    $pdo = null;
    $stmt = null;
?>