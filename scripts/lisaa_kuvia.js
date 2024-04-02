
const dropArea = document.getElementById('droparea');
let fileInput = document.getElementById('images');
const form = document.getElementById('image_form');
let error = document.querySelector('.error_message');

// Add event listeners for the dragover, dragenter, dragleave events
['dragenter', 'dragover', 'dragleave'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

// Prevent the default behavior for the dragover and dragenter events
function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Add event listeners for the dragenter and dragover events to add the 'highlight' class to the drop area
['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

// Add event listeners for the dragleave event to remove the 'highlight' class from the drop area
['dragleave'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

// Function to add the 'highlight' class to the drop area
function highlight() {
    dropArea.classList.add('highlight');
}

// Function to remove the 'highlight' class from the drop area
function unhighlight() {
    dropArea.classList.remove('highlight');
}

// Add an additional event listener for 'mouseleave' to remove the 'highlight' class
dropArea.addEventListener('mouseleave', unhighlight, false);

// Add event listener for the 'drop' event
dropArea.addEventListener('drop', handleDrop, false);

// Add event listener for the 'change' event on the file input
images.addEventListener('change', function(e) {
    handleFiles(e.target.files);
    e.target.value = '';
});

// Function to handle the 'drop' event
function handleDrop(e) {
    preventDefaults(e);
    let dt = e.dataTransfer
    let files = dt.files

    handleFiles(files)
}


// Function to handle the files
function handleFiles(files) {
    resetImageForm();
    formStart();
    
    // Create a new DataTransfer object
    let dataTransfer = new DataTransfer();

    for (let i = 0; i < files.length; i++) {
        let file = files[i];

        if (checkFileType(file) && checkFileSize(file)) {
            if (i < 10) {
                // Add the file to the DataTransfer object
                dataTransfer.items.add(file);

                addFieldsForImage(file, i);
            } else {
                tooManyFiles();
                break;
            }
        } else {
            displayError(file);
        }
    }

    // Get the file input
    let fileInput = document.getElementById('fileInput');

    // Assign the files of the DataTransfer object to the files of the file input
    fileInput.files = dataTransfer.files;

    if (form.classList.contains('has_images')) {
        formEnd();
    } else {
        return;
    }
}

// Function to add the file input fields for the images
function addFieldsForImage(file, index) {
    let selectedImages = document.querySelector('.selected_images');

    let fieldWrapper = document.createElement('span');
    fieldWrapper.classList.add('field_wrapper');
    selectedImages.appendChild(fieldWrapper);

    let preview = document.createElement('img');
    preview.src = URL.createObjectURL(file);
    preview.classList.add('preview');
    fieldWrapper.appendChild(preview);

    let titleLabel = document.createElement('label');
    titleLabel.textContent = 'Otsikko';
    fieldWrapper.appendChild(titleLabel);

    let titleInput = document.createElement('input');
    titleInput.type = 'text';
    titleInput.name = 'title[]';
    titleInput.placeholder = 'Anna otsikko kuvalle ' + (index + 1);
    fieldWrapper.appendChild(titleInput);

    let descriptionLabel = document.createElement('label');
    descriptionLabel.textContent = 'Kuvateksti';
    fieldWrapper.appendChild(descriptionLabel);

    let descriptionInput = document.createElement('input');
    descriptionInput.type = 'text';
    descriptionInput.name = 'description[]';
    descriptionInput.placeholder = 'Anna kuvaus kuvalle ' + (index + 1);
    fieldWrapper.appendChild(descriptionInput);
}


// Function to check if the file is right type
function checkFileType(file) {
    if (file.type === 'image/jpg' || file.type === 'image/jpeg' || file.type === 'image/png') {
        return true;
    } else {
        return false;
    }
}

// Function to check if the file is right size
function checkFileSize(file) {
    if (file.size < 5000000) {
        return true;
    } else {
        return false;
    }
}

// Function to add the form start
function formStart() {
    let form = document.getElementById('image_form');
    form.classList.add('has_images');

    let selectedImages = document.createElement('span');
    selectedImages.classList.add('selected_images');
    form.appendChild(selectedImages);
}

// Function to add the gallery select and submit button to the form
function formEnd() {
    let selectedImages = document.querySelector('.selected_images');
    let form = document.getElementById('image_form');

    let validationError = document.createElement('p');
    validationError.classList.add('error_msg');
    validationError.textContent = '';
    selectedImages.appendChild(validationError);

    let buttonWrapper = document.createElement('div');
    buttonWrapper.classList.add('buttons');
    form.appendChild(buttonWrapper);

    let submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.textContent = 'Lisää kuvat';
    buttonWrapper.appendChild(submitButton);

    let cancelbutton = document.createElement('button');
    cancelbutton.type = 'button';
    cancelbutton.textContent = 'Tyhjennä';
    cancelbutton.addEventListener('click', function() {
        resetImageForm();
    });
    buttonWrapper.appendChild(cancelbutton);
}

// Function to display an error message for the invalid files
function displayError(file) {
    let error = document.createElement('p');
    error.textContent = 'Tiedoston ' + file.name + ' tyyppi ei ole sallittu tai se on liian suuri.';
    error.classList.add('red', 'error_message');
    dropArea.appendChild(error);

    setTimeout(function() {
        error.remove();
    }, 7500);
}

// Function to display an error message if too many files are added
function tooManyFiles() {
    let error = document.createElement('p');
    error.textContent = 'Liian monta kuvaa, maksimimäärä on 10 kuvaa.';
    error.classList.add('red', 'error_message');
    dropArea.appendChild(error);

    setTimeout(function() {
        error.remove();
    }, 7500);
}

// Function to validate the form (gallery select)
form.addEventListener('submit', function(e) {
    let gallerySelect = document.getElementById('selected_gallery');
    let galleryOption = gallerySelect.options[gallerySelect.selectedIndex];
    let galleryError = document.querySelector('.error_msg');

    if (galleryOption.value === '0' || galleryOption.value === 'Valitse galleria') {
        galleryError.textContent = 'Valitse kuville galleria.';
        e.preventDefault();
    }
});

// function to reset the image form
function resetImageForm() {
    form.classList.remove('has_images');
    let selectedImages = document.querySelector('.selected_images');
    let buttonWrapper = document.querySelector('.buttons');
    if (selectedImages === null) {
        return;
    }
    selectedImages.innerHTML = '';
    buttonWrapper.innerHTML = '';
    selectedImages.remove();
    buttonWrapper.remove();
}

