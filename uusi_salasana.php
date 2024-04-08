<?php
    $title = 'Uusi Salasana';
    // $css = '';
    // $js = '';
    include 'header.php';
    checkSession();
?>

<!-- This page is for creating a new password after the user has received the reset link. -->

<main>

    <?php
        // Success message after the password has been reset.
        if (isset($_SESSION['reset_success'])) {
            echo "<h4 class='green'>".$_SESSION['reset_success']."</h4><br>";
            echo "<p>Voit nyt kirjautua sisään: <a href='kirjaudu.php'>Kirjaudu Sisään</a>";
            unset($_SESSION['reset_success']); 
        
        // Starts from here if user arrived from reset link (email).
        } else if (isset($_GET['pwd_token'])) {

            $pwd_token = $_GET['pwd_token'];

            include 'includes/pwd_token_check.php';

            // If the token is invalid, show an error message.
            if (isset($token_error)) {
                echo "<h4 class='red'>". $token_error ."</h4>";
                unset($token_error);

            } else {?>

                <!-- Form for creating a new password. -->
                <div class="hero_item">
                    <h3>Anna Uusi Salasana</h3>

                    <p>Salasanan tulee sisältää vähintään 12 merkkiä. Suosittelemme lisäämään vähintään yhden numeron (0-9) ja erikoismerkin kuten (?!.-_).</p>

                    <form action="includes/pwd_new.php" class="page_form" method="POST">
                        <label for="pwd_token"></label>
                        <input type="hidden" name="pwd_token" value="<?=$pwd_token?>">

                        <label for="pwd">Uusi salasana <strong class="red">*</strong></label>
                        <input type="password" name="pwd" placeholder="Anna uusi salasana" autofocus>

                        <label for="pwd2">Vahvista uusi salasana <strong class="red">*</strong></label>
                        <input type="password" name="pwd2" placeholder="Vahvista uusi salasana">

                        <?php
                            if (isset($_SESSION['reset_error'])) {
                                echo "<p class='red'>".$_SESSION['reset_error']."</p>";
                                unset($_SESSION['reset_error']);
                            }
                        ?>

                        <span class="buttons">
                            <button type="submit"><i class="fa fa-floppy-disk"></i> Tallenna Salasana</button>
                        </span>
                    </form>

                    <p>Muistitko sittenkin salasanasi? <a href="kirjaudu.php">Kirjaudu Sisään!</a></p>
                </div>
        <?php }
        
        } else {
            $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
            header("Location: ../404.php?virhe");
            die();

        } ?>
        
</main>


<?php
    include 'footer.php';
?>