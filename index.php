<?php
    $title = 'Tervetuloa Kuvapankkiin';
    $css = 'css/index.css';
    include 'header.php';
    checkSession();
?>

<main>

    <h1><span class="bold_header">TERVETULOA KUVAPANKKIIN</span></h1>
    
    <p>Kuvapankki on kuvien jakamiseen ja tallentamiseen tarkoitettu palvelu. 
    Kuvapankin avulla voit jakaa kuvia ystäviesi kanssa ja luoda kuvaryhmiä, joihin voit kutsua ystäviäsi mukaan. 
    Kuvapankki on ilmainen ja helppokäyttöinen.</p>
    <div class="circles">
        <div class="circle">
            <h3>Rekisteröidy Tänään!</h3>
            <ul class="register_list">
                <li>Luo kuvagallerioita.</li>
                <li>Tallenna ja lataa kuvia.</li>
                <li>Liity ja luo kuvaryhmiä.</li>
            </ul>
            <button class="yellow_btn" onclick="window.location.href='rekisteroidy.php'"><i class="fa fa-user-plus"></i> Rekisteröidy</button>
        </div>

        <div class="circle">
            <h3>Vieraile Tietopankissa.</h3>
            <p class="small_txt">Lue lisää palvelusta ja sen toiminnasta. Etsi ohjeita palvelun käyttämiseen ja apua ongelmiin.</p>
            <button class="yellow_btn" onclick="window.location.href='tietoa.php'"><i class="fa fa-info-circle"></i>Tietopankki</button>
        </div>

        <div class="circle">
            <h3>Onko Kysyttävää?</h3>
            <p class="small_txt">Ota yhteyttä asiakaspalveluumme, niin autamme sinua ja vastaamme kysymyksiin mielellämme.</p>
            <button class="yellow_btn" onclick="window.location.href='ota_yhteytta.php'"><i class="fa fa-envelope"></i> Ota Yhteyttä</button>
        </div>
    </div>

</main>

<?php
    include 'footer.php';
?>
