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
?>

<main>

    <h1>Omat Tiedot</h1>

    <p>Tältä sivulta löydät kaikki omat tietosi. Voit muokata tietojasi ja vaihtaa salasanasi.</p>

    <p><span class="red"> * pakollinen kenttä.</span></p>

    <form class="page_form" action="profiili.php" method="post">

    <h2>Käyttäjätiedot</h2><br>
    
        <span class="inline">
            <label for="username">Käyttäjätunnus</label>
            <span class="inline_x2">
                <input type="text" id="username" name="username" value="<?php if (isset($_SESSION['username'])) {echo $_SESSION['username'];} ?>" placeholder="Käyttäjätunnus" readonly>
                <a id="edit_username" class="edit_link" href="javascript:void(0);"><i class="fa-solid fa-pen-to-square"></i></a>
            </span>
        </span> 

        <span class="inline">
            <label for="first_name">Etunimi</label>
            <span class="inline_x2">
                <input type="text" id="first_name" name="first_name" value="<?php if (isset($_SESSION['first_name'])) {echo $_SESSION['first_name'];} ?>" placeholder="Etunimi" readonly>
                <a id="edit_first_name" href="javascript:void(0);"><i class="fa-solid fa-pen-to-square"></i></a>
            </span>
        </span>

        <span class="inline">
            <label for="last_name">Sukunimi</label>
            <span class="inline_x2">
                <input type="text" id="last_name" name="last_name" value="<?php if (isset($_SESSION['last_name'])) {echo $_SESSION['last_name'];} ?>" placeholder="Sukunimi" readonly>
                <a id="edit_last_name" href="javascript:void(0);"><i class="fa-solid fa-pen-to-square"></i></a>
            </span>
        </span>

        <span class="inline">
            <label for="phone">Puhelinnumero</label>
            <span class="inline_x2">
                <input type="number" id="phone" name="phone" value="<?php if (isset($_SESSION['phone'])) {echo $_SESSION['phone'];} ?>" placeholder="Matkapuhelinnumero" readonly>
                <a id="edit_phone" href="javascript:void(0);"><i class="fa-solid fa-pen-to-square"></i></a>
            </span>
        </span>

        <span class="inline">
            <label for="email">Sähköpostiosoite</label>
            <span class="inline_x2">
                <input type="email" id="email" name="email" value="<?php if (isset($_SESSION['email'])) {echo $_SESSION['email'];} ?>" placeholder="Sähköpostiosoite" readonly>
                <a id="edit_email" href="javascript:void(0);"><i class="fa-solid fa-pen-to-square"></i></a>
            </span>
        </span>

    <br><h2>Salasana</h2><br>

        <span class="inline">
            <label for="pwd">Nykyinen Salasana <span class="red">*</span></label>
            <input type="password" id="pwd" name="pwd" placeholder="Vanha Salasana">
        </span>

        <span class="inline">
            <label for="new_pwd">Uusi salasana</label>
            <input type="password" id="new_pwd" name="new_pwd" placeholder="Uusi Salasana">
        </span>

        <span class="buttons">
            <button type="submit">Tallenna</button>
            <button id="reset" type="reset">Peruuta</button>
        </span>
    </form>



</main>

<?php
    include 'footer.php';
?>