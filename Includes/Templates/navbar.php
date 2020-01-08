<div class="upper-bar">
    <div class="container">
        <a href="login.php?Action=Login" class="mr-4"><span >Login</span></a>
        <a href="login.php?Action=SignUp"><span >Sign Up</span></a>
    </div>   
</div>

<nav class="navbar navbar-expand-lg ">
    <a class="navbar-brand" href="index.php">Homepage</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto mr-3">
        <?php
            foreach (getCat() as $category){
                echo '<li class="nav-item">';
                echo '<a class="nav-link" href="categories.php?page_ID='. $category['ID'] .
                "&pagename=".str_replace(" ",'-',$category['Name']).
                '">' . $category['Name']. '</a>';
                echo '</li>';
            }
        ?>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo lang_eng('Admin') ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="Members.php?do=Edit&user_ID=<?php echo $_SESSION['ID'] ?>"><?php echo lang_eng('profile') ?></a>
                <a class="dropdown-item" href="#"><?php echo lang_eng('settings') ?></a>
                <a class="dropdown-item" href="Admin/dashboard.php"><?php echo lang_eng('VISIT THE PANEL') ?></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="Logout.php"><?php echo lang_eng('logout') ?></a>
            </div>
        </li>
    </ul>
<!--     <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form> -->
    </div>
</nav>