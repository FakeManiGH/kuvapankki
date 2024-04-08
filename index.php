<?php
    $title = 'Tervetuloa Kuvapankkiin';
    $css = 'css/index.css';
    include 'header.php';
    checkSession();
?>

<main>

    <h1><span class="bold_header">Tervetuloa Kuvapankkiin!</span></h1>
    
    <p>Kuvapankki on kuvien jakamiseen ja tallentamiseen tarkoitettu palvelu. 
    Kuvapankin avulla voit jakaa kuvia ystäviesi kanssa ja luoda kuvaryhmiä, joihin voit kutsua ystäviäsi mukaan. 
    Kuvapankki on ilmainen ja helppokäyttöinen.</p>
    <div class="circles">
        <div class="circle">
            <h3>Reikisteröidy jo tänään.</h3>
            <ul>
                <li>Ilmainen palvelu.</li>
                <li>Luo kuvagallerioita.</li>
                <li>Tallenna ja jaa kuvia.</li>
                <li>Luo yhteisiä gallerioita kavereiden kanssa.</li>
                <li>Kommentoi ja keskustele.</li>
            </ul>
            <button class="btn" onclick="window.location.href='rekisteroidy.php'">Rekisteröidy</button>
        </div>

        <div class="circle">
            <img src="img/blue_tit.jpg" alt="Sinitiainen roikkuu pihlajanmarjassa">
            <h3>Kaikki irti kuvapankista.</h3>
            <p class="small_txt">Lue lisää palvelusta ja sen toiminnasta. Etsi ohjeita palvelun käyttämiseen ja apua ongelmiin.</p>
            <button class="btn" onclick="window.location.href='tietoa.php'">Tietopankki</button>
        </div>

        <div class="circle">
            <img src="img/squirrel.jpg" alt="Orava istumassa oksalla">
            <h3>Eikö onnistu? Kysy apua.</h3>
            <p class="small_txt">Ota yhteyttä asiakaspalveluumme, niin autamme sinua ja vastaamme kysymyksiin mielellämme.</p>
            <button class="btn" onclick="window.location.href='ota_yhteytta.php'">Ota Yhteyttä</button>
        </div>
    </div>

</main>

<?php
    include 'footer.php';
?>
