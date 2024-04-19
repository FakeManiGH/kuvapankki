
const userMenuBtn = document.getElementById('open_user_menu');
const userMenu = document.getElementById('user_navigation');
const closeUserMenu = document.getElementById('close_user_menu');
const userMenuItems = document.querySelector('.user_links');
const menuOverlay = document.querySelector('.menu_overlay');
const topNavigation = document.querySelector('.top_navi');


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

if (userMenuBtn) {
    document.addEventListener('click', (e) => {
        if (!userMenuBtn.contains(e.target) && !userMenu.contains(e.target) && userMenu.classList.contains('active')) {
            userMenu.classList.remove('active');
            menuOverlay.classList.remove('active');

        }
    });
}


// Muokataan top navigationia kun scrollataan alaspäin
let lastScrollTop = 0;
let scrollDirection = '';

window.addEventListener('scroll', () => {
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScroll > lastScrollTop) {
        scrollDirection = 'down';
    } else {
        scrollDirection = 'up';
    }

    if (scrollDirection === 'down' && currentScroll > 170) {
        topNavigation.classList.add('scrolling');
    } else {
        topNavigation.classList.remove('scrolling');
    }

    // If the navigation is at the top of the viewport and is visible, add the box shadow
    if (currentScroll > 165) {
        topNavigation.classList.add('shadow');
    } else {
        topNavigation.classList.remove('shadow');
    }

    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
}, false);


// CREARTE A NOTIFICATION

const createNotification = async (userId, type) => {};