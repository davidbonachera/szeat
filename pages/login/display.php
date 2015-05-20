<?php $xml = simplexml_load_file("pages/login/content.xml"); ?>
<div class="container main">
    	<div class="row">
        	<div class="col-xs-12">
            	<div class="page-header">
                	<a href="index.php"><?php echo ($xml->$lang->home==""?$xml->en->home:$xml->$lang->home); ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#"><?php echo ($xml->$lang->logsign==""?$xml->en->logsign:$xml->$lang->logsign); ?></a>
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
                        <h2><?php echo ($xml->$lang->exist==""?$xml->en->exist:$xml->$lang->exist); ?></h2>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->email==""?$xml->en->email:$xml->$lang->email); ?>:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="email" name="email" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['email'])) ? $_POST['email']:NULL; ?>" />
                            </div>
                        </div>
                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->passw==""?$xml->en->passw:$xml->$lang->passw); ?>:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="password" name="password" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-8">
                                <a href="index.php?page=forgot-password" class="forgot-password"><?php echo ($xml->$lang->forgot==""?$xml->en->forgot:$xml->$lang->forgot); ?>?</a>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top:1em;">
                            <div class="col-md-offset-4 col-md-1">
                                <button type="submit" class="btn btn-yellow"><?php echo ($xml->$lang->logn==""?$xml->en->logn:$xml->$lang->logn); ?></button>
                            </div>
                        </div>

                    </form>


                </div>

            </div>
            
        	<div class="col-sm-6 myfonts">
                <div class="well">
                    <form action="" method="post" class="form-horizontal"> 
                            
                        <h2><?php echo ($xml->$lang->newm==""?$xml->en->newm:$xml->$lang->newm); ?></h2>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->nem==""?$xml->en->nem:$xml->$lang->nem); ?>:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="name">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->phun==""?$xml->en->phun:$xml->$lang->phun); ?>:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="phone">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->arar==""?$xml->en->arar:$xml->$lang->arar); ?>:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="area" name="area">
                                    <option hidden="hidden"><?php echo ($xml->$lang->select==""?$xml->en->select:$xml->$lang->select); ?></option>
                                    <?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($areas)) { ?>
                                        <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->build==""?$xml->en->build:$xml->$lang->build); ?>:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="building" name="building">
                                    <option hidden="hidden"><?php echo ($xml->$lang->select==""?$xml->en->select:$xml->$lang->select); ?></option>
                                    <?php $cuisines = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                        <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->blok==""?$xml->en->blok:$xml->$lang->blok); ?>:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="apartment">
                            </div>
                        </div>



                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->emem==""?$xml->en->emem:$xml->$lang->emem); ?>:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="email" name="email1">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->renta==""?$xml->en->renta:$xml->$lang->renta); ?>:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="email" name="email2">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->passw==""?$xml->en->passw:$xml->$lang->passw); ?>:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="password" name="password1">
                            </div>
                        </div>

                        <div class="form-group form-horizontal">
                            <label class="control-label col-sm-4"><?php echo ($xml->$lang->reen==""?$xml->en->reen:$xml->$lang->reen); ?>:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="password" name="password2">
                            </div>
                        </div>


                        <div class="col-xs-12">
                            <div class="checkbox">
                                <label><input type="checkbox" name="newsletter" checked="checked" value="1"><?php echo ($xml->$lang->aghee1==""?$xml->en->aghee1:$xml->$lang->aghee1); ?></label>
                            </div>
                        </div>
                    
                        <div class="col-xs-12">
                            <div class="checkbox">
                                <label><input type="checkbox" name="terms" value="1"><?php echo ($xml->$lang->aghee2==""?$xml->en->aghee2:$xml->$lang->aghee2); ?>.</label>
                            </div>
                        </div>

                        <textarea class="notesbox" name="notes" id="notes" style="display:none;"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                        
                        <div class="form-group" style="margin-top:1em;">
                            <div class="col-md-offset-1 col-md-1">
                                <button type="submit" class="btn btn-yellow"><?php echo ($xml->$lang->next==""?$xml->en->next:$xml->$lang->next); ?></button>
                            </div>
                        </div>

                    </form>
                </div>                
            </div>
        	<!-- end of your-info -->
        </div>
    	<!-- end of main content -->
    </div>