<div class="container main">
    <div class="row">
        <div class="span12">
            <div class="page-header">
                <a href="index.php">Home</a>
                <img src="img/title-icon.png" alt="" />
                <a href="login.php">Login / Signup</a>
                <img src="img/title-icon.png" alt="" />
                <a href="#">Reset Password</a>
            </div>
        </div>
    </div>
    
    <!-- main content -->
    <div class="row">
        <!-- your-order -->
        <div class="span6">
            <div class="your-info gray-bg-container">
                <h2>Dear, <?php echo $check['name']; ?></h2>
                <!-- your-info-registration -->
                <div class="your-info-registration">
                    <h3>Reset Your Password</h3>
                    <form action="reset-password.php?x=<?php echo $db->escape($_GET['x']); ?>" method="post">
                        <div class="registration-row">
                            <label>Password:</label>
                            <input type="password" name="password1" />
                        </div>
                        <div class="registration-row">
                            <label>Re-enter Password:</label>
                            <input type="password" name="password2" />
                        </div>
                        <div class="clearfix"></div>
                        <a href="login.php" class="forgot-password">&laquo; back to login</a>
                        <div class="clearfix"></div>
                        <button type="submit" class="yellow-button">Submit</button>
                        
                        <div class="clearfix">&nbsp;</div>
                        <?php if (isset($_SESSION['msg'])) { ?>
                            <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>">
                                <br /><?php echo $_SESSION['msg']; ?>
                            </div>
                        <?php } // isset $_SESSION['msg'] ?>
                    </form>
                </div>
                <!-- end of your-info-registration -->
            </div>
        </div>
        <!--end of your-order -->
        
    </div>
    <!-- end of main content -->
</div>
<?php if (isset($_SESSION['error'])) unset($_SESSION['error'],$_SESSION['msg']); ?>