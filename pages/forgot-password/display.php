<?php $xml = simplexml_load_file("pages/change-password/content.xml"); ?>
<div class="container main">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-header">
                <a href="index.php">Home</a>
                <img src="img/title-icon.png" alt="" />
                <a href="index.php?page=login">Login / Signup</a>
                <img src="img/title-icon.png" alt="" />
                <a href="#">Forgot Password</a>
            </div>
        </div>
    </div>
    
    <!-- main content -->
    <div class="row">
        <!-- your-order -->
        <div class="col-md-6 well">
            <div class="your-info gray-bg-container">
                <h2>Reset Your Password</h2>
                <!-- your-info-registration -->
                <div class="your-info-registration">
                    <form action="" method="post">
                        <div class="registration-row">
                            <label>E-mail Address:</label>
                            <input type="email" name="email" />
                        </div>
                        <div class="clearfix"></div>
                        <a href="index.php?page=login" class="forgot-password">&laquo; back to login</a>
                        <div class="clearfix"></div>
                        <button type="submit" class="btn btn-yellow">Reset</button>
                        
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