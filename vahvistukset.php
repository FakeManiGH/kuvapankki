<?php
    $css = 'css/vahvistukset.css';
    $title = 'Rekisteröitymisen vahvistus';
    include 'header.php';
    checkSession();
?>

<main>
    <!-- Tämä sivu on tarkoitettu rekisteröitymisen jälkeen tuleville vahvistuksille. -->

    <div. class="container">

    <?php

    // Tarkistetaan onko rekisteröityminen onnistunut
    if (isset($_SESSION['register_status'])) {
        echo "<h4 class='green'>".$_SESSION['register_status']."</h4>";
        echo "<p class='red'>Vahvista sähköpostiosoitteesi ennen kirjautumista. Linkki on lähetetty antamaasi sähköpostiosoitteeseen.</p>";
        unset($_SESSION['register_status']);
    

    // Tarkistetaan onko sähköpostin vahvistus onnistunut
    } else if (isset($_GET['email_token'])) { 
        $token = $_GET['email_token'];

        try {
            include "includes/connections.php";
            include "includes/register_model.php";

            $errors = [];

            $result = get_email_token($pdo, $token);

            if (empty($result['email_token'])) {
                $errors['verify_status'] = "Vahvistus epäonnistui. Käytithän oikeaa linkkiä?";
            }
            if (!$token === $result['email_token']) {
                $errors['verify_status'] = "Väärä vahvistuskoodi. Käytithän oikeaa linkkiä?";
            }
            if ($result['verified'] == 1) {
                $errors['verify_status'] = "Sähköpostiosoite on jo vahvistettu. Voit kirjautua sisään.";
            }

            // Tarkistetaan onnistuiko vahvistus
            if ($errors) {
                $_SESSION['errors'] = $errors;
                header("Location: vahvistukset.php?vahvistus=epäonnistui");
                die();
            }

            // Asetetaan käyttäjän sähköposti vahvistetuksi
            verify_email($pdo, $token);

            // Nollataan tietokannan yhteys
            $pdo = null;
            $stmt = null;

            // Asetetaan vahvistus onnistuneeksi
            $_SESSION['verify_status'] = "Sähköpostiosoite vahvistettu! Voit nyt kirjautua sisään.";
            echo "<h4 class='green'>".$_SESSION['verify_status']."</h4>";
            unset($_SESSION['verify_status']);


        } catch (PDOException $e) {
            die("Vahvistus epäonnistui: " . $e->getMessage());
        }

    // Tarkistetaan onko vahvistus epäonnistunut
    } else if (isset($_SESSION['errors'])) {

        echo "<h4 class='red'>".$_SESSION['errors']['verify_status']."</h4>";
        unset($_SESSION['errors']);

    } else {

        header("Location: index.php?pääsy=kielletty");
        exit();
        
    }
    ?>


    <!-- Tämä sivu on tarkoitettu sähköpostin vahvistuslinkin vastaanottoon. -->
    
    
    </div>
</main>

<?php
    include 'footer.php';
?>

