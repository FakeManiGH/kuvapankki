document.addEventListener('DOMContentLoaded', (event) => {
    const userMenuBtn = document.getElementById('open_user_menu');
    const userMenu = document.getElementById('user_navigation');
    const closeUserMenu = document.getElementById('close_user_menu');
    const userMenuItems = document.querySelector('.user_links');

    if (userMenuBtn) {
        userMenuBtn.addEventListener('click', () => {
            userMenu.classList.toggle('active');
        });
    }

    if (closeUserMenu) {
        closeUserMenu.addEventListener('click', () => {
            userMenu.classList.remove('active');
        });
    }

    document.addEventListener('click', (e) => {
        if (!userMenuBtn.contains(e.target) && !userMenu.contains(e.target) && userMenu.classList.contains('active')) {
            userMenu.classList.remove('active');
        }
    });
});

