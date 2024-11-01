<!doctype html>
<html class="no-js " lang="pt-br">
<head>
<?php include('./inc/head.php') ?>
</head>
<body class="theme-black">
<!-- Page Loader -->
<!-- <div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="assets/images/logo.svg" width="48" height="48" alt="Alpino"></div>
        <p>Please wait...</p>        
    </div>
</div> -->
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

<nav class="navbar">
    <div class="container">
    <ul class="nav navbar-nav">
        <li>
            <div class="navbar-header">
                <a href="javascript:void(0);" class="h-bars"></a>
                <a class="navbar-brand" href="index.php"><img src="assets/images/logo-black.svg" width="35" alt="Alpino"><span class="m-l-10">Alpino</span></a>
            </div>
        </li>
        <li class="search_bar">
            <div class="input-group">                
                <input type="text" class="form-control" placeholder="Search...">
            </div>
        </li>
        <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-apps"></i></a>
            <ul class="dropdown-menu pullDown">
                <li><a href="mail-inbox.php"><i class="zmdi zmdi-email m-r-10"></i><span>Mail</span></a></li>
                <li><a href="contact.php"><i class="zmdi zmdi-accounts-list m-r-10"></i><span>Contacts</span></a></li>
                <li><a href="chat.php"><i class="zmdi zmdi-comment-text m-r-10"></i><span>Chat</span></a></li>
                <li><a href="invoices.php"><i class="zmdi zmdi-arrows m-r-10"></i><span>Invoices</span></a></li>
                <li><a href="events.php"><i class="zmdi zmdi-calendar-note m-r-10"></i><span>Calendar</span></a></li>
                <li><a href="javascript:void(0)"><i class="zmdi zmdi-arrows m-r-10"></i><span>Notes</span></a></li>
                <li><a href="javascript:void(0)"><i class="zmdi zmdi-view-column m-r-10"></i><span>Taskboard</span></a></li>                
            </ul>
        </li>
        <li class="dropdown notifications badgebit"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-notifications"></i>
            <div class="notify">
                <span class="heartbit"></span>
                <span class="point"></span>
            </div>
            </a>
            <ul class="dropdown-menu pullDown">
                <li class="header">New Message</li>
                <li class="body">
                    <ul class="menu list-unstyled">
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="assets/images/xs/avatar5.jpg" alt="">
                                    <div class="media-body">
                                        <span class="name">Alexander <span class="time">13min ago</span></span>
                                        <span class="message">Meeting with Shawn at Stark Tower by 8 o'clock.</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="assets/images/xs/avatar6.jpg" alt="">
                                    <div class="media-body">
                                        <span class="name">Grayson <span class="time">22min ago</span></span>
                                        <span class="message">You have 5 unread emails in your inbox.</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="assets/images/xs/avatar3.jpg" alt="">
                                    <div class="media-body">
                                        <span class="name">Sophia <span class="time">31min ago</span></span>
                                        <span class="message">OrderPlaced: You received a new oder from Tina.</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>                
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object" src="assets/images/xs/avatar4.jpg" alt="">
                                    <div class="media-body">
                                        <span class="name">Isabella <span class="time">35min ago</span></span>
                                        <span class="message">Lara added a comment in Blazing Saddles.</span>                                        
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="footer"> <a href="javascript:void(0);">View All</a> </li>
            </ul>
        </li>
        <li class="dropdown task badgebit"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-flag"></i>
            <div class="notify">
                    <span class="heartbit"></span>
                    <span class="point"></span>
                </div>
            </a>
            <ul class="dropdown-menu pullDown">
                <li class="header">Project</li>
                <li class="body">
                    <ul class="menu tasks list-unstyled">
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Clockwork Orange <span class="float-right">29%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-turquoise" role="progressbar" aria-valuenow="29" aria-valuemin="0" aria-valuemax="100" style="width: 29%;"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Blazing Saddles <span class="float-right">78%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-slategray" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100" style="width: 78%;"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Project Archimedes <span class="float-right">45%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-parpl" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Eisenhower X <span class="float-right">68%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-coral" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="text-muted">Oreo Admin Templates <span class="float-right">21%</span></span>
                                <div class="progress">
                                    <div class="progress-bar l-amber" role="progressbar" aria-valuenow="21" aria-valuemin="0" aria-valuemax="100" style="width: 21%;"></div>
                                </div>
                            </a>
                        </li>                        
                    </ul>
                </li>
                <li class="footer"><a href="javascript:void(0);">View All</a></li>
            </ul>
        </li>
        <li class="float-right">
            <a href="javascript:void(0);" class="fullscreen" data-provide="fullscreen"><i class="zmdi zmdi-fullscreen"></i></a>
            <a href="javascript:void(0);" class="btn_overlay"><i class="zmdi zmdi-sort-amount-desc"></i></a>
            <a href="javascript:void(0);" class="js-right-sidebar"><i class="zmdi zmdi-settings zmdi-hc-spin"></i></a>
            <a href="sign-in.php" class="mega-menu"><i class="zmdi zmdi-power"></i></a>
        </li>        
    </ul>
    </div>
</nav>

<div class="menu-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">                
                <ul class="h-menu">
                    <li><a href="index.php"><i class="zmdi zmdi-home"></i></a></li>
                    <li class="open active"><a href="javascript:void(0)">Apps</a>
                        <ul class="sub-menu">                                   
                            <li><a href="mail-inbox.php">Inbox</a></li>
                            <li><a href="chat.php">Chat</a></li>
                            <li><a href="events.php">Calendar</a></li>
                            <li><a href="file-dashboard.php">File Manager</a></li>
                            <li><a href="contact.php">Contact list</a></li>
                            
                            <li class="active"><a href="app-ticket.php">Support Ticket</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)">Blog</a>
                        <ul class="sub-menu">
                            <li><a href="blog-dashboard.php">Dashboard</a></li>
                            <li><a href="blog-post.php">New Post</a></li>
                            <li><a href="blog-list.php">Blog List</a></li>
                            <li><a href="blog-grid.php">Blog Grid</a></li>
                            <li><a href="blog-details.php">Blog Single</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)">UI Kit</a>
                        <ul class="sub-menu mega-menu">
                            <li>
                                <ul class="sub-menu-two">
                                    <li><a href="ui_kit.php">UI KIT</a></li>
                                    <li><a href="alerts.php">Alerts</a></li>
                                    <li><a href="collapse.php">Collapse</a></li>
                                    <li><a href="colors.php">Colors</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul class="sub-menu-two">
                                    <li><a href="icons.php">Icons</a></li>
                                    <li><a href="dialogs.php">Dialogs</a></li>
                                    <li><a href="list-group.php">List Group</a></li>
                                    <li><a href="media-object.php">Media Object</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul class="sub-menu-two">
                                    <li><a href="modals.php">Modals</a></li>
                                    <li><a href="notifications.php">Notifications</a></li>
                                    <li><a href="progressbars.php">Progress Bars</a></li>
                                    <li><a href="range-sliders.php">Range Sliders</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul class="sub-menu-two">
                                    <li><a href="sortable-nestable.php">Sortable & Nestable</a></li>
                                    <li><a href="tabs.php">Tabs</a></li>
                                    <li><a href="waves.php">Waves</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)">Forms</a>
                        <ul class="sub-menu">
                            <li><a href="basic-form-elements.php">Basic Elements</a></li>
                            <li><a href="advanced-form-elements.php">Advanced Elements</a></li>
                            <li><a href="form-examples.php">Form Examples</a></li>
                            <li><a href="form-validation.php">Form Validation</a></li>
                            <li><a href="form-wizard.php">Form Wizard</a></li>
                            <li><a href="form-editors.php">Editors</a></li>
                            <li><a href="form-upload.php">File Upload</a></li>
                            <li><a href="form-img-cropper.php">Image Cropper</a></li>
                            <li><a href="form-summernote.php">Summernote</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)">Tables</a>
                        <ul class="sub-menu">
                            <li><a href="normal-tables.php">Normal Tables</a></li>
                            <li><a href="jquery-datatable.php">Jquery Datatables</a></li>
                            <li><a href="editable-table.php">Editable Tables</a></li>
                            
                            <li><a href="table-color.php">Tables Color</a></li>
                            <li><a href="table-filter.php">Tables Filter</a></li>                   
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)">Charts</a>
                        <ul class="sub-menu">
                            <li><a href="morris.php">Morris</a></li>
                            <li><a href="flot.php">Flot</a></li>
                            <li><a href="chartjs.php">ChartJS</a></li>
                            <li><a href="sparkline.php">Sparkline</a></li>
                            <li><a href="jquery-knob.php">Jquery Knob</a></li>
                            <li><a href="chart-e.php">E Chart</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)">Widgets</a>
                        <ul class="sub-menu">                            
                            <li><a href="widgets-app.php">Apps Widgetse</a></li>
                            <li><a href="widgets-data.php">Data Widgetse</a></li>
                            <li><a href="widgets-chart.php">Chart Widgetse</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)">Authentication</a>
                        <ul class="sub-menu">
                            <li><a href="sign-in.php">Sign In</a></li>
                            <li><a href="sign-up.php">Sign Up</a></li>
                            <li><a href="forgot-password.php">Forgot Password</a></li>
                            <li><a href="404.php">Page 404</a></li>
                            <li><a href="403.php">Page 403</a></li>
                            <li><a href="500.php">Page 500</a></li>
                            <li><a href="503.php">Page 503</a></li>
                            <li><a href="page-offline.php">Page Offline</a></li>
                            <li><a href="locked.php">Locked Screen</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)">Maps</a>
                        <ul class="sub-menu">
                            <li><a href="google.php">Google Map</a></li>
                            <li><a href="yandex.php">YandexMap</a></li>
                            <li><a href="jvectormap.php">jVectorMap</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)">Pages</a>
                        <ul class="sub-menu sm-mega-menu">
                            <li>
                                <ul>
                                    <li><a href="blank.php">Blank Page</a></li>
                                    <li><a href="teams-board.php">Teams Board</a></li>
                                    <li><a href="projects.php">Projects List</a></li>
                                    <li><a href="image-gallery.php">Image Gallery</a></li>
                                    <li><a href="profile.php">Profile</a></li>
                                    <li><a href="timeline.php">Timeline</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li><a href="horizontal-timeline.php">Horizontal Timeline</a></li>
                                    <li><a href="pricing.php">Pricing</a></li>
                                    <li><a href="invoices.php">Invoices</a></li>
                                    <li><a href="faqs.php">FAQs</a></li>
                                    <li><a href="search-results.php">Search Results</a></li>
                                    <li><a href="helper-class.php">Helper Classes</a></li>   
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<aside class="right_menu">
    <div id="rightsidebar" class="right-sidebar">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#setting">Setting</a></li>        
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#activity">Activity</a></li>
        </ul>
        <div class="tab-content slim_scroll">
            <div class="tab-pane slideRight active" id="setting">
                <div class="card">
                    <div class="header">
                        <h2><strong>Colors</strong> Skins</h2>
                    </div>
                    <div class="body">
                        <ul class="choose-skin list-unstyled m-b-0">
                            <li data-theme="black" class="active">
                                <div class="black"></div>
                            </li>
                            <li data-theme="purple">
                                <div class="purple"></div>
                            </li>                   
                            <li data-theme="blue">
                                <div class="blue"></div>
                            </li>
                            <li data-theme="cyan">
                                <div class="cyan"></div>                    
                            </li>
                            <li data-theme="green">
                                <div class="green"></div>
                            </li>
                            <li data-theme="orange">
                                <div class="orange"></div>
                            </li>
                            <li data-theme="blush">
                                <div class="blush"></div>                    
                            </li>
                        </ul>
                    </div>
                </div>                
                <div class="card">
                    <div class="header">
                        <h2><strong>General</strong> Settings</h2>
                    </div>
                    <div class="body">
                        <ul class="setting-list list-unstyled m-b-0">
                            <li>
                                <div class="checkbox">
                                    <input id="checkbox1" type="checkbox">
                                    <label for="checkbox1">Report Panel Usage</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <input id="checkbox2" type="checkbox" checked="">
                                    <label for="checkbox2">Email Redirect</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <input id="checkbox3" type="checkbox">
                                    <label for="checkbox3">Notifications</label>
                                </div>                        
                            </li>
                            <li>
                                <div class="checkbox">
                                    <input id="checkbox4" type="checkbox">
                                    <label for="checkbox4">Auto Updates</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <input id="checkbox5" type="checkbox" checked="">
                                    <label for="checkbox5">Offline</label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox m-b-0">
                                    <input id="checkbox6" type="checkbox">
                                    <label for="checkbox6">Location Permission</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="header">
                        <h2><strong>Left</strong> Menu</h2>
                    </div>
                    <div class="body theme-light-dark">
                        <button class="t-dark btn btn-primary btn-round btn-block">Dark</button>
                    </div>
                </div>               
            </div>
            <div class="tab-pane slideLeft" id="activity">
                <div class="card activities">
                    <div class="header">
                        <h2><strong>Recent</strong> Activity Feed</h2>
                    </div>
                    <div class="body">
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
                            <div class="sl-item b-primary">
                                <div class="sl-content">
                                    <div class="text-muted">5 Week ago</div>
                                    <p>Call to customer <a href="" class="text-info">Jacob</a> and discuss the detail.</p>
                                </div>
                            </div>
                            <div class="sl-item b-primary">
                                <div class="sl-content">
                                    <div class="text-muted">3 Week ago</div>
                                    <p>Call to customer <a href="" class="text-info">Jacob</a> and discuss the detail.</p>
                                </div>
                            </div>
                            <div class="sl-item b-warning">
                                <div class="sl-content">
                                    <div class="text-muted">1 Month ago</div>
                                    <p><a href="" class="text-info">Jessi</a> commented your post.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</aside>

<section class="content">
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Support Ticket</h2>                    
                </div>            
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <ul class="breadcrumb float-md-right padding-0">
                        <li class="breadcrumb-item"><a href="index.php"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">App</a></li>
                        <li class="breadcrumb-item active">Support Ticket</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body l-parpl text-center">
                        <div class="sparkline" data-type="bar" data-width="97%" data-height="15px" data-bar-Width="2" data-bar-Spacing="5" data-bar-Color="#ffffff">8,3,2,6,5,9,4,5</div>
                        <h3 class="m-b-0 m-t-10 text-white number count-to" data-from="0" data-to="2078" data-speed="2000" data-fresh-interval="700">2078</h3>
                        <span class="text-white">Total Tickets</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body l-seagreen text-center">
                        <div class="sparkline" data-type="bar" data-width="97%" data-height="15px" data-bar-Width="2" data-bar-Spacing="5" data-bar-Color="#ffffff">2,3,5,6,9,8,7,8,7</div>
                        <h3 class="m-b-0 m-t-10 text-white number count-to" data-from="0" data-to="1278" data-speed="2000" data-fresh-interval="700">1278</h3>
                        <span class="text-white">Resolve</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body l-amber text-center">
                        <div class="sparkline" data-type="bar" data-width="97%" data-height="15px" data-bar-Width="2" data-bar-Spacing="5" data-bar-Color="#ffffff">5,2,8,3,6,9,7,5,1,2</div>
                        <h3 class="m-b-0 m-t-10 text-white number count-to" data-from="0" data-to="521" data-speed="2000" data-fresh-interval="700">521</h3>
                        <span class="text-white">Pending</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="body l-blue text-center">
                        <div class="sparkline" data-type="bar" data-width="97%" data-height="15px" data-bar-Width="2" data-bar-Spacing="5" data-bar-Color="#ffffff">9,3,1,6,9,8,1,8,7</div>
                        <h3 class="m-b-0 m-t-10 text-white number count-to" data-from="0" data-to="978" data-speed="2000" data-fresh-interval="700">978</h3>
                        <span class="text-white">Responded</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Support</strong> & Ticket List</h2>
                        <ul class="header-dropdown">
                            <li><a href="javascript:void(0);" data-toggle="modal" data-target="#addevent"><i class="zmdi zmdi-plus-circle"></i></a></li>
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                    <li><a href="javascript:void(0);" class="boxs-close">Delete</a></li>
                                </ul>
                            </li>                            
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Assign By</th>
                                        <th>Assign to</th>
                                        <th>Email</th>
                                        <th>Sbuject</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>                                
                                <tbody>
                                    <tr>
                                        <td>231</td>
                                        <td>Airi Satou</td>
                                        <td>Angelica Ramos</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-default">Pending</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>235</td>
                                        <td>Brenden Wagner</td>
                                        <td>Ashton Cox</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-success">Complete</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>236</td>
                                        <td>Bradley Greer</td>
                                        <td>Cara Stevens</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-success">Complete</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>245</td>
                                        <td>Cara Stevens</td>
                                        <td>Airi Satou</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-default">Pending</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>248</td>
                                        <td>Airi Satou</td>
                                        <td>Angelica Ramos</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-success">Complete</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>250</td>
                                        <td>Jenette Caldwell</td>
                                        <td>Hermione Butler</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-warning">New</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>260</td>
                                        <td>Paul Byrd</td>
                                        <td>Michael Bruce</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-warning">New</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>261</td>
                                        <td>Lael Greer</td>
                                        <td>Martena Mccray</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-warning">New</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>262</td>
                                        <td>Airi Satou</td>
                                        <td>Angelica Ramos</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-warning">New</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>278</td>
                                        <td>Airi Satou</td>
                                        <td>Angelica Ramos</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-default">Pending</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>278</td>
                                        <td>Airi Satou</td>
                                        <td>Angelica Ramos</td>
                                        <td>airi@example.com</td>
                                        <td>New Code Update</td>
                                        <td><span class="badge badge-default">Pending</span></td>
                                        <td>24-04-2018</td>
                                        <td>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></button>
                                            <button class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</section>

<!-- Default Size -->
<div class="modal fade" id="addevent" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="defaultModalLabel">Add New Contact</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" class="form-control" placeholder="Assign to">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" class="form-control" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" class="form-control" placeholder="Sbuject">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" class="form-control" placeholder="Date">
                    </div>
                </div>       
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-round waves-effect">Save</button>
                <button type="button" class="btn btn-simple btn-round waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!-- Jquery Core Js --> 
<script src="assets/bundles/libscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js --> 
<script src="assets/bundles/vendorscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js --> 

<script src="assets/bundles/datatablescripts.bundle.js"></script>
<script src="assets/bundles/sparkline.bundle.js"></script> <!-- sparkline Plugin Js --> 

<script src="assets/bundles/mainscripts.bundle.js"></script><!-- Custom Js -->
<script src="assets/js/pages/tables/jquery-datatable.js"></script>
</body>
</html>