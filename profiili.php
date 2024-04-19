<?php
    ob_start();
    $title = 'Oma Profiili';
    $css = 'css/profiili.css';
    $js = 'scripts/profiili.js';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautumen=vaaditaan');
        die();
    }

    require 'includes/connections.php';
    require 'includes/profile_model.php';
    require 'includes/profile_ctrl.php';
    include 'includes/storage_space_view.php';

    // Haetaan käyttäjän tiedot tietokannasta
    $user = get_user($pdo, $_SESSION['user_id']);

    $user_json = json_encode($user);

?>

<main>

    <?php
        if (isset($_SESSION['profile_update_success'])) {
            echo "<h4 class='green'>".$_SESSION['profile_update_success']."</h4>";
            unset($_SESSION['profile_update_success']);
        }
    ?>

    <h1>Oma Profiili</h1>

    <div class="page_navigation">
        <nav class="page_links">
            <?php include 'includes/page_navigation.php';?>
        </nav>    
    </div>

    <p>Täällä voit muokata profiilikuvaasi, käyttäjätietojasi ja salasanaasi. Voit myös tarkastella tallennustilaasi ja hallita tiedostoja.</p>

    <div class="hero_container">

        <!-- Tallennustila -->
        <div id="" class="hero_item">
            <h3>Tallennustila</h3>

            <p>Hallitse tallennustilaasi. Voit tarvittaessa tilata lisää tilaa tai tehdä tilaa uusille kuville tiedostojenhallinnan avulla.</p>

            <ul>
                <?php 
                    echo "<li><strong>Tilauksesi:</strong> ". htmlspecialchars($subscription['sub_name']) ."</li>";
                    echo "<li><strong>Tallennustila:</strong> ". htmlspecialchars($storage_space_view) ."</li>";
                    echo "<li><strong>Tilaa käytetty:</strong> ". htmlspecialchars($used_space_view) ."</li>";
                ?>
            </ul>

            <span>
                <label for="storage">Tallennustilan käyttö:</label>
                <span class="inline">
                    <?php 
                        if ($used_space_percentage > 80) {
                            echo "<progress id='storage' value='". $used_space_percentage ."' max='100' class='red'></progress>";
                        } else {
                            echo "<progress id='storage' value='". $used_space_percentage ."' max='100'></progress>";
                        }
                        echo "<p class='no_wrap'>". $used_space_percentage ." %</p>";
                    ?>
                </span>
            </span>

            <div class="buttons">
                <button class="small_btn" onclick="window.location.href='hallitse_tiedostoja.php'"><i class="fa-regular fa-folder-open"></i> Hallitse tiedostoja</button>
                <?php 
                    if ($subscription['sub_type'] !== 1) {
                        echo "<button class='small_btn' onclick='window.location.href=\"tilaus.php\"'><i class='fa-regular fa-pen-to-square'></i> Hallitse tilausta</button>";
                    } else {
                        echo "<button class='small_btn' onclick='window.location.href=\"tilaus.php\"'><i class='fa fa-shopping-cart'></i> Lisää tilaa</button>";
                    }
                ?>
            </div>
        </div>
    

        <!-- Profiilikuva -->
        <div class="hero_item">
            <h3>Profiilikuva</h3>

            <p>Vaihda profiilikuvaasi klikkaamalla "Vaihda profiilikuva" -painiketta. Suosittelemme käyttämään neliön muotoista kuvaa.</p>

            <form id="profile_img_form" enctype="multipart/form-data">
                <span class="inline">
                    <img id="profile_img" src="<?php echo htmlspecialchars($user['user_img']); ?>" class="user_image" alt="Profiilikuva">
                    <p class="error_msg" id="file_err"></p>
                    <p class="success_msg" id="file_success"></p>
                </span>

                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
            
                <div class="buttons">
                    <input type="file" class="hidden" type="hidden" name="userfile" id="userfile">
                    <button class="func_btn green" id="accept_file_select" type="submit"><i class="fa-solid fa-square-check"></i> <span class="small_txt">Hyväksy</span></button>
                    <button class="func_btn red" id="clear_file_select" type="reset"><i class="fa fa-trash"></i> <span class="small_txt">Hylkää</span></button>
                </div>
            </form>
        </div>


        <!-- Käyttäjän tiedot -->
        <div class="hero_item">
            <h3>Käyttäjätiedot</h3>

            <p><i class="fa fa-circle-info"></i> Uusi sähköpostiosoite tulee vahvistaa sähköpostiin lähetetystä linkistä.</p>

            <ul>
                <li class="info_container">
                    <p><strong>Käyttäjätunnus:</strong></p> 
                    <?php echo "<a href='kayttaja.php?u=". htmlspecialchars($user['user_id']) ."' id='username'>". htmlspecialchars($user['username']) ."</a>"; ?>
                </li>

                <li class="info_container">
                    <p><strong>Etunimi:</strong></p> 
                    <p id="first_name"><?php echo htmlspecialchars($user['first_name']); ?></p>
                </li>

                <li class="info_container">
                    <p><strong>Sukunimi:</strong></p> 
                    <p id="last_name"><?php echo htmlspecialchars($user['last_name']); ?></p>
                </li>

                <li class="info_container">
                    <p><strong>Puhelinnumero:</strong></p> 
                    <p id="phone_number">0<?php echo htmlspecialchars($user['phone']); ?></p>
                </li>

                <li class="info_container">
                    <p><strong>Sähköposti:</strong></p> 
                    <p id="email_address"><?php echo htmlspecialchars($user['email']); ?></p>
                </li>
            </ul>

            <div class="buttons">
                <button id='edit_profile' class='btn small_btn'><i class='fa-regular fa-pen-to-square'></i> Muokkaa tietoja</button>
            </div>
            
            <p id="infoSuccess" class='green'></p>
        </div>  


        <!-- Salasanan vaihto -->
        <div class="hero_item">
            <h3>Vaihda Salasanasi</h3>

            <p>Vaihtaaksesi salasanaasi, tulee sinun lähettää nollauspyyntö sähköpostiisi. Sähköpostiosoitteen tulee olla liitetty Kuvapankki-tiliisi. <strong class="red">*</strong> -pakollinen kenttä.</p>

            <form id="pwd_form" method="post">
                <label for="email">Sähköposti <strong class="red">*</strong></label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <button class="small_btn" type="submit"><i class="fa-regular fa-envelope"></i> Lähetä</button>
            </form>
            
            <p id="pwd_success" class="success_msg" class='green'></p>
            <p id="pwd_error" class="error_msg" class='red'></p>

            <p>Jos sinulla on ongelmia salasanan vaihdon kanssa, <a href="ota_yhteytta.php?aihe=salasana">ota yhteyttä</a> ylläpitoomme.</p>
        </div>
    </div>


</main>

<script>
    var user = <?php echo json_encode($user); ?>;
</script>

<?php
    include 'footer.php';
?>