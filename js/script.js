let sideBarIsOpen = true;

toggleBtn.addEventListener('click', (event)=>{
    event.preventDefault();

    if (sideBarIsOpen) {
        dashboardSidebar.style.width = '10%';
        dashboardSidebar.style.transition = '0.3s all';
        dashboardContentContainer.style.width = '90%';
        dashboardLogo.style.fontSize='60px';
        userImage.style.width='60px';

        menuIcons = document.getElementsByClassName('menuText');
        for(let i = 0; i < menuIcons.length; i++){
            menuIcons[i].style.display = 'none';
        }

        document.getElementsByClassName('dashboardMenuLists')[0].style.textAlign = 'center';
        sideBarIsOpen = false;
    } else{
        dashboardSidebar.style.width = '20%';
        dashboardContentContainer.style.width = '80%';
        dashboardLogo.style.fontSize='80px';
        userImage.style.width='80px';

        menuIcons = document.getElementsByClassName('menuText');
        for(let i = 0; i < menuIcons.length; i++){
            menuIcons[i].style.display = 'inline-block';
        }

        document.getElementsByClassName('dashboardMenuLists')[0].style.textAlign = 'left';
        sideBarIsOpen = true;
    }
    
});

//Submenu showing / hide function

document.addEventListener('click', function(e) {
  let clickedEl = e.target;

  if (clickedEl.classList.contains('showHideSubMenu')) {
    let subMenu = clickedEl.closest('li').querySelector('.subMenus');
    let mainMenuIcon = clickedEl.closest('li').querySelector('.mainMenuIconArrow');

    // close and open submenus
    let subMenus = document.querySelectorAll('.subMenus');
    subMenus.forEach((sub) => {
      if (subMenu !== sub) sub.style.display = 'none';
    });

    // calling the function to show/hide the submenu
    showHideSubMenu(subMenu, mainMenuIcon);
  }
});

function showHideSubMenu(subMenu, mainMenuIcon) {
  // checking if there is submenu
  if (subMenu != null) {
    if (subMenu.style.display === 'block') {
      subMenu.style.display = 'none';
      mainMenuIcon.classList.remove('fa-angle-down');
      mainMenuIcon.classList.add('fa-angle-left');
    } else {
      subMenu.style.display = 'block';
      mainMenuIcon.classList.remove('fa-angle-left');
      mainMenuIcon.classList.add('fa-angle-down');
    }
  }
}

// Active menu hiding/showing and adding active class
let pathArray = window.location.pathname.split('/');
let curFile = pathArray[pathArray.length - 1];

let curNav = document.querySelector('a[href=" ./' + curFile + '"]');
if (curNav) {
  curNav.classList.add('subMenuActive');

  let mainNav = curNav.closest('li.liMainMenu');
  mainNav.style.background = '#f685a1';

  let subMenu = curNav.closest('.subMenus');
  let mainMenuIcon = mainNav.querySelector('i.mainMenuIconArrow');

  // calling the function to show/hide the submenu
  showHideSubMenu(subMenu, mainMenuIcon);
}
  




//PO

