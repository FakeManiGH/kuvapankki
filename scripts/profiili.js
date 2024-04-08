// EDIT PROFILE POPUP

// Edit profile button
const editProfileBtn = document.getElementById('edit_profile');
const infoSuccess = document.getElementById('infoSuccess');

// Edit profile event listener
editProfileBtn.addEventListener('click', editProfileInfo);

// Edit profile function
function editProfileInfo() {
    
    // User information
    let userId = user.user_id;
    let username = user.username;
    let firstName = user.first_name;
    let lastName = user.last_name;
    let phone = user.phone;
    let email = user.email;
            
    // create a popup window
    const popup = document.createElement('div');
    popup.classList.add('popup');
    popup.innerHTML = `
        <h3>Muokkaa käyttäjätietoja</h3>
        <p>Täytä tiedot ja tallenna muutokset. <strong class="red">*</strong> - pakollinen kenttä.</p>
        <button class="popup_close func_btn red"><i class="fa fa-circle-xmark"></i></button>

        <form id="edit_profile_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="${userId}">

            <span class="form_group">
            <label for="username">Käyttäjänimi <strong class="red">*</strong></label>
            <input type="text" name="username" id="edit_username" value="${username}" required>
            <p id="username_error" class="red"></p>
            </span>

            <span class="form_group">
            <label for="first_name">Etunimi <strong class="red">*</strong></label>
            <input type="text" name="first_name" id="edit_first_name" value="${firstName}" required>
            <p id="fn_error" class="red"></p>
            </span>

            <span class="form_group">
            <label for="last_name">Sukunimi <strong class="red">*</strong></label>
            <input type="text" name="last_name" id="edit_last_name" value="${lastName}" required>
            <p id="ln_error" class="red"></p>
            </span>

            <span class="form_group">
            <label for="phone">Puhelin <strong class="red">*</strong></label>
            <input type="text" name="phone" id="edit_phone" value="0${phone}" required>
            <p id="phone_error" class="red"></p>
            </span>

            <span class="form_group">
            <label for="email">Sähköposti <strong class="red">*</strong></label>
            <input type="email" name="email" id="edit_email" value="${email}" required>
            <p id="email_error" class="red"></p>
            </span>

            <span class="form_group">
            <label for="pwd">Salasana <strong class="red">*</strong></label>
            <input type="password" name="pwd" id="edit_password">
            <p id="password_error" class="red"></p>
            </span>

            <div class="buttons">
                <button type="submit" class="btn save">Tallenna</button>
                <button type="button" class="btn cancel">Peruuta</button>
            </div>

            <p id="infoError" class="red"></p>
        </form>`;

    menuOverlay.classList.add('active');
    document.body.appendChild(popup);


    
    // FORM SUBMIT
    // Update the profile information
    const editProfileForm = document.getElementById('edit_profile_form');
    const infoError = document.getElementById('infoError');

    editProfileForm.addEventListener('submit', async function(e) {

        e.preventDefault();

        // Get the form data
        let formData = new FormData(this);

        // Send the form data
        try {
            const response = await fetch('api/profile_edit.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                infoError.innerHTML = 'Virhe tietojen päivityksessä, yritä uudelleen.';
                setTimeout(() => {
                    infoError.innerHTML = '';
                }, 3000);
                throw new Error('Virhe tietojen päivityksessä, yritä uudelleen.');
            }

            // Get the response data
            let data;

            try {
                data = await response.json();
            } catch (error) {
                console.error('Error parsing JSON:', error);
                return;
            }

            // Display the error message(s)
            if (!data.success) {

                if(data.errors.fiels) { 
                    infoError.innerHTML = data.errors.fiels;
                    setTimeout(() => {
                        infoError.innerHTML = '';
                    }, 3000);
                } else {
                    infoError.innerHTML = data.error;
                }
                
                if(data.errors.username) {
                    document.getElementById('username_error').innerHTML = data.errors.username;
                    document.getElementById('edit_username').value = username;
                } else {
                    document.getElementById('username_error').innerHTML = '';
                }

                if(data.errors.first_name) {
                    document.getElementById('fn_error').innerHTML = data.errors.first_name;
                    document.getElementById('edit_first_name').value = firstName;
                } else {
                    document.getElementById('fn_error').innerHTML = '';
                }

                if(data.errors.last_name) {
                    document.getElementById('ln_error').innerHTML = data.errors.last_name;
                    document.getElementById('edit_last_name').value = lastName;
                } else {
                    document.getElementById('ln_error').innerHTML = '';
                }

                if(data.errors.phone) {
                    document.getElementById('phone_error').innerHTML = data.errors.phone;
                    document.getElementById('edit_phone').value = '0' + phone;
                } else {
                    document.getElementById('phone_error').innerHTML = '';
                }

                if(data.errors.email) {
                    document.getElementById('email_error').innerHTML = data.errors.email;
                    document.getElementById('edit_email').value = email;
                } else {
                    document.getElementById('email_error').innerHTML = '';
                }

                if(data.errors.pwd) {
                    document.getElementById('password_error').innerHTML = data.errors.pwd;
                } else {
                    document.getElementById('password_error').innerHTML = '';
                }
            }

            // Update the user information
            if(data.success) {
                infoSuccess.style.display = 'block';
                infoSuccess.innerHTML = data.message;
                setTimeout(() => {
                    infoSuccess.innerHTML = '';
                    infoSuccess.style.display = 'none';
                }, 3000);

                // Update the user information
                user.username = data.updated.username;
                user.first_name = data.updated.first_name;
                user.last_name = data.updated.last_name;
                user.phone = data.updated.phone;
                user.email = data.updated.email;

                // Update the user information in the menu and profile
                document.getElementById('menu_username').innerHTML = user.username;
                document.getElementById('username').innerHTML = user.username;
                document.getElementById('first_name').innerHTML = user.first_name;
                document.getElementById('last_name').innerHTML = user.last_name;
                document.getElementById('phone_number').innerHTML = user.phone;
                document.getElementById('email_address').innerHTML = user.email;

                // Close the popup
                menuOverlay.classList.remove('active');
                document.querySelector('.popup').remove();
            }

        } catch (error) {
            console.error('Error fetching data:', error);
        }
    });

    // Close the popup
    document.querySelector('.popup_close').addEventListener('click', () => {
        menuOverlay.classList.remove('active');
        popup.remove();
    });

    // Close with window click
    document.addEventListener('click', (e) => {
        if(e.target === menuOverlay) {
            menuOverlay.classList.remove('active');
            popup.remove();
        }
    });

    // Cancel the edit
    document.querySelector('.cancel').addEventListener('click', () => {
        menuOverlay.classList.remove('active');
        popup.remove();
    });
}





// PROFILE IMAGE

// User profile image
var oldProfileImg = document.getElementById('profile_img').src; // Old or default profile image
const fileErr = document.getElementById('file_err');
const fileSucc = document.getElementById('file_success');

// Maximum size of the image
document.getElementById('userfile').addEventListener('change', function(e) {
    var maxSize = 2 * 1024 * 1024; // 2MB
    var file = e.target.files[0];

    if(file.size > maxSize) {
        fileErr.innerHTML = 'Tiedosto on liian suuri, maksimikoko on 2MB';
        e.target.value = ''; // Clear the input
        setTimeout(() => {
            fileErr.innerHTML = '';
        } , 3000);
    } else {
        fileErr.innerHTML = '';
    }
});

// Check if the file is an image
document.getElementById('userfile').addEventListener('change', function(e) {
    var file = e.target.files[0];
    var fileType = file.type;

    if(fileType != 'image/jpeg' && fileType != 'image/png') {
        fileErr.innerHTML = 'Tiedosto ei ole kuvatiedosto, vain JPG ja PNG sallittu.';
        e.target.value = ''; // Clear the input
        setTimeout(() => {
            fileErr.innerHTML = '';
        } , 3000);
    } else {
        fileErr.innerHTML = '';
    }
});

// Image preview
document.getElementById('userfile').addEventListener('change', function(e) {
    var file = e.target.files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        document.getElementById('profile_img').src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
        document.getElementById('clear_file_select').style.display = 'flex';
        document.getElementById('accept_file_select').style.display = 'flex';
    } else {
        document.getElementById('profile_img').src = oldProfileImg;
    }
});

// Delete the selected image
document.getElementById('clear_file_select').addEventListener('click', function() {
    if(document.getElementById('userfile').value != '') {
        document.getElementById('userfile').value = '';
        document.getElementById('profile_img').src = oldProfileImg;
        document.getElementById('clear_file_select').style.display = 'none';
        document.getElementById('accept_file_select').style.display = 'none';
    }
});

// Accept the selected image
const imageForm = document.getElementById('profile_img_form');

imageForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Get the form data
    let formData = new FormData(this);

    // Check if the form is empty
    if (!formData.has('userfile')) {
        fileErr.innerHTML = 'Valitse kuva ensin.';
        return;
    }

    // Send the form data
    try {
        const response = await fetch('api/profile_img.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            fileErr.innerHTML = 'Virhe kuvan päivityksessä, yritä uudelleen.';
            throw new Error('Virhe kuvan päivityksessä, yritä uudelleen.');
        }

        // Get the response data
        let data;

        try {
            data = await response.json();
        } catch (error) {
            console.error('Error parsing JSON:', error);
            return;
        }

        // Display the error message
        if (!data.success) {
            fileErr.innerHTML = data.error;
            return;
        }

        // Update the profile image
        if(data.success) {
            fileSucc.style.display = 'block';
            fileSucc.innerHTML = data.message;
            document.getElementById('menu_profile_img').src = document.getElementById('profile_img').src;
            document.getElementById('tiny_profile_img').src = document.getElementById('profile_img').src;
            setTimeout(() => {
                fileSucc.innerHTML = '';
                fileSucc.style.display = 'none';
            }, 3000);
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }

    // Clear the input
    document.getElementById('userfile').value = '';

    // Hide the buttons
    document.getElementById('clear_file_select').style.display = 'none';
    document.getElementById('accept_file_select').style.display = 'none';
});




// RESET PASSWORD

// Reset password button
const passwordForm = document.getElementById('pwd_form');
const passwordErr = document.getElementById('pwd_error');
const passwordSucc = document.getElementById('pwd_success');

passwordForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    // Get the form data
    let formData = new FormData(this);

    // Send the form data
    try {
        const response = await fetch('api/password_reset.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            passwordErr.style.display = 'block';
            passwordErr.innerHTML = 'Virhe salasanan päivityksessä, yritä uudelleen.';
            throw new Error('Virhe salasanan päivityksessä, yritä uudelleen.');
        }

        // Get the response data
        let data;

        try {
            data = await response.json();
        } catch (error) {
            console.error('Error parsing JSON:', error);
            return;
        }

        // Display the error message
        if (!data.success) {
            passwordErr.style.display = 'block';
            passwordErr.innerHTML = data.error;
            setTimeout(() => {
                passwordErr.innerHTML = '';
                passwordErr.style.display = 'none';
            }, 3000);
            return;
        }

        // Update the password
        if(data.success) {
            passwordSucc.style.display = 'block';
            passwordSucc.innerHTML = data.message;
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }

    // Clear the input
    document.getElementById('password_form').reset();
});
