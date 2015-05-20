<?php $xml = simplexml_load_file("pages/forgot-password/content.xml"); ?>
<div class="container main">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-header">
                <a href="index.php"><?php echo ($xml->$lang->home==""?$xml->en->home:$xml->$lang->home); ?></a>
                <img src="img/title-icon.png" alt="" />
                <a href="index.php?page=login"><?php echo ($xml->$lang->logn==""?$xml->en->logn:$xml->$lang->logn); ?></a>
                <img src="img/title-icon.png" alt="" />
                <a href="#"><?php echo ($xml->$lang->ferg==""?$xml->en->ferg:$xml->$lang->ferg); ?></a>
            </div>
        </div>
    </div>
    
    <!-- main content -->
    <div class="row">
        <!-- your-order -->
        <div class="col-md-6 well myfonts">
            
                
                <h2><?php echo ($xml->$lang->reset==""?$xml->en->reset:$xml->$lang->reset); ?></h2>
                
                <form action="" method="post" class="form form-horizontal">
                    
                    <div class="form-group form-horizontal">
                        <label class="control-label col-sm-4"><?php echo ($xml->$lang->email==""?$xml->en->email:$xml->$lang->email); ?>:</label>
                        <div class="col-sm-8">
                            <input type="email" name="email" class="form-control" />
                        </div>        
                    </div>

                    <div class="clearfix"></div>

                    <a href="index.php?page=login" class="forgot-password">&laquo; <?php echo ($xml->$lang->bak==""?$xml->en->bak:$xml->$lang->bak); ?></a>
                    
                    <div class="clearfix"></div>
                    
                    <button type="submit" class="btn btn-yellow"><?php echo ($xml->$lang->reset==""?$xml->en->reset:$xml->$lang->reset); ?></button>
                    
                    <div class="clearfix">&nbsp;</div>
                    <?php if (isset($_SESSION['msg'])) { ?>
                        <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>">
                            <br /><?php echo $_SESSION['msg']; ?>
                        </div>
                    <?php } // isset $_SESSION['msg'] ?>
                </form>
                
                
            
        </div>
        <!--end of your-order -->
        
    </div>
    <!-- end of main content -->
</div>
<?php if (isset($_SESSION['error'])) unset($_SESSION['error'],$_SESSION['msg']); ?>