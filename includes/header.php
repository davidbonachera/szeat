<div class="header">
    <div class="container">
        <div class="row">
            <div class="span10 <?php echo (isset($_SESSION['user']['id'])) ? 'w500':NULL; ?>">
                <h1><a href="index.php"><img src="img/logo_new2.png" alt="" /></a></h1>
            </div>
            <?php if (isset($_SESSION['user']['id'])) { ?>
                <div class="span3 pull-right log-out w400">
                    <span>Welcome, <?php echo getData('users','name',$_SESSION['user']['id']); ?></span>
                    <p><a href="account-details.php" style="color:#343333;">My Account</a> &nbsp;&nbsp; <a href="logout.php" style="color:#343333;">Log Out</a></p>
                </div>
            <?php } else { ?>
                <div class="span2 pull-right log-in">
                    <a href="login.php">Log In</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>