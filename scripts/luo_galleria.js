const galleryType = document.getElementById('gallery_type');
const galleryMembers = document.getElementById('select_users_area');
const galleryMembersList = document.getElementById('selected_users_container');
const selectedUserList = document.getElementById('selected_users_list');
const galleryForm = document.getElementById('create_gallery');


// GALLERY VISIBILITY
// Visibility change to show/hide members
galleryType.addEventListener('change', function() {
    if (galleryType.value === '4') {
        galleryMembers.style.display = 'none';
    } else {
        galleryMembers.style.display = 'flex';
    }
});


// ADDING USERS TO GALLERY

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
        removeButton.classList.add('func_btn');
        removeButton.type = 'button';
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

// Selecting users error (if no user is selected)
function selectError() {
    const selectingErr = document.createElement('p');
    selectingErr.id = 'select_error';
    selectingErr.classList.add('red');
    selectingErr.style.alignSelf = 'flex-end';
    selectingErr.textContent = 'Valitse käyttäjä';
    galleryMembers.appendChild(selectingErr);
    setTimeout(function() {
        selectingErr.remove();
    }, 3000);
}


// INFO POPUPS

// Gallery visibility info popup
const visibilityInfo = document.getElementById('visibility_info');
const visibilityPopup = document.getElementById('visibility_popup');
const closeVisibilityInfo = document.getElementById('close_visibility_info');

visibilityInfo.addEventListener('mouseover', showVisibilityPopup);

visibilityInfo.addEventListener('mouseout', function() {
    visibilityPopup.style.display = 'none';
});

function showVisibilityPopup() {
    if (visibilityPopup.style.display === 'flex') {
        visibilityPopup.style.display = 'none';
    } else {
        visibilityPopup.style.display = 'flex';
    }
}

closeVisibilityInfo.addEventListener('click', function() {
    visibilityPopup.style.display = 'none';
});

// Gallery type info popup
const typeInfo = document.getElementById('type_info');
const typePopup = document.getElementById('type_popup');
const closeTypeInfo = document.getElementById('close_type_info');

typeInfo.addEventListener('mouseover', showTypePopup); 

typeInfo.addEventListener('mouseout', function() {
    typePopup.style.display = 'none';
});

function showTypePopup() {
    if (typePopup.style.display === 'flex') {
        typePopup.style.display = 'none';
    } else {
        typePopup.style.display = 'flex';
    }
}

closeTypeInfo.addEventListener('click', function() {
    typePopup.style.display = 'none';
});

// Selected users info popup
const usersInfo = document.getElementById('users_info');
const usersPopup = document.getElementById('users_popup');
const closeUsersPopup = document.getElementById('close_users_popup');

usersInfo.addEventListener('mouseover', showUsersPopup); 

usersInfo.addEventListener('mouseout', function() {
    usersPopup.style.display = 'none';
});

function showUsersPopup() {
    if (usersPopup.style.display === 'flex') {
        usersPopup.style.display = 'none';
    } else {
        usersPopup.style.display = 'flex';
    }
}

closeUsersPopup.addEventListener('click', function() {
    usersPopup.style.display = 'none';
});


// CLEARING FORM

// Clear form
document.getElementById('reset').addEventListener('click', function() {
    document.getElementById('gallery_name').value = '';
    document.getElementById('gallery_description').value = '';
    document.getElementById('gallery_category').value = '0';
    document.getElementById('gallery_type').value = '1';
    document.getElementById('gallery_tags').value = '';
    document.getElementById('gallery_visibility').value = '1';
    document.getElementById('form_errors').textContent = '';

    // Hiding selected users and clearing list
    let selectUsers = document.getElementById('select_users');
    let selectedUsers = document.getElementById('selected_users');
    Array.from(selectedUsers.options).forEach(option => {
        selectedUsers.remove(option.index);
        selectUsers.appendChild(option);
    });
    document.getElementById('select_users_area').style.display = 'none';
    document.getElementById('selected_users_list').innerHTML = '';
});


// FORM INPUT COUNTERS

// Name length counter
document.getElementById('gallery_name').addEventListener('input', function() {
    let nameCounter = document.getElementById('name_counter');
    let nameLength = document.getElementById('gallery_name').value.length;

    if (nameLength === 0) {
        nameCounter.textContent = '';
        nameCounter.display = 'none';
    } else {
        nameCounter.textContent = nameLength + ' / 75';
        if (nameLength > 75) {
            nameCounter.style.color = 'red';
        } else {
            nameCounter.style.color = 'white';
        }
    }
});

// Description length counter
document.getElementById('gallery_description').addEventListener('input', function() {
    let descLength = document.getElementById('gallery_description').value.length;
    let descCounter = document.getElementById('desc_counter');

    if (descLength === 0) {
        descCounter.textContent = '';
        descCounter.display = 'none';
    } else {
        descCounter.textContent = descLength + ' / 400';
        if (descLength > 400) {
            document.getElementById('desc_counter').style.color = 'red';
        } else {
            document.getElementById('desc_counter').style.color = 'white';
        }
    }
});

// Tag counter
document.getElementById('gallery_tags').addEventListener('input', function() {
    let tagInput = document.getElementById('gallery_tags').value.trim().replace(/\s+/g, ' ');
    let tags = tagInput.split(' ');
    let tagsLength = tags.length;
    let tagsCounter = document.getElementById('tags_counter');

    tagsCounter.textContent = tagsLength + ' / 15kpl';
    
    if (!tagInput === '') {
        tagsLength = 1;
    }
    if (tagInput === '') {
        tagsCounter.textContent = '';
        tagsCounter.display = 'none';
    }

    if (tagsLength > 15) {
        document.getElementById('tags_counter').style.color = 'red';
    } else {
        document.getElementById('tags_counter').style.color = 'white';
    }
});


// FORM VALIDATION & SUBMIT

// Form Error Check
galleryForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Form input elements
    const galleryName = document.getElementById('gallery_name');
    const galleryDesc = document.getElementById('gallery_description');
    const galleryCategory = document.getElementById('gallery_category');
    const galleryVisibility = document.getElementById('gallery_visibility');
    const galleryType = document.getElementById('gallery_type');
    const galleryTags = document.getElementById('gallery_tags');
    
    // Error message elements
    const formErrors = document.getElementById('form_errors'); // Form error message
    const nameError = document.getElementById('gallery_name_error'); // Name error message
    const descError = document.getElementById('gallery_desc_error'); // Description error message
    const tagsError = document.getElementById('gallery_tags_error'); // Tags error message
    const categoryError = document.getElementById('gallery_category_error'); // Category error message
    const visibilityError = document.getElementById('gallery_visibility_error'); // Visibility error message
    const typeError = document.getElementById('gallery_type_error'); // Type error message

    // Validations
    let errors = false;

    if (galleryName.value === '') {
        nameError.style.display = 'block';
        nameError.textContent = 'Anna gallerialle nimi.';
        errors = true;
    }

    if (galleryDesc.value === '') {
        descError.style.display = 'block';
        descError.textContent = 'Anna gallerian kuvaus.';
        errors = true;
    }

    if (galleryName.value.length > 75) {
        currentNameLength = galleryName.value.length;
        nameError.style.display = 'block';
        nameError.textContent = 'Gallerian nimi on liian pitkä. ' + currentNameLength +'/75 merkkiä.';
        errors = true;
    }

    if (galleryDesc.value.length > 400) {
        currentDescLength = galleryDesc.value.length;
        descError.style.display = 'block';
        descError.textContent = 'Kuvaus on liian pitkä. ' + currentDescLength +'/400 merkkiä.';
        errors = true;
    }

    if (galleryCategory.value === '0') {
        categoryError.style.display = 'block';
        categoryError.textContent = 'Valitse gallerian kategoria.';
        errors = true;
    }

    if (galleryTags.value === '' || galleryTags.value === ' ') {
        tagsError.style.display = 'block';
        tagsError.textContent = 'Lisää gallerialle avainsanat.';
        errors = true;
    }

    if ((galleryTags.value.split(' ').length > 15)) {
        currentTagsLength = galleryTags.value.split(' ').length;
        tagsError.style.display = 'block';
        tagsError.textContent = 'Avainsanat ylittävätä merkkirajan. ' + currentTagsLength +'/ 15kpl.';
        errors = true;
    }

    if ((!galleryVisibility.value === '1') && (!galleryVisibility.value === '2') && (!galleryVisibility.value === '3')) {
        visibilityError.style.display = 'block';
        visibilityError.textContent = 'Valitse gallerialle näkyvyys.';
        errors = true;
    }

    if ((!galleryType.value === '1') && (!galleryType.value === '2') && (!galleryType.value === '3') && (!galleryType.value === '4')) {
        typeError.style.display = 'block';
        typeError.textContent = 'Valitse gallerian tyyppi.';
        errors = true;
    }

    if (errors) {
        return;
    }


    // FORM SUBMIT

    // Create form data
    let formData = new FormData(this);
        
    try {
        const response = await fetch('api/gallery_create.php', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            formErrors.innerHTML = 'Virhe lomakkeen lähetyksessä. Yritä uudelleen.';
            throw new Error('Network response was not ok');
        }

        let data;

        try {
            data = await response.json();
        } catch (error) {
            console.error('Error parsing JSON:', error);
        }

        if (!data.success) {
            let errors = data.errors;
            let correct = data.correct;
            
            if (errors.name) {
                nameError.style.display = 'block';
                if (Array.isArray(errors.name)) {
                    nameError.textContent = '';
                    errors.name.forEach(error => {
                        nameError.textContent += error + ' ';
                    });
                } else {
                    nameError.textContent = errors.name;
                }
            } else {
                galleryName.value = correct.name;
            }

            if (errors.description) {
                descError.style.display = 'block';
                descError.textContent = errors.description;
            } else {
                galleryDesc.value = correct.description;
            }

            if (errors.category) {
                categoryError.style.display = 'block';
                categoryError.textContent = errors.category;
            } else {
                galleryCategory.value = correct.category;
            }

            if (errors.visibility) {
                visibilityError.style.display = 'block';
                visibilityError.textContent = errors.visibility;
            } else {
                galleryVisibility.value = correct.visibility;
            }

            if (errors.type) {
                typeError.style.display = 'block';
                typeError.textContent = errors.type;
            } else {
                galleryType.value = correct.type;
            }

            if (errors.tags) {
                tagsError.style.display = 'block';
                tagsError.textContent = errors.tags;
            } else {
                galleryTags.value = correct.tags;
            }

            if (errors.general) {
                formErrors.style.display = 'block';
                formErrors.textContent = errors.general;
            }
            return;
        }

        // Redirect to gallery page
        if (data.success) {
            let gallery = data.gallery_id;
            window.location.href = 'galleria.php?g=' + gallery;
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
});


