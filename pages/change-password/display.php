<div class="container main">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-header">
                <a href="index.php">Home</a>
                <img src="img/title-icon.png" alt="" />
                <a href="account-details.php">Customer Dashboard</a>
                <img src="img/title-icon.png" alt="" />
                <a href="#">Change Password</a>
            </div>
        </div>
    </div>
    
    <!-- main content -->
    <div class="row">
        <!-- your-order -->
        <div class="col-md-6 well">
            <div class="your-info gray-bg-container">
                <h2>Change Password</h2>
                <!-- your-info-registration -->
                <div class="your-info-registration">
                    <form action="" method="post">
                        <div class="registration-row">
                            <label>Current Password:</label>
                            <input type="password" name="currentPassword" />
                        </div>
                        <div class="registration-row">
                            <label>New Password:</label>
                            <input type="password" name="password1" />
                        </div>
                        <div class="registration-row">
                            <label>Re-enter New Password:</label>
                            <input type="password" name="password2" />
                        </div>
                        <div class="clearfix"></div>
                        <button type="submit" class="btn btn-yellow">Submit</button>
                        
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
