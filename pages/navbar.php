<?php
$user;




?>



<nav class="nav navbar-expand-lg navbar-light bg-white border">
    <div class="container col-lg-9 col-sm-12 col-md-10 d-flex flex-lg-row flex-md-row flex-sm-column justify-content-between">
        <div class="d-flex justify-content-between col-lg-8 col-sm-12">
            <a class="navbar-brand">
                <img src="images/logo1.png" alt="printerflow_logo">
            </a>
            <!--START OF SERACH FORM--->
            <form class="d-flex" id="searchform">
                <input id="search" type="search" placeholder="Looking for answers?..." aria-label="Search" onautocomplete="off">
                <div class="bg-white text-end rounded border shadow py-3 px-4 mt-5" style="display:none; position:absolute;z-index:+99;" id="search_result" data-bs-auto-closes="true">
                <button type="button" class="btn-close" aria-label="Close" id="close_search"></button>
                <div id="sra" class="text-start">
                    <p class="ext-center text-muted">Enter a Keyword</p>
                </div>

                </div>
            </form>
            <!--END OF SERACH FORM--->
        </div>
        <!--START OF Navbar items--->
        <ul class="navbar-nav flex-fill flex-row justify-content-evenly mb-lg-1 mb-sm-0">
            <li class="nav-item">
                <a class="nav-link text-dark" href="#"><i class="bi bi-house-door-fill"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" data-bs-toggle="modal" data-bs-target="#addpost"><i class="bi bi-plus-square-fill"></i></a>
            </li>
            <li class="nav-item">
                <?php
                    if(getUnreadNotificationsCount()>0)
                    {
                
                
                ?>
                <a class="nav-link text-dark position-relative" id="show_not" data-bs-toggle="offcanvas" href="#notification_sidebar" role="button" aria-controls="offcanvasExample"><i class="bi bi-bell-fill"></i>
                    <span class="un-count position-absolute start-10 translate-middle badge p-1 rounded-pill bg-danger">
                        <small><?php//some php   ?></small>
                    </span>
                </a>
                <?php
                    }
                else{
                ?>
                    <a href="#message_sidebar" class="nav-link" data-bs-toggle="offcanvas"><i class="bi bi-chat-right-dots-fill"></i></a>
                <?php
                }
                ?>
                
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" data-bs-toggle="modal" data-bs-target="#addpost">
                    <i class="bi bi-plus-square-fill"></i>
                    <span class="un-count position-absolute start-10 translate-middle badge p-1 rounded-pill bg-danger" id="msgcounter"></span>
                </a>
            </li>
            <li class="nav-item dropdown dropstart">
                <a href="#" class="nav-link" id="navbarDropdown" role="button"><img src="/images/profile/<?php=$user['profile_pic']?>" alt="profile_pic" height="30" width="30" class="rounded-circle border"></a>
                <ul class="dropdown-menu position-absolute top-100 end-50" aria-labelledby="navbarDropdown">
                    <li>
                        <a href="?u=<?=$user['username']?>" class="dropdown-item"><i class="bi bi-person"></i>My Profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="?editprofile"><i class="bi bi-pencil-square"></i>Edit Profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="?editprofile"><i class="bi bi-gear-fill"></i>Account Settings</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="php/actions.php?logout"><i class="bi bi-box-arrow-in-left"></i>LOGOUT</a>
                    </li>
                </ul> 
            </li>
        </ul>
        <!--END OF Navbar items--->
    </div>
</nav>