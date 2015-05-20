<?php $xml = simplexml_load_file("pages/change-password/content.xml"); ?>
<div class="container main">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-header">
                <a href="index.php"><?php echo ($xml->$lang->home==""?$xml->en->home:$xml->$lang->home); ?></a>
                <img src="img/title-icon.png" alt="" />
                <a href="account-details.php"><?php echo ($xml->$lang->dash==""?$xml->en->dash:$xml->$lang->dash); ?></a>
                <img src="img/title-icon.png" alt="" />
                <a href="#"><?php echo ($xml->$lang->cp==""?$xml->en->cp:$xml->$lang->cp); ?></a>
            </div>
        </div>
    </div>
    
    <!-- main content -->
    <div class="row">
        <!-- your-order -->
        <div class="col-md-6 well">
            <div class="your-info gray-bg-container">
                <h2><?php echo ($xml->$lang->cp==""?$xml->en->cp:$xml->$lang->cp); ?></h2>
                <!-- your-info-registration -->
                <div class="your-info-registration">
                    <form action="" method="post">
                        <div class="registration-row">
                            <label><?php echo ($xml->$lang->curr==""?$xml->en->curr:$xml->$lang->curr); ?>:</label>
                            <input type="password" name="currentPassword" />
                        </div>
                        <div class="registration-row">
                            <label><?php echo ($xml->$lang->np==""?$xml->en->np:$xml->$lang->np); ?>:</label>
                            <input type="password" name="password1" />
                        </div>
                        <div class="registration-row">
                            <label><?php echo ($xml->$lang->rere==""?$xml->en->rere:$xml->$lang->rere); ?>:</label>
                            <input type="password" name="password2" />
                        </div>
                        <div class="clearfix"></div>
                        <button type="submit" class="btn btn-yellow"><?php echo ($xml->$lang->sub==""?$xml->en->sub:$xml->$lang->sub); ?></button>
                        
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
