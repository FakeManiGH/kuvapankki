
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

