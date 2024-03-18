let galleryVisibility = document.getElementById('visibility');
let private = document.getElementById('private');
let public = document.getElementById('public');
let galleryMembers = document.getElementById('select_users_area');
let visibilityInfo = document.getElementById('visibility_info');
let visibilityPopup = document.getElementById('visibility_popup');
let closePopup = document.getElementById('close_popup');
const form = document.getElementById('create_gallery');


galleryVisibility.addEventListener('change', function() {
    if (galleryVisibility.value === '1') {
        galleryMembers.style.display = 'none';
    } else {
        galleryMembers.style.display = 'flex';
    }
});

// Add user to gallery
document.getElementById('add_user').addEventListener('click', function() {
    var selectUsers = document.getElementById('select_users');
    var selectedUsers = document.getElementById('selected_users');
    var selectedOption = selectUsers.options[selectUsers.selectedIndex];

    if (selectedOption.value !== '' && selectedOption.value !== '0') {
        selectUsers.remove(selectUsers.selectedIndex);
        selectedUsers.appendChild(selectedOption);
        
        let removeButton = document.createElement('button');
        removeButton.classList.add('remove_user');
        removeButton.innerHTML = '<i class="fa fa-trash"></i>';
        selectedOption.appendChild(removeButton);
        selectedOption.addEventListener('click', function() {
            selectedUsers.removeChild(selectedOption);
            selectUsers.appendChild(selectedOption);
            removeButton.remove();
        });

    } else {
        selectError();
    }
});


// Select error
function selectError() {
    let selectingErr = document.createElement('p');
    selectingErr.id = 'select_error';
    selectingErr.classList.add('red');
    selectingErr.style.alignSelf = 'flex-end';
    selectingErr.textContent = 'Valitse käyttäjä';
    galleryMembers.appendChild(selectingErr);
    setTimeout(function() {
        selectingErr.remove();
    }
    , 1500);
}

// visibility info popup
visibilityInfo.addEventListener('mouseover', function() {
    visibilityPopup.style.display = 'flex';
});

visibilityInfo.addEventListener('mouseout', function() {
    visibilityPopup.style.display = 'none';
});

function showVisibilityPopup() {
    if (visibilityPopup.style.display === 'flex') {
        visibilityPopup.style.display = 'none';
    } else {
        visibilityPopup.style.display = 'flex';
        visibilityPopup.style.left = '0';
        visibilityPopup.style.top = '50px';
    }
}

closePopup.addEventListener('click', function() {
    visibilityPopup.style.display = 'none';
});


// Default image for preview
var defaultImg = document.getElementById('cover_img_preview').src;

// Selected image preview
document.getElementById('cover_img').addEventListener('change', function(e) {
    var file = e.target.files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        document.getElementById('cover_img_preview').src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
        document.getElementById('clear_preview_img').style.display = 'block';
    } else {
        document.getElementById('cover_img_preview').src = defaultImg;
    }
});

// Selected image clear
document.getElementById('clear_preview_img').addEventListener('click', function() {
    if(document.getElementById('cover_img').value != '') {
        document.getElementById('cover_img').value = '';
        document.getElementById('cover_img_preview').src = defaultImg;
        document.getElementById('clear_preview_img').style.display = 'none';
    }
});

// Clear form
document.getElementById('reset').addEventListener('click', function() {
    document.getElementById('cover_img').value = '';
    document.getElementById('cover_img_preview').src = defaultImg;
    document.getElementById('clear_preview_img').style.display = 'none';
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('tags').value = '';
    document.getElementById('visibility').value = '1';
    document.getElementById('select_users').innerHTML = '';
    document.getElementById('selected_users').innerHTML = '';
    document.getElementById('select_users').innerHTML = '';
    document.getElementById('select_users').innerHTML = '';
    document.getElementById('tags_counter').textContent = '0 / 15kpl';
    document.getElementById('desc_counter').style.color = 'black';
    document.getElementById('desc_counter').textContent = '0 / 400';
    document.getElementById('desc_counter').style.color = 'black';
    document.getElementById('name_counter').textContent = '0 / 75';
    document.getElementById('name_counter').style.color = 'black';
    document.getElementById('basic_info_err').textContent = '';
    document.getElementById('more_info_err').textContent = '';
});

// Name length counter
document.getElementById('name').addEventListener('input', function() {
    let nameLength = document.getElementById('name').value.length;
    document.getElementById('name_counter').textContent = nameLength + ' / 75';

    if (nameLength > 75) {
        document.getElementById('name_counter').style.color = 'red';
    } else {
        document.getElementById('name_counter').style.color = 'black';
    }
});

// Description length counter
document.getElementById('description').addEventListener('input', function() {
    let descLength = document.getElementById('description').value.length;
    document.getElementById('desc_counter').textContent = descLength + ' / 400';

    if (descLength > 400) {
        document.getElementById('desc_counter').style.color = 'red';
    } else {
        document.getElementById('desc_counter').style.color = 'black';
    }
});

// Tag counter
document.getElementById('tags').addEventListener('input', function() {
    let tagInput = document.getElementById('tags').value.trim().replace(/\s+/g, ' ');
    let tags = tagInput.split(' ');
    let tagsLength = tags.length;
    document.getElementById('tags_counter').textContent = tagsLength + ' / 15kpl';

    if (!tagInput === '') {
        tagsLength = 1;
    }
    if (tagInput === '') {
        document.getElementById('tags_counter').textContent = '0 / 15kpl';
    }

    if (tagsLength > 15) {
        document.getElementById('tags_counter').style.color = 'red';
    } else {
        document.getElementById('tags_counter').style.color = 'black';
    }
});


// Form Error Check
form.addEventListener('submit', function(e) {
    let galleryName = document.getElementById('name');
    let galleryDesc = document.getElementById('description');
    let galleryTags = document.getElementById('tags');
    let basicInfoError = document.getElementById('basic_info_err');
    let moreInfoError = document.getElementById('more_info_err');

    if (galleryName.value === '' || galleryDesc.value === '') {
        basicInfoError.textContent = 'Täytä kaikki pakolliset kentät.';
        e.preventDefault();
    }

    if (galleryName.value.length > 75) {
        currentNameLength = galleryName.value.length;
        basicInfoError.textContent = 'Gallerian nimi on liian pitkä. ' + currentNameLength +'/75 merkkiä.';
        e.preventDefault();
    }

    if (galleryDesc.value.length > 400) {
        currentDescLength = galleryDesc.value.length;
        basicInfoError.textContent = 'Kuvaus on liian pitkä. ' + currentDescLength +'/400 merkkiä.';
        e.preventDefault();
    }

    if (galleryTags.value.length > 100) {
        currentTagsLength = galleryTags.value.length;
        moreInfoError.textContent = 'Avainsanat ylittävätä merkkirajan. ' + currentTagsLength +'/100 merkkiä.';
        e.preventDefault();
    }
});


