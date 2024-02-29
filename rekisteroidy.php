<?php
$title = 'Rekisteröidy';
$css = 'css/rekisteroidy.css';
$js = 'scripts/register.js';
include 'header.php';
checkSession();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "includes/register_validation.php";

    // tarkistetaan onko virheitä
    $errors = [$usernameErr, $first_nameErr, $last_nameErr, $phoneErr, $emailErr, $pwdErr, $pwd2Err];

    if (empty(implode($errors))) {
        
        // tallennetaan lomakkeen tiedot sessioon ja siirrytään rekisteröintiin.
        $_SESSION['form_data'] = $_POST;
        include "includes/register.php";
    } 
}

?>

<main>

    <h1>Rekisteröidy</h1>

    <p>Rekisteröidy käyttäjäksi täyttämällä alla oleva lomake.</p>

    <p><span class="red"> <strong>*</strong> pakollinen kenttä.</span></p>

    <form id="register_form"  class="page_form" action="rekisteroidy.php" method="post">
        <span class="inline">
            <label for="username">Käyttäjätunnus <span class="red">*</span></label>
            <input id="username" type="text" name="username" placeholder="Käyttäjätunnus" value="<?php if (isset($username)) {echo $username;} ?>" autofocus>
        </span>
        <p class='error_msg'><?php if (isset($usernameErr)) {echo $usernameErr;} ?></p>

        <span class="inline">
            <label for="first_name">Etunimi <span class="red">*</span></label>
            <input id="first_name" type="text" name="first_name" placeholder="Etunimi" value="<?php if (isset($first_name)) {echo $first_name;} ?>">
        </span>
        <p class='error_msg'><?php if (isset($first_nameErr)) {echo $first_nameErr;} ?></p>

        <span class="inline">
            <label for="last_name">Sukunimi <span class="red">*</span></label>
            <input id="last_name" type="text" name="last_name" placeholder="Sukunimi" value="<?php if (isset($last_name)) {echo $last_name;} ?>">
        </span>
        <p class='error_msg'><?php if (isset($last_nameErr)) {echo $last_nameErr;} ?></p>

        <span class="inline">
            <label for="phone">Puhelinnumero <span class="red">*</span></label>
            <input id="phone" type="number" name="phone" placeholder="Matkapuhelinnumero" value="<?php if (isset($phone)) {echo $phone;} ?>">
        </span>
        <p class='error_msg'><?php if (isset($phoneErr)) {echo $phoneErr;} ?></p>

        <span class="inline">
            <label for="email">Sähköpostiosoite <span class="red">*</span></label>
            <input id="email" type="text" name="email" placeholder="Sähköpostiosoite" value="<?php if (isset($email)) {echo $email;} ?>">
        </span>
        <p class='error_msg'><?php if (isset($emailErr)) {echo $emailErr;} ?></p>

        <span class="inline">
            <label for="pwd">Salasana <span class="red">*</span></label>
            <input id="pwd" type="password" name="pwd" placeholder="Salasana" value="<?php if (isset($pwd)) {echo $pwd;} ?>">
        </span>
        <p class='error_msg'><?php if (isset($pwdErr)) {echo $pwdErr;} ?></p>

        <span class="inline">
            <label for="pwd2">Vahvista salasana <span class="red">*</span></label>
            <input id="pwd2" type="password" name="pwd2" placeholder="Vahvista Salasana">
        </span>
        <p class='error_msg'><?php if (isset($pwd2Err)) {echo $pwd2Err;} ?></p>

        <span class="buttons">
            <button type="submit">Rekisteröidy</button>
            <button id="reset" type="reset">Tyhjennä</button>
        </span>
    </form>

    <p>Onko sinulla jo tunnukset? <a href="kirjaudu.php">Kirjaudu sisään!</a></p>

</main>


<?php
    include 'footer.php';
?>