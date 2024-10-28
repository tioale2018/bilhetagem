<?php include('./inc/head.php') ?>
</head>
<body class="theme-black">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="assets/images/logo.svg" width="48" height="48" alt="Alpino"></div>
        <p>Please wait...</p>        
    </div>
</div>
<div class="overlay_menu">
    <button class="btn btn-primary btn-icon btn-icon-mini btn-round"><i class="zmdi zmdi-close"></i></button>
    <div class="container">        
        <div class="row clearfix">
            <div class="card">
                <div class="body">
                    <div class="input-group m-b-0">                
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-addon">
                            <i class="zmdi zmdi-search"></i>
                        </span>
                    </div>
                </div>
            </div>         
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="social">
                    <a class="icon" href="https://www.facebook.com/thememakkerteam" target="_blank"><i class="zmdi zmdi-facebook"></i></a>
                    <a class="icon" href="https://www.behance.net/thememakker" target="_blank"><i class="zmdi zmdi-behance"></i></a>
                    <a class="icon" href="#"><i class="zmdi zmdi-twitter"></i></a>
                    <a class="icon" href="#"><i class="zmdi zmdi-linkedin"></i></a>                    
                    <p>Coded by WrapTheme<br> Designed by <a href="http://thememakker.com/" target="_blank">thememakker.com</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="overlay"></div><!-- Overlay For Sidebars -->

<?php include('./inc/menu_topo.php') ?>
<?php include('./inc/menu_principal.php') ?>
<?php include('./inc/menu_lateral.php') ?>

<!-- Main Content -->
<section class="content home">
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Dashboard</h2>                    
                </div>            
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <ul class="breadcrumb float-md-right padding-0">
                        <li class="breadcrumb-item"><a href="index.php"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-12 text-center">
                <div class="card tasks_report">
                    <div class="body">
                        <input type="text" class="knob" value="66" data-width="90" data-height="90" data-thickness="0.1" data-fgColor="#26dad2" readonly>                        
                        <h6 class="m-t-20">Satisfaction Rate</h6>
                        <p class="displayblock m-b-0">47% Average <i class="zmdi zmdi-trending-up"></i></p>                        
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 text-center">
                <div class="card tasks_report">
                    <div class="body">
                        <input type="text" class="knob dial2" value="26" data-width="90" data-height="90" data-thickness="0.1" data-fgColor="#7b69ec" readonly>
                        <h6 class="m-t-20">Project Panding</h6>
                        <p class="displayblock m-b-0">13% Average <i class="zmdi zmdi-trending-down"></i></p>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 text-center">
                <div class="card tasks_report">
                    <div class="body">
                        <input type="text" class="knob dial3" value="76" data-width="90" data-height="90" data-thickness="0.1" data-fgColor="#f9bd53" readonly>
                        <h6 class="m-t-20">Productivity Goal</h6>
                        <p class="displayblock m-b-0">75% Average <i class="zmdi zmdi-trending-up"></i></p>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 text-center">
                <div class="card tasks_report">
                    <div class="body">
                        <input type="text" class="knob dial4" value="88" data-width="90" data-height="90" data-thickness="0.1" data-fgColor="#00adef" readonly>
                        <h6 class="m-t-20">Total Revenue</h6>
                        <p class="displayblock m-b-0">54% Average <i class="zmdi zmdi-trending-up"></i></p>
                        
                    </div>
                </div>
            </div>            
        </div>
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h5>Project A</h5>
                        <div class="row">                            
                            <div class="col-md-3 col-4">
                                <span>Overall<span>
                                <h6 class="col-indigo">70.40%</h6>
                            </div>
                            <div class="col-md-3 col-4">
                                <span>Montly</span>
                                <h6 class="col-indigo">25.80%</h6>
                            </div>
                            <div class="col-md-3 col-4">
                                <span>Day</span>
                                <h6 class="col-indigo">12.50%</h6>
                            </div>
                        </div>
                        <div class="sparkline" data-type="line" data-spot-Radius="3" data-highlight-Spot-Color="rgb(63, 81, 181)" data-highlight-Line-Color="#222"
                            data-min-Spot-Color="rgb(233, 30, 99)" data-max-Spot-Color="rgb(63, 81, 181)" data-spot-Color="rgb(63, 81, 181, 0.7)"
                            data-offset="90" data-width="100%" data-height="50px" data-line-Width="1" data-line-Color="#3F51B5"
                            data-fill-Color="#7b84b8"> 6,1,3,3,6,3,2,2,8,2 </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                        <h5>Project B</h5>
                        <div class="row">                            
                            <div class="col-md-3 col-4">
                                <span>Overall<span>
                                <h6 class="col-red">70.40%</h6>
                            </div>
                            <div class="col-md-3 col-4">
                                <span>Montly</span>
                                <h6 class="col-red">25.80%</h6>
                            </div>
                            <div class="col-md-3 col-4">
                                <span>Day</span>
                                <h6 class="col-red">12.50%</h6>
                            </div>
                        </div>
                        <div class="sparkline" data-type="line" data-spot-Radius="2" data-highlight-Spot-Color="rgb(233, 30, 99)" data-highlight-Line-Color="#222"
                            data-min-Spot-Color="rgb(233, 30, 99)" data-max-Spot-Color="rgb(96, 125, 139)" data-spot-Color="rgb(96, 125, 139, 0.7)"
                            data-offset="90" data-width="100%" data-height="50px" data-line-Width="1" data-line-Color="#ec3b57"
                            data-fill-Color="#eb94a2"> 6,4,7,8,4,3,2,2,5,6,7,4,1,5,7,9,9,8,7,6 </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="body">
                       <h5>Project C</h5>
                        <div class="row">                            
                            <div class="col-md-3 col-4">
                                <span>Overall<span>
                                <h6 class="col-green">70.40%</h6>
                            </div>
                            <div class="col-md-3 col-4">
                                <span>Montly</span>
                                <h6 class="col-green">25.80%</h6>
                            </div>
                            <div class="col-md-3 col-4">
                                <span>Day</span>
                                <h6 class="col-green">12.50%</h6>
                            </div>
                        </div>
                        <div class="sparkline" data-type="line" data-spot-Radius="3" data-highlight-Spot-Color="rgb(233, 30, 99)" data-highlight-Line-Color="#222"
                            data-min-Spot-Color="rgb(233, 30, 99)" data-max-Spot-Color="rgb(120, 184, 62)" data-spot-Color="rgb(120, 184, 62, 0.7)"
                            data-offset="90" data-width="100%" data-height="50px" data-line-Width="1" data-line-Color="#50d38a)"
                            data-fill-Color="#94d3b0"> 6,4,7,6,9,3,3,5,7,4,2,3,7,6 </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Monthly</strong> Earnings</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                    <li><a href="javascript:void(0);" class="boxs-close">Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <canvas id="line_chart" class="m-b-20" height="150"></canvas>
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="margin-0">5,124</h4>
                                <p>Marketplace</p>
                            </div>
                            <div class="col-4">
                                <h4 class="margin-0">349</h4>
                                <p>Last week</p>
                            </div>
                            <div class="col-4">
                                <h4 class="margin-0">821</h4>
                                <p>Last Month</p>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Revenue</strong></h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                    <li><a href="javascript:void(0);" class="boxs-close">Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <canvas id="bar_chart" class="m-b-20" height="150"></canvas>
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="margin-0">5,124</h4>
                                <p>Marketplace</p>
                            </div>
                            <div class="col-4">
                                <h4 class="margin-0">349</h4>
                                <p>Last week</p>
                            </div>
                            <div class="col-4">
                                <h4 class="margin-0">821</h4>
                                <p>Last Month</p>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>            
        </div>
        <div class="row clearfix">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Earning</strong> Report</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                    <li><a role="button" class="boxs-close">Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body m-b-10 bg-dark">
                        <div class="row">
                            <div class="col-6">
                                <small>Total Earning</small>
                                <h4 class="text-success m-b-0 m-t-0">$7,171</h4>
                                <h6 class="m-b-0 m-t-0">March 2018</h6>
                            </div>
                            <div class="col-6 text-right">
                                <div class="sparkline m-t-10" data-type="bar" data-width="97%" data-height="50px" data-bar-Width="2" data-bar-Spacing="7" data-bar-Color="#18ce0f ">2,5,6,3,4,5,5,6,2,1</div>                                
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive earning-report">
                            <table class="table m-b-0 table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="2">User Name</th>
                                        <th>Priority</th>
                                        <th>Earnings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:60px;"><span class="rounded"><img src="assets/images/xs/avatar1.jpg" alt="user" width="50"></span></td>
                                        <td>
                                            <h6>John Smith</h6><small class="text-muted">UI UX Designer</small></td>
                                        <td><span class="badge badge-success">Low</span></td>
                                        <td>$1.9K</td>
                                    </tr>
                                    <tr class="active">
                                        <td><span class="rounded"><img src="assets/images/xs/avatar2.jpg" alt="user" width="50"></span></td>
                                        <td>
                                            <h6>Hossein Shams</h6><small class="text-muted">Project Manager</small></td>
                                        <td><span class="badge badge-info">Medium</span></td>
                                        <td>$2.9K</td>
                                    </tr>
                                    <tr>
                                        <td><span class="round round-success"><img src="assets/images/xs/avatar3.jpg" alt="user" width="50"></span></td>
                                        <td>
                                            <h6>Maryam Amiri</h6><small class="text-muted">Angular Developer</small></td>
                                        <td><span class="badge badge-primary">High</span></td>
                                        <td>$32.9K</td>
                                    </tr>
                                    <tr>
                                        <td><span class="round round-primary"><img src="assets/images/xs/avatar4.jpg" alt="user" width="50"></span></td>
                                        <td>
                                            <h6>Tim Hank</h6><small class="text-muted">Frontend</small></td>
                                        <td><span class="badge badge-danger">Low</span></td>
                                        <td>$11.9K</td>
                                    </tr>
                                    <tr>
                                        <td><span class="round round-warning"><img src="assets/images/xs/avatar5.jpg" alt="user" width="50"></span></td>
                                        <td>
                                            <h6>Fidel Tonn</h6><small class="text-muted">Content Writer</small></td>
                                        <td><span class="badge badge-warning">High</span></td>
                                        <td>$2.5K</td>
                                    </tr>
                                    <tr>
                                        <td><span class="round round-danger"><img src="assets/images/xs/avatar6.jpg" alt="user" width="50"></span></td>
                                        <td>
                                            <h6>Frank Camly</h6><small class="text-muted">Graphic Design</small></td>
                                        <td><span class="badge badge-info">High</span></td>
                                        <td>$12.7K</td>
                                    </tr>
                                    <tr>
                                        <td><span class="round round-primary"><img src="assets/images/xs/avatar4.jpg" alt="user" width="50"></span></td>
                                        <td>
                                            <h6>Tim Hank</h6><small class="text-muted">Frontend</small></td>
                                        <td><span class="badge badge-danger">Low</span></td>
                                        <td>$11.9K</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>User</strong> Activities</h2>
                    </div>
                    <div class="body m-b-10 widget-user">
                        <img class="rounded-circle" src="assets/images/sm/avatar5.jpg" alt="">
                        <div class="wid-u-info">
                            <h5>Monica Ryther</h5>
                            <p class="text-muted m-b-0">info@example.com</p>
                            <small class="text-warning"><b>Developer</b></small>
                        </div>
                    </div>
                    <div class="body activities">
                        <div class="streamline b-accent">
                            <div class="sl-item">
                                <div class="sl-content">
                                    <div class="text-muted">Just now</div>
                                    <p>Finished task <a href="" class="text-info">#features 4</a>.</p>
                                </div>
                            </div>
                            <div class="sl-item b-info">
                                <div class="sl-content">
                                    <div class="text-muted">10:30</div>
                                    <p><a href="">@Jessi</a> retwit your post</p>
                                </div>
                            </div>
                            <div class="sl-item b-primary">
                                <div class="sl-content">
                                    <div class="text-muted">12:30</div>
                                    <p>Call to customer <a href="" class="text-info">Jacob</a> and discuss the detail.</p>
                                </div>
                            </div>
                            <div class="sl-item b-warning">
                                <div class="sl-content">
                                    <div class="text-muted">1 days ago</div>
                                    <p><a href="" class="text-info">Jessi</a> commented your post.</p>
                                </div>
                            </div>
                            <div class="sl-item b-primary">
                                <div class="sl-content">
                                    <div class="text-muted">2 days ago</div>
                                    <p>Call to customer <a href="" class="text-info">Jacob</a> and discuss the detail.</p>
                                </div>
                            </div>
                            <div class="sl-item b-primary">
                                <div class="sl-content">
                                    <div class="text-muted">3 days ago</div>
                                    <p>Call to customer <a href="" class="text-info">Jacob</a> and discuss the detail.</p>
                                </div>
                            </div>
                            <div class="sl-item b-warning">
                                <div class="sl-content">
                                    <div class="text-muted">4 Week ago</div>
                                    <p><a href="" class="text-info">Jessi</a> commented your post.</p>
                                </div>
                            </div>
                            <div class="sl-item b-warning">
                                <div class="sl-content">
                                    <div class="text-muted">5 days ago</div>
                                    <p><a href="" class="text-info">Jessi</a> commented your post.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Radar</strong> Chart</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                    <li><a href="javascript:void(0);" class="boxs-close">Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <canvas id="radar_chart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Polar</strong> Area Chart</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                    <li><a href="javascript:void(0);" class="boxs-close">Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <canvas id="chart-area" height="150"></canvas>
                    </div>
                </div>
            </div>                       
        </div>
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card">                    
                    <div class="carousel slide twitter feed" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <i class="zmdi zmdi-twitter zmdi-hc-2x"></i>
                                <p>23th Feb</p>
                                <h4>Now Get <span>Up to 70% Off</span><br>on buy</h4>
                                <div class="m-t-20"><i>- post form ThemeMakker</i></div>
                            </div>
                            <div class="carousel-item">
                                <i class="zmdi zmdi-twitter zmdi-hc-2x"></i>
                                <p>25th Jan</p>
                                <h4>Now Get <span>50% Off</span><br>on buy</h4>
                                <div class="m-t-20"><i>- post form WrapTheme</i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="carousel slide google feed" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <i class="zmdi zmdi-google-plus zmdi-hc-2x"></i>
                                <p>18th Feb</p>
                                <h4>Now Get <span>Up to 70% Off</span><br>on buy</h4>
                                <div class="m-t-20"><i>- post form WrapTheme</i></div>
                            </div>
                            <div class="carousel-item">
                                <i class="zmdi zmdi-google-plus zmdi-hc-2x"></i>
                                <p>28th Mar</p>
                                <h4>Now Get <span>50% Off</span><br>on buy</h4>
                                <div class="m-t-20"><i>- post form ThemeMakker</i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="carousel slide facebook feed" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <i class="zmdi zmdi-facebook zmdi-hc-2x"></i>
                                <p>20th Jan</p>
                                <h4>Now Get <span>50% Off</span><br>on buy</h4>
                                <div class="m-t-20"><i>- post form Theme</i></div>
                            </div>
                            <div class="carousel-item">
                                <i class="zmdi zmdi-facebook zmdi-hc-2x"></i>
                                <p>23th Feb</p>
                                <h4>Now Get <span>Up to 70% Off</span><br>on buy</h4>
                                <div class="m-t-20"><i>- post form Theme</i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('./inc/javascript.php') ?>
</body>
</html>