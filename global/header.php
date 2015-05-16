<?php $xml = simplexml_load_file("global/global_content/header.xml"); ?>
</head>
<body>

    <div class="header">
        <div class="header">
           <nav class="navbar navbar-default"> 
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/"><img id="headerlogo" src="img/logo-dash.png" alt="ShenzhenEat"/></a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <div class="navbar-right">

                            <?php if (isset($_SESSION['user']['id'])) { ?>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo getData('users','name',$_SESSION['user']['id']); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="index.php?page=account-details" style="color:#343333;"><?php echo ($xml->$lang->myacc == "" ? $xml->en->myacc : $xml->$lang->myacc); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="logout.php" style="color:#343333;"><?php echo ($xml->$lang->logout == "" ? $xml->en->logout : $xml->$lang->logout); ?></a></li>
                                </ul>                      
                            <?php } else { ?>
                                <a href="login.php"><?php echo ($xml->$lang->signup == "" ? $xml->en->signup : $xml->$lang->signup); ?></a>
                                <a class="btn btn-custom navbar-btn" href="login.php" role="button"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo ($xml->$lang->login == "" ? $xml->en->login : $xml->$lang->login); ?></a>                                
                            <?php } ?>
                            <a href="index.php?<?php echo 'page='.$page.'&';?>lang=en"><img src="img/uk-flag.png" style="max-height:44px;" /></a>
                            <a href="index.php?<?php echo 'page='.$page.'&';?>lang=cn"><img src="img/china-flag.png" style="max-height:44px;" /></a>  
                        </div>              
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>
    </div>