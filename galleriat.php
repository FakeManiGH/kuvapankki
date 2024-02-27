<?php
    $title = 'Omat Galleriat';
    $css = 'css/galleriat.css';
    include 'header.php';
    checkSession();
    if (!is_logged_in()) {
        header('Location: kirjaudu.php?kirjautumen=vaaditaan');
        die();
    }

    $gallery = [
        'head_image' => 'https://placehold.co/150x150'
    ];
    function gallery_background($gallery) {
        if ($gallery['head_image']) {
            return $gallery['head_image'];
        } else {
            return 'https://placehold.co/150x150';
        } 
    }
?>

<style>
    .gallery {
    background: url(<?php echo gallery_background($gallery); ?>);
    background-size: cover;
    background-position: center;
    }
</style>

<main>

    <p>Täältä näet omat galleriasi. Voit lisätä kuvia olemassa oleviin gallerioihin tai luoda uusia gallerioita.</p>

    <h2>Yksityiset Galleriat</h2>

    <div class="galleria_container">

        <a class="gallery" href="#"></a>

        <a class="new_gallery" href="uusi_galleria.php">+</a>

    </div>


    <h2>Jaetut Galleriat</h2>

    <div class="galleria_container">

        <a class="gallery" href="#"></a>

        <a class="new_gallery" href="uusi_galleria.php">+</a>

    </div>

</main>

<?php
    include 'footer.php';
?>