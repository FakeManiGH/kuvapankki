<?php
    $title = 'Profiili';
    $css = 'css/profiili.css';
    $js = 'scripts/profiili.js';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautumen=vaaditaan');
        die();
    }

    function securePhone($phone) {
        $start = substr($phone, 0, -4);
        $start = str_repeat("*", strlen($start));
        $end = substr($phone, -4);
        $securePhone = $start . $end;
        return $securePhone;
    }

    function secureEmail($email) {
        $start = substr($email, 0, 3);
        $middle = substr($email, 3, strpos($email, '@') - 3);
        $end = substr($email, strpos($email, '@'));
        $secureEmail = $start . str_repeat("*", strlen($middle)) . $end;
        return $secureEmail;
    }

    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $phone = "0".$_SESSION['phone'];
   /*  $email = secureEmail($_SESSION['email']);
    $phone = securePhone($_SESSION['phone']); */

?>

<main>

    <?php
        if (isset($_SESSION['profile_update_success'])) {
            echo "<h4 class='green'>".$_SESSION['profile_update_success']."</h4>";
            unset($_SESSION['profile_update_success']);
        }
    ?>

    <h1>Käyttäjäprofiili</h1>
    
    <div class="grid_container">

        <div class="important_list grid_item">
            <p><i class="fa-solid fa-circle-exclamation"></i> Tältä sivulta löydät kaikki omat tietosi. Voit muokata tietojasi ja vaihtaa salasanasi.</p>
            <p><i class="fa-solid fa-circle-exclamation"></i> Osaa käyttäjätiedoista ei näytetä yksityisyyden suojan säilymiseksi.</p>
            <p><i class="fa-solid fa-circle-exclamation"></i> Vaihtaaksesi etu- tai sukunimeä, <a href="ota_yhteytta.php">ota yhteyttä</a> ylläpitoon.</p>
            <p><i class="fa-solid fa-circle-exclamation"></i> Muista tallentaa tiedot, jotta muutokset astuvat voimaan.</p>
            <p><i class="fa-solid fa-circle-exclamation"></i> Salasana aina pakollinen tietojen päivittämiseksi, poislukien profiilikuva.</p>
        </div>


        <!-- Profiilikuva -->
        <form class="page_form grid_item" action="includes/upload_profile_img.php" method="post" enctype="multipart/form-data">
            <div class="image_container">
                <div class="user_image_wrapper">
                    <img id="profile_img" src="<?php if (isset($_SESSION['user_img'])) {echo $_SESSION['user_img'];} ?>" class="user_image" alt="Profiilikuva">
                </div>

                <div class="file_container">
                    <input type="file" name="userfile" id="user_img">
                    <a id="clear_file_select" href="javascript:void(0);"><i class="fa fa-trash"></i></a>
                    <button id="accept_file_select" type="submit"><i class="fa-solid fa-square-check"></i></button>
                </div>  
            </div>
            <p id="file_err">
                <?php if (isset($_SESSION['image_update_err'])) {
                    echo $_SESSION['image_update_err'];
                    unset($_SESSION['image_update_err']);
                }
                ?>
            </p>
        </form>
    </div>




    <h2>Omat tiedot</h2>

    <form id="user_info" class="page_form" action="includes/profile_update.php" method="post">

        <span class="inline">
            <label for="username">Käyttäjätunnus</label>
            <span class="inline_x2">
                <input type="text" id="username" name="username" value="<?php if (isset($_SESSION['username'])) {echo $_SESSION['username'];} ?>" placeholder="Käyttäjätunnus" readonly>
                <a id="edit_username" href="javascript:void(0);"><i class="fa-solid fa-pen-to-square"></i></a>
            </span>
        </span>

        <span class="inline">
            <label for="phone">Puhelinnumero</label>
            <span class="inline_x2">
                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="Matkapuhelinnumero" readonly>
                <a id="edit_phone" href="javascript:void(0);"><i class="fa-solid fa-pen-to-square"></i></a>
            </span>
        </span>

        <span class="inline">
            <label for="email">Sähköpostiosoite</label>
            <span class="inline_x2">
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="Sähköpostiosoite" readonly>
                <a id="edit_email" href="javascript:void(0);"><i class="fa-solid fa-pen-to-square"></i></a>
            </span>
        </span>

        <span class="inline">
            <label for="pwd">Salasana <span class="red">*</span></label>
            <span class="inline_x2">
                <input type="password" id="pwd" name="pwd" placeholder="Vanha Salasana" required autocomplete="off">
                <a id="show_pwd" href="javascript:void(0);"><i class="fa-regular fa-eye"></i></a>
            </span>
        </span> 

        <p class='error_msg' id="update_errors">
            <?php 
                if (isset($_SESSION['profile_update_err'])) {
                    echo $_SESSION['profile_update_err'];
                    unset($_SESSION['profile_update_err']);   
                }
            ?>
        </p>

        <span class="buttons">
            <button type="submit">Tallenna Tiedot</button>
            <button id="reset" type="reset">Peruuta</button>
        </span>

    </form>


  

    <h2>Salasanan Vaihto</h2>

    <form id="pwd_update_form" class="page_form" action="includes/pwd_update.php" method="post">

        <span class="inline">
            <label for="old_pwd">Nykyinen Salasana <span class="red">*</span></label>
            <span class="inline_x2">
                <input type="password" id="old_pwd" name="old_pwd" placeholder="Nykyinen Salasana" required autocomplete="off">
                <a id="show_old_pwd" href="javascript:void(0);"><i class="fa-regular fa-eye"></i></a>
            </span>
        </span>

        <span class="inline">
            <label for="new_pwd">Uusi Salasana <span class="red">*</span></label>
            <span class="inline_x2">
                <input type="password" id="new_pwd" name="new_pwd" placeholder="Uusi Salasana" required>
                <a id="show_new_pwd" href="javascript:void(0);"><i class="fa-regular fa-eye"></i></a>
            </span>
        </span>

        <p class='error_msg'>
            <?php 
                if (isset($_SESSION['pwd_update_err'])) {
                    echo $_SESSION['pwd_update_err'];
                    unset($_SESSION['pwd_update_err']);   
                }
            ?>
        </p>

        <span class="buttons">
            <button type="submit">Vaihda Salasana</button>
            <button id="reset" type="reset">Peruuta</button>
        </span>

    </form>
    



</main>

<?php
    include 'footer.php';
?>