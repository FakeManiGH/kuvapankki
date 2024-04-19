
// Gallerian tietojen muokkaaminen
const editGalleryBtn = document.getElementById('edit_details');

if (editGalleryBtn) {
    editGalleryBtn.addEventListener('click', editGalleryDetails);
}

function editGalleryDetails() {
    let id = editGalleryBtn.getAttribute('data-id');
    let name = editGalleryBtn.getAttribute('data-name');
    let desc = editGalleryBtn.getAttribute('data-desc');
    let visibility = editGalleryBtn.getAttribute('data-visibility');

    let editPopup = document.createElement('div');
    editPopup.classList.add('popup');
    document.body.style.overflow = 'hidden';
    editPopup.innerHTML = `
        <h3>Muokkaa Gallerian tietoja</h3>
        <button class="popup_close func_btn red"><i class="fa fa-circle-xmark"></i></button>
        <form action="includes/gallery_update.php" class="page_form" method="POST">
            <input type="hidden" name="gallery_id" value="${id}">
            <label for="name">Gallerian Nimi</label>
            <input type="text" id="name" name="name" value="${name}">
            <label for="description">Gallerian Kuvaus</label>
            <textarea id="description" name="description">${desc}</textarea>
            <label for="visibility">Näkyvyys</label>
            <select name="visibility" id="edit_visibility">
                <option value="1">Yksityinen</option>
                <option value="2">Kaverit</option>
                <option value="3">Julkinen</option>
            </select>
            <button type="submit" class="edit_btn">Tallenna</button>
        </form>
    `;

    // activate blurrwd background
    document.querySelector('.menu_overlay').classList.add('active');

    // Add the popup to the body of the page
    document.body.appendChild(editPopup);

    // Set the selected option in the visibility select element
    let visibilitySelect = document.getElementById('edit_visibility');
    let options = visibilitySelect.options;
    for (let i = 0; i < options.length; i++) {
        if (options[i].value === visibility) {
            options[i].selected = true;
        }
    }

    // Close the popup
    document.querySelector('.popup_close').addEventListener('click', () => {
        editPopup.remove();
        document.querySelector('.menu_overlay').classList.remove('active');
        document.body.style.overflow = 'auto';
    });

    // Close the popup when clicking outside of it
    document.querySelector('.menu_overlay').addEventListener('click', () => {
        editPopup.remove();
        document.querySelector('.menu_overlay').classList.remove('active');
        document.body.style.overflow = 'auto';
    });
}


// Gallerian kuvien suodatus ja järjestäminen
const filterInput = document.getElementById('filter_images');
const images = document.querySelectorAll('.gallery_item');
const gallery = document.querySelector('.gallery_container');
const description = document.querySelectorAll('.desc_txt');

if (filterInput) {
    filterInput.addEventListener('input', filterImages);
}

function filterImages() {
    let filterValue = filterInput.value.toUpperCase();

    if (filterValue.length <= 1) {
        images.forEach(image => {
            image.style.display = 'flex';
        });
        return;
    }

    images.forEach(image => {
        let imageTitle = image.dataset.name ? image.dataset.name.toUpperCase() : '';
        let author = image.dataset.author ? image.dataset.author.toUpperCase() : '';

        if (imageTitle.includes(filterValue) || author.includes(filterValue)) {
            image.style.display = 'flex';
        } else {
            image.style.display = 'none';
        }
    });
}


// Järjestetään kuvat aakkosjärjestykseen tai päivämäärän mukaan
const sortImages = document.getElementById('sort_images');

if (sortImages) {
    sortImages.addEventListener('change', sortImageList);
}

function sortImageList() {
    let sortValue = sortImages.value;
    let imagesArray = Array.from(images);

    if (sortValue === 'az') {
        imagesArray.sort((a, b) => {
            let titleA = a.getAttribute('data-name').toUpperCase();
            let titleB = b.getAttribute('data-name').toUpperCase();

            if (titleA < titleB) {
                return -1;
            }

            if (titleA > titleB) {
                return 1;
            }

            return 0;
        });
    }
    
    else if (sortValue === 'za') {
        imagesArray.sort((a, b) => {
            let titleA = a.getAttribute('data-name').toUpperCase();
            let titleB = b.getAttribute('data-name').toUpperCase();

            if (titleA > titleB) {
                return -1;
            }

            if (titleA < titleB) {
                return 1;
            }

            return 0;

        });
    }

    else if (sortValue === 'new') {
        imagesArray.sort((a, b) => {
            let dateA = new Date(a.getAttribute('data-date'));
            let dateB = new Date(b.getAttribute('data-date'));

            return dateB - dateA;
        });
    }

    else if (sortValue === 'old') {
        imagesArray.sort((a, b) => {
            let dateA = new Date(a.getAttribute('data-date'));
            let dateB = new Date(b.getAttribute('data-date'));

            return dateA - dateB;
        });
    }

    imagesArray.forEach(image => {
        gallery.appendChild(image);
    });
}




// GRID AND LIST VIEW

// Muutetaan gallerian näkymä listaksi tai ruudukoksi
const listButton = document.getElementById('list_view');
const gridButton = document.getElementById('grid_view');

if (listButton) {
    listButton.addEventListener('click', showListView);
}

function showListView() {
    gridButton.classList.remove('active');
    listButton.classList.add('active');
    gallery.classList.add('list_view');
    images.forEach(image => {
        image.classList.add('list_item');
    });
    description.forEach(desc => {
        desc.style.display = 'block';
    });
}

if (gridButton) {
    gridButton.addEventListener('click', showGridView);
}

function showGridView() {
    listButton.classList.remove('active');
    gridButton.classList.add('active');
    gallery.classList.remove('list_view');
    images.forEach(image => {
        image.classList.remove('list_item');
    });
    description.forEach(desc => {
        desc.style.display = 'none';
    });
}

// If screen is less than 400px wide, show grid view by default
if (window.innerWidth <= 400) {
    showGridView();
    gridButton.style.display = 'none';
    listButton.style.display = 'none';
}

window.addEventListener('resize', () => {
    if (window.innerWidth <= 400) {
        showGridView();
        gridButton.style.display = 'none';
        listButton.style.display = 'none';
    } else {
        gridButton.style.display = 'block';
        listButton.style.display = 'block';
    }
});





// EDIT IMAGES

// Edit image information
const editBtns = document.querySelectorAll('.edit_btn');

editBtns.forEach(button => {
    button.addEventListener('click', () => {
        let gallery = button.getAttribute('data-gallery');
        let id = button.getAttribute('data-id');
        let title = button.getAttribute('data-title');
        let desc = button.getAttribute('data-desc');
        let imageURL = button.getAttribute('data-url');

        let editPopup = document.createElement('div');
        editPopup.classList.add('popup');
        document.body.style.overflow = 'hidden';    
        editPopup.innerHTML = `
            <h3>Muokkaa Kuvan teitoja</h3>
            <img class="popup_image" src="../${imageURL}" alt="${title}">
            <button class="popup_close func_btn red"><i class="fa fa-circle-xmark"></i></button>
            <form id="img_edit_form" method="POST">
                <input type="hidden" name="gallery_id" value="${gallery}">
                <input type="hidden" name="image_id" value="${id}">
                <label for="title">Otsikko:</label>
                <input type="text" id="title" name="title" value="${title}">
                <label for="description">Kuvaus:</label>
                <textarea id="description" name="description">${desc}</textarea>
                <div class="buttons">
                    <button type="submit" title="Tallenna tiedot" class="edit_btn">Tallenna</button>
                    <button type="button" title="Poista kuva" class="func_btn delete_btn"><i class="fa fa-trash"></i></button>
                </div>
            </form>
        `;
        // activate blurrwd background
        document.querySelector('.menu_overlay').classList.add('active');

        // Add the popup to the body of the page
        document.body.appendChild(editPopup);

        // Close the popup
        document.querySelector('.popup_close').addEventListener('click', () => {
            editPopup.remove();
            document.querySelector('.menu_overlay').classList.remove('active');
            document.body.style.overflow = 'auto';
        });

        // Close the popup when clicking outside of it
        document.querySelector('.menu_overlay').addEventListener('click', () => {
            editPopup.remove();
            document.querySelector('.menu_overlay').classList.remove('active');
            document.body.style.overflow = 'auto';
        });
    });
}); 





// IMAGE DOWNLOAD

// Downloading images from gallery
const downloadBtns = document.querySelectorAll('.download_btn');

downloadBtns.forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        let imageURL = button.getAttribute('data-url');
        let imgName = button.getAttribute('data-name');
        if (!imgName) {
            imgName = 'image';
        }
        let fileName = imgName.replace(/\s/g, '');
        let link = document.createElement('a');
        link.href = '../' + imageURL;
        link.download = fileName + '.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});