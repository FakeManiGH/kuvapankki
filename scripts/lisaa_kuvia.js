
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
    form.innerHTML = '';
    formStart();

    for (let i = 0; i < files.length; i++) {
        let file = files[i];

        if (checkFileType(file) && checkFileSize(file)) {
            if (i < 10) {
                addFieldsForImage(file, i);
            } else {
                tooManyFiles();
                break;
            }
        } else {
            displayError(file);
        }
    }
    if (form.classList.contains('has_images')) {
        formEnd();
    } else {
        return;
    }
}

// Function to add the file input fields for the images
function addFieldsForImage(file, index) {
    let fieldSet = form.querySelector('fieldset');

    let fieldWrapper = document.createElement('div');
    fieldWrapper.classList.add('field_wrapper');
    fieldSet.appendChild(fieldWrapper);

    let preview = document.createElement('img');
    preview.src = URL.createObjectURL(file);
    preview.classList.add('preview');
    fieldWrapper.appendChild(preview);

    let fileInput = document.createElement('input');
    fileInput.type = 'hidden';
    fileInput.name = 'file[' + index + ']';
    fileInput.value = file.name;
    fieldWrapper.appendChild(fileInput);

    let titleInput = document.createElement('input');
    titleInput.type = 'text';
    titleInput.name = 'title[' + index + ']';
    titleInput.placeholder = 'Otsikko kuvalle ' + (index + 1);
    fieldWrapper.appendChild(titleInput);

    let descriptionInput = document.createElement('input');
    descriptionInput.type = 'text';
    descriptionInput.name = 'description[' + index + ']';
    descriptionInput.placeholder = 'Kuvateksti kuvalle ' + (index + 1);
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
    let fieldSet = document.createElement('fieldset');
    form.appendChild(fieldSet);

    let legend = document.createElement('legend');
    legend.textContent = 'Lisättävät kuvat';
    fieldSet.appendChild(legend);

    form.classList.add('has_images');
}

// Function to add the gallery select and submit button to the form
function formEnd() {
    let fieldSet = form.querySelector('fieldset');

    let galleryLabel = document.createElement('label');
    galleryLabel.for = 'gallery';
    galleryLabel.innerHTML = 'Valitse galleria <span class="red">*</span>';
    galleryLabel.classList.add('gallery_label');
    fieldSet.appendChild(galleryLabel);

    let gallerySelect = document.createElement('select');
    gallerySelect.name = 'gallery';
    gallerySelect.id = 'gallery';
    gallerySelect.hasAttribute('required');
    fieldSet.appendChild(gallerySelect);

    let optionDefault = document.createElement('option');
    optionDefault.value = 'Valitse galleria';
    optionDefault.textContent = 'Valitse galleria';
    gallerySelect.appendChild(optionDefault);

    fetch('galleries_view.php')
        .then(response => response.json())
        .then(data => {
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement('option');
                option.value = data[i].id; // replace 'id' with the actual property name
                option.textContent = data[i].name; // replace 'name' with the actual property name
                gallerySelect.appendChild(option);
            }
        })
        .catch(error => console.error('Error:', error));

    let validationError = document.createElement('p');
    validationError.classList.add('error_msg');
    validationError.textContent = '';
    fieldSet.appendChild(validationError);

    let buttonWrapper = document.createElement('div');
    buttonWrapper.classList.add('button_wrapper');
    fieldSet.appendChild(buttonWrapper);

    let submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.textContent = 'Lisää kuvat';
    buttonWrapper.appendChild(submitButton);

    let cancelbutton = document.createElement('button');
    cancelbutton.type = 'button';
    cancelbutton.textContent = 'Tyhjennä';
    cancelbutton.addEventListener('click', function() {
        form.classList.remove('has_images');
        form.innerHTML = '';
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
    let gallerySelect = document.getElementById('gallery');
    let galleryOption = gallerySelect.options[gallerySelect.selectedIndex];
    let galleryError = document.querySelector('.error_msg');

    if (galleryOption.value === '' || galleryOption.value === 'Valitse galleria') {
        galleryError.textContent = 'Valitse kuville galleria.';
        e.preventDefault();
    }
});
