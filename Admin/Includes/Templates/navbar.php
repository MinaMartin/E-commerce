
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#"><?php echo lang_eng('e-comerce') ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="Categories.php"><?php echo lang_eng('CATOGORIES') ?> </a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="members.php"><?php echo lang_eng('MEMBERS') ?>  </a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="comments.php"><?php echo lang_eng('COMMENTS') ?>  </a>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo lang_eng('Admin') ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../index.php"><?php echo lang_eng('VISIT SHOP'); ?></a>
            <a class="dropdown-item" href="Members.php?do=Edit&user_ID=<?php echo $_SESSION['ID'] ?>"><?php echo lang_eng('profile') ?></a>
            <a class="dropdown-item" href="#"><?php echo lang_eng('settings') ?></a>
            
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="Logout.php"><?php echo lang_eng('logout') ?></a>
        </div>
        </li>
        <li class="nav-item">
        <a class="nav-link " href="items.php" tabindex="-1" ><?php echo lang_eng('ITEMS') ?></a>
        
        </li>
    </ul>
<!--     <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form> -->
    </div>
</nav>