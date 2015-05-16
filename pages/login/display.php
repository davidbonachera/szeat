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
        	<div class="col-sm-6 myfonts">

                <div class="well">

                    <form action="" method="post" class="form-horizontal">
                        <h2>Existing Member</h2>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">E-mail Address:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="email" name="email" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['email'])) ? $_POST['email']:NULL; ?>" />
                            </div>
                        </div>
                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Password:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="password" name="password" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-8">
                                <a href="index.php?page=forgot-password" class="forgot-password">Forgotten your password?</a>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top:1em;">
                            <div class="col-md-offset-4 col-md-1">
                                <button type="submit" class="btn btn-yellow">Login</button>
                            </div>
                        </div>

                    </form>


                </div>

            </div>
            
        	<div class="col-sm-6 myfonts">
                <div class="well">
                    <form action="" method="post" class="form-horizontal"> 
                            
                        <h2>New Member</h2>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Name:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="name">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Phone:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="phone">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Your Area:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="area" name="area">
                                    <option hidden="hidden">Select</option>
                                    <?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($areas)) { ?>
                                        <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Your Building:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="building" name="building">
                                    <option hidden="hidden">Select</option>
                                    <?php $cuisines = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                        <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Block / Apt:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="apartment">
                            </div>
                        </div>



                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Email address:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="email" name="email1">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Re-enter email address:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="email" name="email2">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Password:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="password" name="password1">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4">Re-enter password:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="password" name="password2">
                            </div>
                        </div>


                        <div class="col-xs-12">
                            <div class="checkbox">
                                <label><input type="checkbox" name="newsletter" checked="checked" value="1">I would like to sign up to the SHENZHEN EAT newsletter (via email and mobile) for the chance to win free takeaway for a year.</label>
                            </div>
                        </div>
                    
                        <div class="col-xs-12">
                            <div class="checkbox">
                                <label><input type="checkbox" name="terms" value="1">I accept the <a href="#">terms and conditions</a>, <a href="#">privacy policy</a> &amp; <a href="#">cookies policy</a>.</label>
                            </div>
                        </div>

                        <textarea class="notesbox" name="notes" id="notes" style="display:none;"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                        
                        <div class="form-group" style="margin-top:1em;">
                            <div class="col-md-offset-1 col-md-1">
                                <button type="submit" class="btn btn-yellow">Next</button>
                            </div>
                        </div>

                    </form>
                </div>                
            </div>
        	<!-- end of your-info -->
        </div>
    	<!-- end of main content -->
    </div>