
const userMenuBtn = document.getElementById('open_user_menu');
const userMenu = document.getElementById('user_navigation');
const closeUserMenu = document.getElementById('close_user_menu');
const userMenuItems = document.querySelector('.user_links');
const menuOverlay = document.querySelector('.menu_overlay');
const pageMenu = document.querySelector('.page_navbar');

if (userMenuBtn) {
    userMenuBtn.addEventListener('click', () => {
        userMenu.classList.toggle('active');
        menuOverlay.classList.toggle('active');
    });
}

if (closeUserMenu) {
    closeUserMenu.addEventListener('click', () => {
        userMenu.classList.remove('active');
        menuOverlay.classList.remove('active');
    });
}

document.addEventListener('click', (e) => {
    if (!userMenuBtn.contains(e.target) && !userMenu.contains(e.target) && userMenu.classList.contains('active')) {
        userMenu.classList.remove('active');
        menuOverlay.classList.remove('active');
    }
});
