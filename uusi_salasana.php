<?php
    $title = 'Uusi Salasana';
    // $css = '';
    // $js = '';
    include 'header.php';
    checkSession();
?>

<main>

    <?php
        if (isset($_SESSION['reset_success'])) {
            echo "<h4 class='green'>".$_SESSION['reset_success']."</h4><br>";
            echo "<p>Voit nyt kirjautua sisään: <a href='kirjaudu.php'>Kirjaudu Sisään</a>";
            unset($_SESSION['reset_success']); 

        } else if (isset($_GET['pwd_token'])) {

            $pwd_token = $_GET['pwd_token'];

            include 'includes/pwd_token_check.php';

            if (isset($token_error)) {
                echo "<h4 class='red'>".$token_error."</h4>";
                unset($token_error);

            } else {?>

            <h1>Aseta Uusi Salasana</h1>
                
            <div class="form_container">
                <form action="includes/pwd_new.php" class="page_form" method="POST">
                    <label for="pwd_token"></label>
                    <input type="hidden" name="pwd_token" value="<?=$pwd_token?>">

                    <span class="inline">
                        <label for="pwd">Uusi Salasana</label>
                        <input type="password" name="pwd" placeholder="Anna uusi salasana" autofocus>
                    </span>

                    <span class="inline">
                        <label for="pwd2">Vahvista Salasana</label>
                        <input type="password" name="pwd2" placeholder="Vahvista uusi salasana">
                    </span>

                    <p class='error_msg'>
                        <?php if (isset($_SESSION['reset_error'])) {
                            echo $_SESSION['reset_error'];
                            unset($_SESSION['reset_error']);
                            } 
                        ?>
                    </p>

                    <span class="buttons">
                        <button type="submit">Tallenna Salasana</button>
                    </span>
                </form>
            </div>

            <p>Muistitko sittenkin salasanasi? <a href="kirjaudu.php">Kirjaudu Sisään!</a></p>

        <?php }
        
        } else {

            header("Location: index.php?pääsy=kieletty");
            exit();

        } ?>
        
</main>


<?php
    include 'footer.php';
?>