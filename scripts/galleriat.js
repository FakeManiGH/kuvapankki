
// Gallery list container elements
const ownedGalleries = document.getElementById('owned_galleries');
const joinedGalleries = document.getElementById('joined_galleries');

// Gallery list items
const galleryOwned = document.querySelectorAll('.gallery_owned');
const galleryJoined = document.querySelectorAll('.gallery_joined');

// Own galleries sort and filter select elements
const filtersOwned = document.getElementById('filters_owned');
const filterGalleriesOwn = document.getElementById('filter_galleries_owned');
const sortGalleriesOwn = document.getElementById('sort_galleries_owned');

// Joined galleries sort and filter select elements
const filtersJoined = document.getElementById('filters_joined');
const filterGalleriesJoined = document.getElementById('filter_galleries_joined');
const sortGalleriesJoined = document.getElementById('sort_galleries_joined');

// Add event listeners if galleries exist
if (galleryOwned.length > 1) {
    filtersOwned.style.display = 'flex';
    filterGalleriesOwn.addEventListener('change', filterOwnGalleries);
    sortGalleriesOwn.addEventListener('change', sortOwnGalleries);
} else {
    filtersOwned.style.display = 'none';
}

if (galleryJoined.length > 1) {
    filtersJoined.style.display = 'flex';
    filterGalleriesJoined.addEventListener('change', filterJoinedGalleries);
    // sortGalleriesJoined.addEventListener('change', sortJoinedGalleries);
} else {
    filtersJoined.style.display = 'none';
}



// Filter Owned galleries by visibility
function filterOwnGalleries() {
    let visibiltyFilter = filterGalleriesOwn.value;

    galleryOwned.forEach(gallery => {
        let galleryVisibility = gallery.getAttribute('data-visibility');

        if (visibiltyFilter === 'all') {
            gallery.style.display = 'flex';
        } else if (visibiltyFilter === galleryVisibility) {
            gallery.style.display = 'flex';
        } else {
            gallery.style.display = 'none';
        }
    });
}

// Sort Owned galleries
function sortOwnGalleries() {
    let gallerySort = sortGalleriesOwn.value;
    let galleryArray = Array.from(galleryOwned);

    // Sort by name ascending
    if (gallerySort === 'name') {
        galleryArray.sort((a, b) => {
            let nameA = a.getAttribute('data-name').toUpperCase(); // ignore upper and lowercase
            let nameB = b.getAttribute('data-name').toUpperCase(); // ignore upper and lowercase

            if (nameA < nameB) {
                return -1;
            }
            if (nameA > nameB) {
                return 1;
            }
            return 0; // names must be equal
        });

        galleryArray.forEach(gallery => {
            ownedGalleries.appendChild(gallery);
        });
    }

    // Sort by name descending
    if (gallerySort === 'name_desc') {
        galleryArray.sort((a, b) => {
            let nameA = a.getAttribute('data-name').toUpperCase(); // ignore upper and lowercase
            let nameB = b.getAttribute('data-name').toUpperCase(); // ignore upper and lowercase

            if (nameA < nameB) {
                return 1;
            }
            if (nameA > nameB) {
                return -1;
            }
            return 0; // names must be equal
        });

        galleryArray.forEach(gallery => {
            ownedGalleries.appendChild(gallery);
        });
    }

    // Sort by date ascending
    if (gallerySort === 'oldest') {
        galleryArray.sort((a, b) => {
            let dateA = new Date(a.getAttribute('data-date'));
            let dateB = new Date(b.getAttribute('data-date'));

            return dateA - dateB;
        });

        galleryArray.forEach(gallery => {
            ownedGalleries.appendChild(gallery);
        });
    }

    // Sort by date descending
    if (gallerySort === 'newest') {
        galleryArray.sort((a, b) => {
            let dateA = new Date(a.getAttribute('data-date'));
            let dateB = new Date(b.getAttribute('data-date'));

            return dateB - dateA;
        });

        galleryArray.forEach(gallery => {
            ownedGalleries.appendChild(gallery);
        });
    }
}


// Filter Joined galleries by visibility
function filterJoinedGalleries() {
    let visibiltyFilter = filterGalleriesJoined.value;

    galleryJoined.forEach(gallery => {
        let galleryVisibility = gallery.getAttribute('data-visibility');

        if (visibiltyFilter === 'all') {
            gallery.style.display = 'flex';
        } else if (visibiltyFilter === galleryVisibility) {
            gallery.style.display = 'flex';
        } else {
            gallery.style.display = 'none';
        }
    });
}