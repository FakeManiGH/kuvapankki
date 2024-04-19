
// CREATE POST

// Get neccecary elements
const formContainer = document.getElementById('form_container'); // Form container
const postForm = document.getElementById('post_form'); // Form
const fileInput = document.getElementById('image_input'); // File input
const gallerySelect = document.getElementById('gallery_select'); // Gallery select
const postDesc = document.getElementById('post_desc'); // Description input
const previewContainer = document.getElementById('preview_container'); // Preview container (for image(s)
const postError = document.getElementById('post_error'); // Error message
const postSuccess = document.getElementById('post_success'); // Success message
const postOpenMarker = document.getElementById('post_open_marker'); 

// Function to count time passed since post was created
function timePassed(givenDate) {
    const date = new Date(givenDate);
    const now = new Date();
    const diff = now - date;
    const diffYears = Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
    const diffMonths = Math.floor(diff / (1000 * 60 * 60 * 24 * 30.44));
    const diffDays = Math.floor(diff / (1000 * 60 * 60 * 24));
    const diffHours = Math.floor(diff / (1000 * 60 * 60));
    const diffMinutes = Math.floor(diff / (1000 * 60));
    const diffSeconds = Math.floor(diff / 1000);
    if (diffYears > 0) {
        return diffYears + ' vuotta sitten';
    } else if (diffMonths > 0) {
        return diffMonths + ' kuukautta sitten';
    } else if (diffDays > 0) {
        return diffDays + ' päivää sitten';
    } else if (diffHours > 0) {
        return diffHours + ' tuntia sitten';
    } else if (diffMinutes > 0) {
        return diffMinutes + ' minuuttia sitten';
    } else {
        return diffSeconds + ' sekuntia sitten';
    }
};

// View post from
const postButton = document.getElementById('post_button'); // Form

if (postForm) {
    postButton.addEventListener('click', () => {
    if (formContainer.classList.contains('active')) {
        postButton.innerHTML = 'Julkaise uusi julkaisu <i class="fa fa-chevron-down"></i>';
        formContainer.classList.remove('active');
    } else {
        formContainer.classList.add('active');
        postButton.innerHTML = 'Sulje julkaisu <i class="fa fa-chevron-up"></i>';
    }
});
}   

// Remove file from file input (call from img event listener)
function removeFile(file) {
    const dt = new DataTransfer(); // New DataTransfer object
    const files = fileInput.files;
    for (let i = 0; i < files.length; i++) {
        if (files[i] !== file) {
            dt.items.add(files[i]); // Add file to DataTransfer object
        }
    }
    fileInput.files = dt.files; // Set new file list
}

// IMAGE PREVIEW
if (fileInput) {
    fileInput.addEventListener('change', () => {
        const files = fileInput.files; // Get files
        
        // Check if files are selected
        if (files.length > 0) {
            previewContainer.innerHTML = ''; // Clear preview container
            
            // Loop through files
            for (let i = 0; i < files.length; i++) {
                const file = files[i]; // Get file

                // Check if file is over 5MB
                if (file.size > 5 * 1024 * 1024) {
                    // Display error message if file is over 5MB
                    postError.style.display = 'block';
                    postError.innerHTML = 'Tiedosto ' + file.name + ' on liian suuri. Valitse tiedosto, joka on alle 5MB';
                    setTimeout(() => {
                        postError.style.display = 'none';
                    }, 5000);

                    // Remove file from file input
                    removeFile(file);
                    continue; // Skip to next iteration
                }
                
                // Check if file is image
                if (file.type.match('image')) {
                    const reader = new FileReader(); // New FileReader object
                    
                    // Read file
                    reader.readAsDataURL(file);
                    
                    // Add image to preview container
                    reader.addEventListener('load', () => {
                        // Create div element for image
                        const imgContainer = document.createElement('div');
                        imgContainer.classList.add('preview_image');
                        previewContainer.appendChild(imgContainer);

                        // Add image to div element
                        const img = document.createElement('img');
                        img.src = reader.result;
                        imgContainer.appendChild(img); 

                        // Add remove icon to image
                        const removeIcon = document.createElement('button');
                        removeIcon.classList.add('remove_image');
                        removeIcon.innerHTML = '<i class="fa fa-trash"></i>'; 
                        imgContainer.appendChild(removeIcon);

                        // Add event listener to remove image from preview container and file input
                        removeIcon.addEventListener('click', () => {
                            imgContainer.remove();
                            img.remove();
                            removeIcon.remove();
                            removeFile(file);
                        });
                    });

                } else {
                    // Display error message if file is not an image
                    postError.style.display = 'block';
                    postError.innerHTML = 'Valitse vain kuvatiedostoja.';
                    fileInput.value = '';
                    previewContainer.innerHTML = '';
                    setTimeout(() => {
                        postError.style.display = 'none';
                    }, 3000);
                    return;
                }
            }
        }
    });
}


// CRAETE POST FORM SUBMIT
if (postForm) {
    postForm.addEventListener('submit', async (e) => {
        e.preventDefault(); // Prevent default form submission
        
        const formData = new FormData(postForm); // Create new FormData object
        
        // Check if file input has files
        if (fileInput.files.length == 0) {
            postError.style.display = 'block'; // Show error message
            postError.innerHTML = 'Valitse kuva(t) julkaistavaksi.';
            return;
        }

        // Check if gallery select has value
        if (gallerySelect.value === 'empty') {
            postError.style.display = 'block'; // Show error message
            postError.innerHTML = 'Valitse galleria kuvalle tai kuville.';
            return;
        }

        // Check if description input has value
        if (postDesc.value === '') {
            postError.style.display = 'block'; // Show error message
            postError.innerHTML = 'Anna julkaisulle kuvaus.';
            return;
        }

        // Fetch data from PHP API
        try {
            const response = await fetch('api/post_create.php', {
                method: 'POST',
                body: formData
            });

            // If fetch fails, display error message
            if (!response.ok) {
                postError.style.display = 'block'; // Show error message
                postError.innerHTML = 'Julkaisu epäonnistui, yritä uudelleen.';
                throw new Error('Julkaisu epäonnistui, yritä uudelleen.');
            }

            let data;

            try {
                data = await response.json();
            } catch (error) {
                console.error('Error parsing data:', error);
            }

            // Access properties of data
            let errors = data.errors;

            // If post creation fails, display error message
            if (!data.success) {
                postError.style.display = 'block'; // Show error message
                postError.innerHTML = data.error;
                errors.forEach(error => {
                    postError.innerHTML += error + '<br>';
                });
                return;
            }

            // If post creation is successful, display success message
            if (data.success) {
                postSuccess.style.display = 'block';
                postSuccess.innerHTML = data.message;
                resetForm(); // Reset form
                setTimeout(() => {
                    postSuccess.style.display = 'none';
                }, 3000);
                if (data.errors) {
                    postError.style.display = 'block'; // Show error message
                    errors.forEach(error => {
                        postError.innerHTML += error + '<br>';
                    });
                }
            }

        } catch (error) {
            console.error('Error fetching data:', error);
        }

        displayPosts(); // Call function to display posts
    });
}

// RESET FORM
const resetButton = document.getElementById('form_reset'); // Reset button

if (resetButton) {resetButton.addEventListener('click', resetForm);}

function resetForm() {
    postForm.reset(); // Reset form
    formContainer.classList.remove('active'); // Hide form container
    previewContainer.innerHTML = ''; // Clear preview container
    gallerySelect.value = 'empty'; // Reset gallery select
    postDesc.value = ''; // Clear description input
}



// GETTING POSTS

// Get neccecary elements
const postsContainer = document.getElementById('post_list'); // Posts container
const postsGetError = document.getElementById('posts_get_error'); // Error message container

const displayPosts = async () => {
    try {
        const response = await fetch('api/posts_get.php'); // Fetch data from PHP API

        // If fetch fails, display error message
        if (!response.ok) {
            postsContainer.innerHTML = 'Julkaisujen hakeminen epäonnistui.';
            throw new Error('Julkaisujen hakeminen epäonnistui.');
        }

        let data;

        try {
            data = await response.json();
        } catch (error) {
            console.error('Error parsing data:', error);
        }

        if (!data.success) {
            postsGetError.style.display = 'block'; // Show error message
            postsGetError.innerHTML = data.errors;
            return;
        }  

        // Access properties of data
        const posts = data.posts;

        // Sort posts by date (newest first)
        posts.sort((a, b) => new Date(b.date) - new Date(a.date));

        // If posts are found, display them
        if (posts && posts.length > 0) {
            postsContainer.innerHTML = ''; // Clear posts container

            // Loop through posts
            posts.forEach(post => {
                // access properties of post
                const postId = post.post_id;
                const userId = post.user_id;
                const username = post.username;
                const userImage = post.user_img;
                const galleryId = post.gallery_id;
                const galleryName = post.name;
                const dateTxt = post.date;
                const description = post.description;
                const images = post.images;
                const appUserId = data.appUserId;

                
                // Create div element for post
                const postItem = document.createElement('li');
                postItem.classList.add('hero_item', 'post');
                postsContainer.appendChild(postItem);

                // Create post header
                const postHeader = document.createElement('div');
                postHeader.classList.add('post_header');
                postItem.appendChild(postHeader);

                // Header content: Links
                const postLinks = document.createElement('p');
                postLinks.innerHTML = `<a class="user_link" href="kayttaja.php?u=${userId}"><img class="tiny_img" src="${userImage}"> ${username}</a> <i class="fa fa-chevron-right"></i> <a href="galleria.php?g=${galleryId}">${galleryName}</a>`;
                postLinks.classList.add('post_links');
                postHeader.appendChild(postLinks);

                // Header content: Date
                const postDate = document.createElement('p');

                // Display time passed
                postDate.innerHTML = timePassed(dateTxt);
                postHeader.appendChild(postDate);

                // Create image container
                const imgContainer = document.createElement('ul');
                imgContainer.classList.add('post_images');
                postItem.appendChild(imgContainer);

                // Loop through images
                images.forEach(image => {
                    // access properties of image
                    const imageId = image.image_id;
                    const url = image.url;
                    const alt = image.description;

                    // Create list item for image
                    const imgItem = document.createElement('li');
                    imgItem.classList.add('post_image');
                    imgContainer.appendChild(imgItem);

                    // Create image element
                    const img = document.createElement('img');
                    img.src = '../galleries/'+url;
                    img.alt = alt;
                    imgItem.appendChild(img);

                    // Add event listener to open image in full screen
                    img.addEventListener('click', () => {
                        img.requestFullscreen();
                    });
                });

                // Create post description
                const postDesc = document.createElement('p');
                postDesc.innerHTML = description;
                postItem.appendChild(postDesc);

                // Create post footer
                const postFooter = document.createElement('div');
                postFooter.classList.add('buttons');
                postItem.appendChild(postFooter);

                // Footer content: Like button
                const likeButton = document.createElement('button');
                if (post.liked) {
                    likeButton.innerHTML = `<p>${post.likes.length}</p> <i style="color: #993232;" class="fa-solid fa-heart"></i> Tykkäsit`;
                } else {
                    likeButton.innerHTML = `<p>${post.likes.length}</p> <i class="fa-regular fa-heart"></i> Tykkää`;
                }
                likeButton.classList.add('func_btn', 'post_btn', 'like_btn');
                likeButton.setAttribute('data-post-id', postId);
                postFooter.appendChild(likeButton);
                likeButton.addEventListener('click', () => like_post(postId)); // Add event listener to like button

                // Footer content: Comment button
                const commentButton = document.createElement('button');
                commentButton.innerHTML = `<p>${post.comments.length}</p> <i class="fa-regular fa-comment"></i> Kommentoi`;
                commentButton.classList.add('func_btn', 'post_btn', 'comment_btn');
                commentButton.setAttribute('data-post-id', postId);
                postFooter.appendChild(commentButton);
                commentButton.addEventListener('click', () => {
                    commentContainer.classList.toggle('active');
                });
                    // if comments includes users id, edit commentButton
                    post.comments.forEach(comment => {
                        if (comment.user_id == appUserId) {
                            commentButton.innerHTML = `<p>${post.comments.length}</p> <i style="color: #256599;" class="fa fa-comment"></i> Kommentoi`;
                        }
                    });

                // Footer content: if user is owner of post, add edit button
                if (userId == appUserId) {
                    const editButton = document.createElement('button');
                    editButton.innerHTML = '<i class="fa-regular fa-edit"></i> Muokkaa';
                    editButton.classList.add('func_btn', 'post_btn');
                    postFooter.appendChild(editButton);
                    editButton.addEventListener('click', () => edit_post(postId, description, images, galleryId)); // Add event listener to edit button
                }


                // POST COMMENTS
                const comments = post.comments;
                
                // Create div element for comments
                const commentContainer = document.createElement('div');
                commentContainer.classList.add('comment_container');
                postItem.appendChild(commentContainer);

                // Create comments header
                const commentHeader = document.createElement('div');
                commentHeader.classList.add('comments_header');
                commentContainer.appendChild(commentHeader);

                // Header content: Comments title   
                const commentTitle = document.createElement('h4');
                commentTitle.innerHTML = 'Kommentit:';
                commentHeader.appendChild(commentTitle);

                // Header content: Hide comments button
                const hideComments = document.createElement('a');
                hideComments.innerHTML = '<i class="fa fa-chevron-up"></i> Piilota kommentit';
                hideComments.setAttribute('href', 'javascript:void(0)');
                commentHeader.appendChild(hideComments);
                hideComments.addEventListener('click', () => {
                    commentContainer.classList.remove('active');
                }); 
            
                // Create comments list
                const commentList = document.createElement('ul');
                commentList.classList.add('comments_list');
                commentList.setAttribute('data-post-id', postId);
                commentContainer.appendChild(commentList);

                if (comments.length === 0) {
                    // empty list item
                    const commentItem = document.createElement('li');
                    commentItem.innerHTML = 'Ei vielä kommentteja. Ole ensimmäinen!';
                    commentItem.classList.add('comment_item', 'grey');
                    commentList.appendChild(commentItem);
                } else {
                    showComments(appUserId, comments, commentList, postId, post.comments.length); // Call function to show comments
                }


                // COMMENT FORM
                // Create comment form
                const commentForm = document.createElement('form');
                commentForm.classList.add('comment_form');
                commentForm.setAttribute('data-post-id', postId);
                commentContainer.appendChild(commentForm);

                // Create hidden input for post id
                const postIdInput = document.createElement('input');
                postIdInput.setAttribute('type', 'hidden');
                postIdInput.setAttribute('name', 'post_id');
                postIdInput.setAttribute('value', postId);
                commentForm.appendChild(postIdInput);

                // Create comment input
                const commentInput = document.createElement('input');
                commentInput.setAttribute('type', 'text');
                commentInput.setAttribute('maxlength', '300');
                commentInput.classList.add('comment_input');
                commentInput.setAttribute('name', 'comment');
                commentInput.setAttribute('placeholder', 'Kirjoita kommentti...');
                commentInput.setAttribute('required', '');
                commentForm.appendChild(commentInput);

                // Create comment submit button
                const commentSubmit = document.createElement('button');
                commentSubmit.innerHTML = 'Kommentoi';
                commentSubmit.classList.add('small_btn');
                commentForm.appendChild(commentSubmit);
            
                // Create counter for comment input
                const commentlength = document.createElement('p');
                commentlength.classList.add('small_txt', 'grey');
                commentContainer.appendChild(commentlength);
                commentInput.addEventListener('input', () => {
                    commentlength.innerHTML = commentInput.value.length + '/300 merkkiä';
                    if (commentInput.value.length > 300) {
                        commentlength.style.color = '#993232';
                    } else {
                        commentlength.style.color = '#777';
                    }
                    if (commentInput.value.length === 0) {
                        commentlength.innerHTML = '';
                    }
                });

                // Create error message for comment form
                const commentError = document.createElement('p');
                commentError.classList.add('error_msg', 'comment_error');
                commentError.setAttribute('data-id', postId);
                commentForm.appendChild(commentError);

            
                // SUBMIT COMMENT FORM
                // Event listener for comment form submission
                commentForm.addEventListener('submit', async (e) => {
                    e.preventDefault(); // Prevent default form submission

                    // Check if comment input has value
                    if (commentInput.value === '') {
                        commentInput.style.borderColor = '#993232';
                        commentError.style.display = 'block'; // Show error message
                        commentError.innerHTML = 'Kirjoita kommentti.';
                        return;
                    }

                    // Check if comment input has over 300 characters
                    if (commentInput.value.length > 300) {
                        commentInput.style.borderColor = '#993232';
                        commentError.style.display = 'block'; // Show error message
                        commentError.innerHTML = 'Kommentti saa olla enintään 300 merkkiä pitkä.';
                        return;
                    }

                    // Fetch data from PHP API
                    try {
                        const formData = new FormData(commentForm); // Create new FormData object
                        const response = await fetch('api/comment_post.php', {
                            method: 'POST',
                            body: formData
                        });

                        // If fetch fails, display error message
                        if (!response.ok) {
                            commentError.style.display = 'block'; // Show error message
                            commentError.innerHTML = 'Kommentointi epäonnistui, yritä uudelleen.';
                            throw new Error('Kommentointi epäonnistui, yritä uudelleen.');
                        }

                        let data;

                        try {
                            data = await response.json();
                        } catch (error) {
                            console.error('Error parsing data:', error);
                        }

                        // Access properties of data
                        let errors = data.errors;

                        // If comment creation fails, display error message
                        if (!data.success) {
                            commentError.style.display = 'block'; // Show error message
                            commentError.innerHTML = data.error;
                            errors.forEach(error => {
                                postsGetError.innerHTML += error + '<br>';
                            });
                            return;
                        }

                        // If comment creation is successful, display success message
                        if (data.success) {
                            commentForm.reset(); // Reset form
                            commentlength.innerHTML = '';
                            commentError.style.display = 'none';
                            commentContainer.classList.add('active');
                            commentButton.innerHTML = `<p>${data.comments.length}</p> <i style="color: #256599;" class="fa fa-comment"></i> Kommentoi`;
                            commentList.innerHTML = '';
                            const comments = data.comments;
                            showComments(appUserId, comments, commentList, postId, commentButton); // Call function to show comments
                        }

                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });
            
        } else {
            postsContainer.innerHTML = `<p class="grey">Ei näytettäviä julkaisuja.</p>`;
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}


// LIKE POST
const like_post = async (postId) => {

    let likeButton = document.querySelector('.like_btn[data-post-id="'+postId+'"]')
    
    try {
        const response = await fetch('api/post_like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ post_id: postId })
        });

        // If fetch fails, display error message
        if (!response.ok) {
            postsGetError.style.display = 'block'; // Show error message
            postsGetError.innerHTML = 'Tykkäys epäonnistui.';
            throw new Error('Tykkäys epäonnistui.');
        }

        let data;

        try {
            data = await response.json();
        } catch (error) {
            console.error('Error parsing data:', error);
        }

        // If post creation fails, display error message
        if (!data.success) {
            postsGetError.style.display = 'block'; // Show error message
            postsGetError.innerHTML = data.error;
            return;
        }

        if (data.success) {
            const likes = data.likes;
            if (data.liked) {
                likeButton.innerHTML = `<p>${likes.length}</p> <i style="color: #993232;" class="fa-solid fa-heart"></i> Tykkäsit`;
                return;
            } else {
                likeButton.innerHTML = `<p>${likes.length}</p> <i class="fa-regular fa-heart"></i> Tykkää`;
                return;
            }
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}


// EDITING AND DELETING POSTS
// EDIT POST
const edit_post = (postId, description, images, galleryId) => {
    let descUnedited = description;
    document.body.style.overflow = 'hidden'; // Disable scrolling
    
    // Create popup with form for editing post
    const popup = document.createElement('div');
    popup.classList.add('popup');
    popup.id = 'edit_post_popup';
    document.body.appendChild(popup);

    // Create form for editing post
    popup.innerHTML = `
        <button title="Sulje muokkaus" id="edit_close" class="popup_close func_btn red"><i class="fa fa-circle-xmark"></i></button>
        <form id="edit_form" class="form">
            <h2>Muokkaa julkaisua</h2>
            <input type="hidden" class="hidden" name="post_id" value="${postId}">
            <div id="image_edit_display" class="post_images"></div>
            <label for="edit_desc">Kuvaus <strong class="red">*</strong></label>
            <textarea id="edit_desc" name="edit_desc" rows="3" required>${description}</textarea>
            <div class="buttons">
                <button title="Tallenna muutokset" type="submit" class="btn">Tallenna</button>
                <button title="Palauta tiedot" type="button" id="edit_reset" class="btn">Palauta</button>
                <button title="Poista julkaisu" type="button" id="edit_delete" class="func_btn"><i class="fa fa-trash"></i></button>
            </div>
        </form>

        <p id="edit_error" class="error_msg"></p>

        <div class="post_delete_container">
            <h4>Haluatko varmasti poistaa julkaisun?</h4>
            <div class="inline_basic">
                <input type="checkbox" id="delete_images" name="delete_images" value="false">
                <label for="delete_images">Poista kuvat galleriasta</label>
            </div>
            <div class="buttons">
                <button title="Poista julkaisu" id="delete_confirm" class="small_btn"><i class="fa fa-trash"></i> Poista</button>
                <button title="Peruuta poisto" id="delete_cancel" class="func_btn post_btn">Peruuta</button>
            </div>
        </div>
    `;

    // Add images to popup
    const editImages = document.getElementById('image_edit_display');
    images.forEach(image => {

        // Image priview element
        const img = document.createElement('img');
        img.src = '../galleries/'+image.url;
        img.alt = image.description;
        img.classList.add('post_image');
        img.setAttribute('data-image-id', image.image_id);
        editImages.appendChild(img);
    });
    menuOverlay.classList.add('active');

    // Add event listener to close button
    const editClose = document.getElementById('edit_close');
    editClose.addEventListener('click', () => {
        popup.remove();
        menuOverlay.classList.remove('active');
        document.body.style.overflow = 'auto'; // Enable scrolling
    });

    // Add event listener to reset button
    const editReset = document.getElementById('edit_reset');
    editReset.addEventListener('click', () => {
        const editDesc = document.getElementById('edit_desc');
        editDesc.value = descUnedited;
    });

    // Add event listener to close popup when clicking outside of it
    document.querySelector('.menu_overlay').addEventListener('click', () => {
        popup.remove();
        menuOverlay.classList.remove('active');
        document.body.style.overflow = 'auto'; // Enable scrolling
    });


    // EVENT LISTENER FOR POST DELETE
    const editDelete = document.getElementById('edit_delete');
    editDelete.addEventListener('click', () => {
        
        // Show delete popup
        const deleteContainer = document.querySelector('.post_delete_container');
        if (deleteContainer.style.display === 'flex') {
            deleteContainer.style.display = 'none';
            editDelete.classList.remove('red');
        } else {
            deleteContainer.style.display = 'flex';
            editDelete.classList.add('red');
        }

        // Add event listener to delete confirm button
        const deleteConfirm = document.getElementById('delete_confirm');
        deleteConfirm.addEventListener('click', () => {
            let deleteImages = 0;
            if (document.getElementById('delete_images').checked) {
                deleteImages = 1;
            }
            delete_post(postId, galleryId, images, deleteImages); // Call function to delete post
        });

        // Add event listener to delete cancel button
        const deleteCancel = document.getElementById('delete_cancel');
        deleteCancel.addEventListener('click', () => {
            deleteContainer.style.display = 'none';
        });
    });


    // EDIT FORM SUBMIT
    const editForm = document.getElementById('edit_form');

    editForm.addEventListener('submit', async (e) => {
        e.preventDefault(); // Prevent default form submission

        // Check if description input has value
        if (editForm.edit_desc.value === '') {
            const editError = document.getElementById('edit_error');
            editError.style.display = 'block'; // Show error message
            editError.innerHTML = 'Anna julkaisulle kuvaus.';
            return;
        }

        // Check if description input has changed
        if (editForm.edit_desc.value === descUnedited) {
            const editError = document.getElementById('edit_error');
            editError.style.display = 'block'; // Show error message
            editError.innerHTML = 'Muokkaa kuvausta ennen tallentamista.';
            return;
        }

        // Check if description value is over 400 characters
        if (editForm.edit_desc.value.length > 400) {
            const editError = document.getElementById('edit_error');
            editError.style.display = 'block'; // Show error message
            editError.innerHTML = 'Kuvaus saa olla enintään 400 merkkiä pitkä.';
            return;
        }

        const formData = new FormData(editForm); // Create new FormData object

        // Fetch data from PHP API
        try {
            const response = await fetch('api/post_edit.php', {
                method: 'POST',
                body: formData
            });

            // If fetch fails, display error message
            if (!response.ok) {
                postsGetError.style.display = 'block'; // Show error message
                postsGetError.innerHTML = 'Muokkaus epäonnistui, yritä uudelleen.';
                throw new Error('Muokkaus epäonnistui, yritä uudelleen.');
            }

            let data;

            try {
                data = await response.json();
            } catch (error) {
                console.error('Error parsing data:', error);
            }

            // Access properties of data
            let errors = data.errors;

            // If post creation fails, display error message
            if (!data.success) {
                const editError = document.getElementById('edit_error');
                editError.style.display = 'block'; // Show error message
                editError.innerHTML = data.error;
                errors.forEach(error => {
                    editError.innerHTML += error + '<br>';
                });
                return;
            }

            // If post creation is successful, display success message
            if (data.success) {
                popup.remove();
                menuOverlay.classList.remove('active');
                document.body.style.overflow = 'auto'; // Enable scrolling
                displayPosts(); // Call function to display posts
                postSuccess.style.display = 'block';
                postSuccess.innerHTML = data.message;
                setTimeout(() => {
                    postSuccess.style.display = 'none';
                }, 3000);
            }

        } catch (error) {
            console.error('Error fetching data:', error);
        }
    });
}

// DELETE POST

const delete_post = async (postId, galleryId, images, deleteImages) => {

    const editError = document.getElementById('edit_error');

    let row = { post_id: postId, gallery_id: galleryId, images: images, delete_images: deleteImages }

    // Fetch data from PHP API
    try {
        const response = await fetch('api/post_delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(row)
        });

        // If fetch fails, display error message
        if (!response.ok) {
            editError.style.display = 'block'; // Show error message
            editError.innerHTML = 'Julkaisun poisto epäonnistui.';
            throw new Error('Julkaisun poisto epäonnistui.');
        }

        let data;

        try {
            data = await response.json();
        } catch (error) {
            console.error('Error parsing data:', error);
        }

        // Access properties of data
        let errors = data.errors;

        // If post creation fails, display error message
        if (!data.success) {
            editError.style.display = 'block'; // Show error message
            editError.innerHTML = data.error;
            errors.forEach(error => {
                editError.innerHTML += error + '<br>';
            });
            return;
        }

        // If post creation is successful, display success message
        if (data.success) {
            document.getElementById('edit_post_popup').remove();
            document.querySelector('.menu_overlay').classList.remove('active');
            document.body.style.overflow = 'auto'; // Enable scrolling
            displayPosts(); // Call function to display posts
            postSuccess.style.display = 'block';
            postSuccess.innerHTML = data.message;
            setTimeout(() => {
                postSuccess.style.display = 'none';
            }, 3000);
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }

}



// COMMENTS

// SHOW COMMENTS
const showComments = (appUserId, comments, commentList, postId, commentButton) => {

    comments.sort((a, b) => new Date(b.date) - new Date(a.date));

    if (commentList === null) {
        return;
    }

    let displayedComments = 0;
    
    // Loop through comments
    const displayComment = (comment) => {
        // access properties of comment
        const commentId = comment.comment_id;
        const userId = comment.user_id;
        const username = comment.username;
        const userImage = comment.user_img;
        const dateTxt = comment.date;
        const commentTxt = comment.comment;

        // Create list item for comment
        const commentItem = document.createElement('li');
        commentItem.classList.add('comment_item');
        commentItem.id = 'comment_'+commentId;
        commentList.insertBefore(commentItem, commentList.firstChild);

        // Create comment header
        const commentHeader = document.createElement('div');
        commentHeader.classList.add('comment_header');
        if (userId == appUserId) {
            commentHeader.classList.add('own_comment');
        }
        commentItem.appendChild(commentHeader);

        // Header content: Links
        const commentLinks = document.createElement('p');
        commentLinks.innerHTML = `<a class="user_link" href="kayttaja.php?u=${userId}"><img class="tiny_img" src="${userImage}"> ${username}</a>`;
        commentLinks.classList.add('comment_links');
        commentHeader.appendChild(commentLinks);

        // Header content: Date
        const commentDate = document.createElement('p');
        commentDate.innerHTML = timePassed(dateTxt);
        commentHeader.appendChild(commentDate);

        // Create comment text
        const commentText = document.createElement('p');
        commentText.classList.add('comment_txt');

        // if comment includes @user, linkify user
        const linkingRegex = /@(\w+)\b/g;  // Match @username followed by word boundary
        let matches = commentTxt.matchAll(linkingRegex); // Match variable
        if (matches) { // If matches are found in comment
            linkifyUsers(commentTxt).then(linkedText => {
                commentText.innerHTML = linkedText; // Linkify users
            });
        } else {
            commentText.innerHTML = commentTxt;
        }

        commentItem.appendChild(commentText);

        // Create comment footer
        const commentFooter = document.createElement('div');
        commentFooter.classList.add('comment_footer');
        commentItem.appendChild(commentFooter);

        // Footer content: Reply button
        const replyButton = document.createElement('button');
        replyButton.innerHTML = '<i class="fa fa-reply"></i> Vastaa';
        replyButton.classList.add('func_btn', 'post_btn');
        commentFooter.appendChild(replyButton);
        replyButton.addEventListener('click', () => {
            const commentForm = document.querySelector('.comment_form[data-post-id="'+postId+'"');
            commentForm.querySelector('.comment_input').value = `@${username} `;
            commentForm.querySelector('.comment_input').focus();
        });

        // Footer content: if user is owner of comment, add delete button
        if (userId == appUserId) {
            const deleteButton = document.createElement('button');
            deleteButton.innerHTML = '<i class="fa fa-trash"></i> Poista';
            deleteButton.classList.add('func_btn', 'post_btn');
            commentFooter.appendChild(deleteButton);
            deleteButton.addEventListener('click', () => {
                delete_comment(commentId, postId);
            });
        }  
    };

    // Display 3 newest comments
    for (let i = 0; i < 3 && i < comments.length; i++) {
        displayComment(comments[i]);
        displayedComments++;
    }

    // Add "show more" link if there are more than 3 comments
    if (displayedComments < comments.length) {
        const showMoreLink = document.createElement('a');
        showMoreLink.setAttribute('href', 'javascript:void(0)');
        showMoreLink.innerHTML = '<i class="fa fa-chevron-up"></i> Näytä vanhemmat ('+(comments.length - displayedComments)+')';
        commentList.insertBefore(showMoreLink, commentList.firstChild);

        // When the "show more" button is clicked, display the next 3 comments
        showMoreLink.addEventListener('click', () => {
            for (let i = displayedComments; i < displayedComments + 3 && i < comments.length; i++) {
                displayComment(comments[i]);
            }

            displayedComments += 3;
            showMoreLink.innerHTML = '<i class="fa fa-chevron-up"></i> Näytä vanhemmat ('+(comments.length - displayedComments)+')';
            commentList.insertBefore(showMoreLink, commentList.firstChild);

            // If all comments are displayed, hide the "show more" button
            if (displayedComments >= comments.length) {
                showMoreLink.style.display = 'none';
            }
        });
    }

    // update comment count
    commentButton.innerHTML = `<p>${comments.length}</p> <i style="color: #256599;" class="fa fa-comment"></i> Kommentoi`;
    // if comments includes users id, edit commentButton
    comments.forEach(comment => {
        if (comment.user_id == appUserId) {
            commentButton.innerHTML = `<p>${comments.length}</p> <i style="color: #256599;" class="fa fa-comment"></i> Kommentoi`;
        }
    });
} 

// LINKIFY USERS (mentions)

const linkifyUsers = async (commentTxt) => {

    const linkingRegex = /@(\w+)/g;  // Match @username followed by word boundary
    const linkedNames = []; // Array for matched usernames
    let matches = commentTxt.matchAll(linkingRegex); // Match variable

    for (const match of matches) {
        linkedNames.push(match[1]);  // Add matched usernames to array
    }

    try {
        const response = await fetch('api/users_link.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ names: linkedNames })
        });

        if (!response.ok) {
            throw new Error('Käyttäjien hakeminen epäonnistui.');
        }

        let data;

        try {
            data = await response.json();
        } catch (error) {
            console.error('Error parsing data:', error);
        }

        if (!data.success) {
            return commentTxt;
        }

        if (data.success) {
            let users = data.users;

            if (users.length == 0) {
                return commentTxt;
            }

            let newText = commentTxt;

            for (let i = 0; i < users.length; i++) {
                let user = users[i];
                let userId = user.user_id;
                let username = user.username;
                let regex = new RegExp('@'+username+'\\b', 'g');
                newText = newText.replace(regex, '<a href="kayttaja.php?u='+userId+'">@'+username+'</a> ');
            }

            return newText;
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

// DELETE COMMENT

const delete_comment = async (commentId, postId) => {

    let row = { comment_id: commentId, post_id: postId }
    let commentButton = document.querySelector('.comment_btn[data-post-id="'+postId+'"]');
    const commentList = document.querySelector('.comments_list[data-post-id="'+postId+'"]');

    // Fetch data from PHP API
    try {
        const response = await fetch('api/comment_delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(row)
        });

        // If fetch fails, display error message
        if (!response.ok) {
            postsGetError.style.display = 'block'; // Show error message
            postsGetError.innerHTML = 'Kommentin poisto epäonnistui.';
            throw new Error('Kommentin poisto epäonnistui.');
        }

        let data;

        try {
            data = await response.json();
        } catch (error) {
            console.error('Error parsing data:', error);
        }

        // Access properties of data
        let errors = data.errors;

        // If post creation fails, display error message
        if (!data.success) {
            postsGetError.style.display = 'block'; // Show error message
            postsGetError.innerHTML = data.error;
            errors.forEach(error => {
                postsGetError.innerHTML += error + '<br>';
            });
            return;
        }

        // If post creation is successful, display success message
        if (data.success) {
            const appUserId = data.appUserId;
            commentList.innerHTML = '';
            const comments = data.comments;
            showComments(appUserId, comments, commentList, postId, commentButton); // Call function to show comments
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}


// Call function to display posts
displayPosts();