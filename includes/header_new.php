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
                        <a class="navbar-brand" href="/"><img src="img/logo-dash.png" alt="ShenzhenEat" style="max-width:140px; margin-top: -7px;"/></a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <div class="navbar-right">

                            <?php if (isset($_SESSION['user']['id'])) { ?>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo getData('users','name',$_SESSION['user']['id']); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="account-details.php" style="color:#343333;">My Account</a></li>
                                    <li class="divider"></li>
                                    <li><a href="logout.php" style="color:#343333;">Log Out</a></li>
                                </ul>                      
                            <?php } else { ?>
                                <a href="#">Sign Up</a>
                                <a class="btn btn-custom navbar-btn" href="login.php" role="button"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Log In</a>                                
                            <?php } ?>
                            <a href=""><img src="img/uk-flag.png" style="max-height:44px;" /></a>
                            <a href=""><img src="img/china-flag.png" style="max-height:44px;" /></a>  
                        </div>              
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>
    </div>