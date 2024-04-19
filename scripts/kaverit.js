
// USER SEARCH
const searchForm = document.getElementById('user_search_form');
const searchInput = document.getElementById('search_input');
const searchResults = document.getElementById('search_results');
const searchReset = document.getElementById('search_reset');
const searchError = document.getElementById('search_error');


// Search API (REST)
if (searchForm) {
    searchForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Clear previous search results (if any)
        searchResults.innerHTML = '';

        // Clear previous error messages (if any)
        searchError.style.display = 'none';

        // Create new FormData object from the form
        let formData = new FormData(searchForm);

        // Validate search input (min 3 characters)
        let searchValue = searchInput.value.trim();
        if (searchValue.length < 3) {
            searchError.style.display = 'block';
            searchError.innerHTML = 'Hakusanan tulee olla vähintään 3 merkkiä pitkä.';
            setTimeout(() => {
                searchError.style.display = 'none';
            }, 3000);
            return;
        }

        // Fetch data from PHP API
        try {
            const response = await fetch('api/user_search.php', {
                method: 'POST',
                body: formData
            });

            // If fetch fails, display error message
            if (!response.ok) {
                searchError.style.display = 'block';
                searchError.innerHTML = 'Haku epäonnistui, yritä uudelleen.';
                throw new Error('Haku epäonnistui, yritä uudelleen.');
            }

            let data;

            try {
                data = await response.json();
            } catch (error) {
                console.error('Error parsing data:', error);
            }

            // If no users found, display error message
            if (data.users.length < 1) {
                searchError.style.display = 'block';
                searchError.innerHTML = 'Ei hakutuloksia.';
                return;
            }

            // Display search results
            data.users.forEach(user => {
                searchResults.innerHTML += `
                    <li class="result_user">
                        <a class="func_btn" href="kayttaja.php?u=${user.user_id}"><i class="fa fa-user"></i> ${user.username}</a>
                        <a href="javascript:void(0);" data-id="${user.user_id}"><i class="fa fa-plus"></i> Lisää kaveriksi</button>
                    </li>
                `;
            });

        
        } catch (error) {
            console.error('Error fetching data:', error);
        }

        // Reset search form
        searchReset.addEventListener('click', () => {
            searchResults.innerHTML = '';
            searchForm.reset();
        });
    });
}




// Friend list filtering

const filterInput = document.getElementById('filter_friends');
const friendList = document.getElementById('friend_list');
const friends = document.querySelectorAll('.friend');

if (filterInput) {
    filterInput.addEventListener('input', filterFriends);
}

function filterFriends() {
    let filterValue = filterInput.value.toUpperCase();
    let friendsArray = Array.from(friends);

    friendsArray.forEach(friend => {
        let friendName = friend.getAttribute('data-name').toUpperCase();

        if (filterValue.length <= 1) {
            friend.style.display = 'flex';
            return;
        }

        if (friendName.includes(filterValue)) {
            friend.style.display = 'flex';
        } else {
            friend.style.display = 'none';
        }
    });
}

// Friend list sorting

const sortFriends = document.getElementById('sort_friends');

if (sortFriends) {
    sortFriends.addEventListener('change', sortFriendList);
}

function sortFriendList() {
    let sortValue = sortFriends.value;
    let friendsArray = Array.from(friends);

    if (sortValue === 'az') {
        friendsArray.sort((a, b) => {
            let nameA = a.getAttribute('data-name').toUpperCase();
            let nameB = b.getAttribute('data-name').toUpperCase();

            if (nameA < nameB) {
                return -1;
            }
            if (nameA > nameB) {
                return 1;
            }
            return 0;
        });
    } else if (sortValue === 'za') {
        friendsArray.sort((a, b) => {
            let nameA = a.getAttribute('data-name').toUpperCase();
            let nameB = b.getAttribute('data-name').toUpperCase();

            if (nameA > nameB) {
                return -1;
            }
            if (nameA < nameB) {
                return 1;
            }
            return 0;
        });
    }

    friendsArray.forEach(friend => {
        friendList.appendChild(friend);
    });
}