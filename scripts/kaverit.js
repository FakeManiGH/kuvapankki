
const searchForm = document.querySelector('.page_form');
const userList = document.getElementById('user_list');
const userItem = document.getElementById('user_item');
const searchReset = document.getElementById('search_reset');
const searchInput = document.getElementById('search_input');
const errorText = document.querySelector('.error_msg');

if (searchReset) {
    searchReset.addEventListener('click', () => {
        userList.innerHTML = '';
        searchInput.value = '';
        errorText.innerHTML = '';
        searchReset.style.display = 'none';
    });
}

if (searchForm) {
    searchForm.addEventListener('submit', (e) => {
        if (searchInput.value.trim() === '') {
            e.preventDefault();
            errorText.innerHTML = 'Anna hakusana.';
        }
    });

    searchForm.addEventListener('submit', (e) => {
        if (searchInput.value.trim().length < 3) {
            e.preventDefault();
            errorText.innerHTML = 'Hakusanan tulee olla vähintään 3 merkkiä pitkä.';
        }
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