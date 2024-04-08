let galleryVisibility = document.getElementById('visibility');
let private = document.getElementById('private');
let public = document.getElementById('public');
let galleryMembers = document.getElementById('select_users_area');
let galleryMembersList = document.getElementById('selected_users_container');
var selectedUserList = document.getElementById('selected_users_list');
const form = document.getElementById('create_gallery');


galleryVisibility.addEventListener('change', function() {
    if (galleryVisibility.value === '1') {
        galleryMembers.style.display = 'none';
        galleryVisibility.style.marginBottom = '10px';
    } else {
        galleryMembers.style.display = 'flex';
        galleryVisibility.style.marginBottom = '0';
    }
});

// Adding users to gallery
document.getElementById('add_user').addEventListener('click', function() {
    let selectUsers = document.getElementById('select_users');
    let selectedUsers = document.getElementById('selected_users');
    let selectedOption = selectUsers.options[selectUsers.selectedIndex];

    if (selectedOption.value !== '' && selectedOption.value !== '0') {
        selectUsers.remove(selectUsers.selectedIndex);
        selectedUsers.appendChild(selectedOption);

        let selectedUser = document.createElement('li');
        selectedUser.classList.add('selected_user');
        selectedUserList.appendChild(selectedUser);

        let selectedUserText = document.createElement('p');
        selectedUserText.textContent = selectedOption.text;
        selectedUser.appendChild(selectedUserText);
        
        let removeButton = document.createElement('button');
        removeButton.classList.add('func_btn', 'red');
        removeButton.innerHTML = '<i class="fa fa-trash"></i>';
        selectedUser.appendChild(removeButton);
        removeButton.addEventListener('click', function() {
            selectedUsers.removeChild(selectedOption);
            selectUsers.appendChild(selectedOption);
            removeButton.remove();
            selectedUser.remove();
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




// Selected users info popup
let usersInfo = document.getElementById('users_info');
let usersPopup = document.getElementById('users_popup');
let closeUsersPopup = document.getElementById('close_users_popup');

usersInfo.addEventListener('mouseover', showUsersPopup); 

usersInfo.addEventListener('mouseout', function() {
    usersPopup.style.display = 'none';
});

function showUsersPopup() {
    if (usersPopup.style.display === 'flex') {
        usersPopup.style.display = 'none';
    } else {
        usersPopup.style.display = 'flex';
        usersPopup.style.left = '0';
        usersPopup.style.top = '40px';
    }
}

closeUsersPopup.addEventListener('click', function() {
    usersPopup.style.display = 'none';
});



// Visibility info popup
let visibilityInfo = document.getElementById('visibility_info');
let visibilityPopup = document.getElementById('visibility_popup');
let closePopup = document.getElementById('close_visibility');

visibilityInfo.addEventListener('mouseover', showVisibilityPopup); 

visibilityInfo.addEventListener('mouseout', function() {
    visibilityPopup.style.display = 'none';
});

function showVisibilityPopup() {
    if (visibilityPopup.style.display === 'flex') {
        visibilityPopup.style.display = 'none';
    } else {
        visibilityPopup.style.display = 'flex';
        visibilityPopup.style.left = '0';
        visibilityPopup.style.top = '40px';
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
    document.getElementById('tags_counter').textContent = '0 / 15kpl';
    document.getElementById('desc_counter').style.color = 'black';
    document.getElementById('desc_counter').textContent = '0 / 400';
    document.getElementById('desc_counter').style.color = 'black';
    document.getElementById('name_counter').textContent = '0 / 75';
    document.getElementById('name_counter').style.color = 'black';
    document.getElementById('basic_info_err').textContent = '';
    document.getElementById('more_info_err').textContent = '';

    // moving users back to select
    let selectUsers = document.getElementById('select_users');
    let selectedUsers = document.getElementById('selected_users');
    Array.from(selectedUsers.options).forEach(option => {
        selectedUsers.remove(option.index);
        selectUsers.appendChild(option);
    });
    document.getElementById('select_users_area').style.display = 'none';
    document.getElementById('selected_users_list').innerHTML = '';
});

// Name length counter
document.getElementById('name').addEventListener('input', function() {
    let nameLength = document.getElementById('name').value.length;
    document.getElementById('name_counter').textContent = nameLength + ' / 75';

    if (nameLength > 75) {
        document.getElementById('name_counter').style.color = 'red';
    } else {
        document.getElementById('name_counter').style.color = 'white';
    }
});

// Description length counter
document.getElementById('description').addEventListener('input', function() {
    let descLength = document.getElementById('description').value.length;
    document.getElementById('desc_counter').textContent = descLength + ' / 400';

    if (descLength > 400) {
        document.getElementById('desc_counter').style.color = 'red';
    } else {
        document.getElementById('desc_counter').style.color = 'white';
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
        document.getElementById('tags_counter').style.color = 'white';
    }
});


// Form Error Check
form.addEventListener('submit', function(e) {
    let galleryName = document.getElementById('name');
    let galleryDesc = document.getElementById('description');
    let galleryTags = document.getElementById('tags');
    let formErrors = document.getElementById('form_errors');

    if (galleryName.value === '' || galleryDesc.value === '') {
        formErrors.textContent = 'Täytä kaikki pakolliset kentät.';
        e.preventDefault();
    }

    if (galleryName.value.length > 75) {
        currentNameLength = galleryName.value.length;
        formErrors.textContent = 'Gallerian nimi on liian pitkä. ' + currentNameLength +'/75 merkkiä.';
        e.preventDefault();
    }

    if (galleryDesc.value.length > 400) {
        currentDescLength = galleryDesc.value.length;
        formErrors.textContent = 'Kuvaus on liian pitkä. ' + currentDescLength +'/400 merkkiä.';
        e.preventDefault();
    }

    if (galleryTags.value.split(' ').length > 15) {
        currentTagsLength = galleryTags.value.split(' ').length;
        formErrors.textContent = 'Avainsanat ylittävätä merkkirajan. ' + currentTagsLength +'/ 15kpl.';
        e.preventDefault();
    }
});


