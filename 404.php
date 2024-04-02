<?php
    $title = 'Sivua ei löydy';
    // $css = '';
    include 'header.php';
    checkSession();
?>

<main>
  
    <h1><span class="bold_header">404 - SIVUA EI LÖYDY</span></h1>
    
    <?php
        if (isset($_SESSION['404_error'])) {
            echo '<p class="red"><i class="fa fa-circle-exclamation"></i> ' . $_SESSION['404_error'] . '</p>';
            unset($_SESSION['404_error']);
        }
    ?>
    <button onclick="window.location.href='index.php'">Etusivulle</button>

</main>

<?php
    include 'footer.php';
?>
