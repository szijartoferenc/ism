<?php
    $user = $_SESSION['user'];
?>

<div class="dashboardSidebar" id="dashboardSidebar">
            <h3 class="dashboardLogo" id="dashboardLogo">ISM</h3>
            <div class="dashboardSidebarUser">
                <img src="img/users/46.jpg" alt="User image." id="userImage">
                <span><?= $user['first_name']. '  '. $user['last_name']?></span>
            </div>
            <div class="dashboardSidebarMenus">
            <ul class="dashboardMenuLists">
                <!---class="menuActive"-->
                    <li class="liMainMenu">
                        <a href="/dashboard.php"><i class="fa fa-dashboard"></i><span class="menuText">Kezelőfelület</span></a>
                    </li>
                    <li class="liMainMenu">
                        <a href="/report.php"><i class="fa fa-file"></i><span class="menuText">Jelentések</span></a>
                    </li>
                    <li class="liMainMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu"> <!--SubMenu -->
                                    <i class="fa fa-tag showHideSubMenu"></i>
                                    <span class="menuText showHideSubMenu">Termékek</span>
                                    <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                       </a>
                       <ul class="subMenus">
                            <li><a class="subMenuLink" href="./viewproduct.php"><i class="fa fa-circle-o"></i>Termékek megtekintése</a></li>
                            <li><a class="subMenuLink" href="./addproduct.php"><i class="fa fa-circle-o"></i>Termékek hozzáadása</a></li>
                            <li><a class="subMenuLink" href="./orderproduct.php"><i class="fa fa-circle-o"></i>Termékek rendelése</a></li>
                        </ul>
                    </li>
                    <li class="liMainMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu"> <!--SubMenu -->
                                <i class="fa fa-truck showHideSubMenu"></i>
                                <span class="menuText showHideSubMenu">Beszállítók</span>
                                <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                       </a>
                       <ul class="subMenus">
                            <li><a class="subMenuLink" href="./viewsupplier.php"><i class="fa fa-circle-o"></i>Beszállítók megtekintése</a></li>
                            <li><a class="subMenuLink" href="./addsupplier.php"><i class="fa fa-circle-o"></i>Beszállítók hozzáadása</a></li>
                        </ul>
                    </li>
                    <li class="liMainMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu"> <!--SubMenu -->
                                <i class="fa fa-shopping-cart showHideSubMenu"></i>
                                <span class="menuText showHideSubMenu">Megrendelések</span>
                                <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                       </a>
                       <ul class="subMenus">
                            <li><a class="subMenuLink" href="./orderproduct.php"><i class="fa fa-circle-o"></i>Termékek rendelése</a></li>
                            <li><a class="subMenuLink" href="./vieworder.php"><i class="fa fa-circle-o"></i>Rendelések megtekintése</a></li>
                        </ul>
                    </li>
                    <li class="liMainMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu"> <!--SubMenu -->
                            <i class="fa fa-user-plus showHideSubMenu"></i>
                            <span class="menuText showHideSubMenu">Felhasználók</span>
                            <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                        </a>
                        <ul class="subMenus">
                            <li><a class="subMenuLink" href="./viewuser.php"><i class="fa fa-circle-o"></i>Felhasználók megtekintése</a></li>
                            <li><a class="subMenuLink" href="./adduser.php"><i class="fa fa-circle-o"></i>Felhasználók hozzáadása</a></li>
                        </ul>
                    </li>
                   
                </ul>
            </div>
        </div>

        
        