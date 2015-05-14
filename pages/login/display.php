<div class="container main">
    	<div class="row">
        	<div class="col-xs-12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Login / Signup</a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	 <?php if (isset($_SESSION['msg'])) { ?>
                <div class="clearfix"></div>
                <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>" style="text-align:center;">
                    <?php echo $_SESSION['msg']; ?>
                </div>
                <div class="clearfix">&nbsp;</div>
            <?php } // isset $_SESSION['msg'] ?>
        	<!-- your-order -->
        	<div class="col-sm-6">
            	<div class="your-info gray-bg-container">
                	<h2>Name and Address</h2>
                    <!-- your-info-registration -->
                    <div class="your-info-registration">
                        <form action="" method="post">
                        	<h3>Existing Member</h3>
                            <div class="registration-row">
                            	<label>E-mail Address:</label>
                                <input type="email" name="email" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['email'])) ? $_POST['email']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Password:</label>
                                <input type="password" name="password" autocomplete="off" />
                            </div>
                            <a href="index.php?page=forgot-password" class="forgot-password">Forgotten your password?</a>
                            <button type="submit" class="btn btn-yellow">Login</button>

                        </form>
                	</div>
                    <!-- end of your-info-registration -->
                </div>
            </div>
        	<!--end of your-order -->
            
        	<!-- your-info -->
        	<div class="col-sm-6">
            	<div class="your-info gray-bg-container">
                    <div class="your-info-registration">
                        <form action="" method="post">
                        	<h3>New Member</h3>
                            <div class="registration-row">
                            	<label>Name:</label>
                                <input type="text" name="name" id="name" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['name'])) ? $_POST['name']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Phone:</label>
                                <input type="text" name="phone" id="phone" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['phone'])) ? $_POST['phone']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Your Area:</label>
                                <select name="area" id="area">
                                	<option hidden="hidden" value="">Select</option>
									<?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($areas)) { ?>
                                        <option value="<?php echo $r['id']; ?>" <?php echo (isset($_POST) && $_SESSION['error']==true && $_POST['area']==$r['id']) ? 'selected':NULL; ?>>
											<?php __($r['title']); ?>
                                        </option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </div>
                            <div class="registration-row">
                            	<label>Your Building:</label>
                                <select name="building" id="building">
                                	<option hidden="hidden" value="">Select</option>
									<?php $cuisines = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                        <option value="<?php echo $r['id']; ?>" <?php echo (isset($_POST) && $_SESSION['error']==true && $_POST['building']==$r['id']) ? 'selected':NULL; ?>>
											<?php __($r['title']); ?>
                                        </option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </div>
                            <div class="registration-row">
                            	<label>Your Block/ Apt #:</label>
                                <input type="text" name="apartment" id="apartment" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['apartment'])) ? $_POST['apartment']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>E-mail address:</label>
                                <input type="email" name="email1" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['email1'])) ? $_POST['email1']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Re-enter Email addres:</label>
                                <input type="email" name="email2" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['email2'])) ? $_POST['email2']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Password:</label>
                                <input type="password" name="password1" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['password1'])) ? $_POST['password1']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Re-enter Password:</label>
                                <input type="password" name="password2" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['password2'])) ? $_POST['password2']:NULL; ?>" />
                            </div>
                            <div class="registration-row checkbox-row">
                            	<input type="checkbox" name="newsletter" value="1" <?php echo ($_POST) ? ($_POST['newsletter']==1 ? 'checked':NULL):'checked'; ?> />
                                <p>I would like to sign up to the SHENZHEN EAT newsletter (via email and mobile) for the chance to win free takeaway for a year.</p>
                           	</div> 
                            <div class="registration-row checkbox-row">
                            	<input type="checkbox" name="terms" value="1" <?php echo (isset($_POST) && $_SESSION['error']==true && $_POST['terms']==1) ? 'checked':NULL; ?> />
                                <p>I accept the <a href="#">terms and conditions</a>, <a href="#">privacy policy</a> & <a href="#">cookies policy</a></p>
                           	</div> 
                            <button name="submit" type="submit" class="btn btn-yellow">Next</button>
                        </form>
                	</div>
                    <!-- end of your-info-registration -->
                </div>
            </div>
        	<!-- end of your-info -->
        </div>
    	<!-- end of main content -->
    </div>