<?php

function HeaderLinks(){
    CheckUser();
    return '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
              <meta http-equiv="X-UA-Compatible" content="IE=edge">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <meta name="description" content="Admin for the marketing maintanance.">
              <meta name="keywords" content="admin, maintanance, marketing">
              <meta name="author" content="pixelstrap">
              <link rel="icon" href="'.asset('assets/images/favicon.png').'" type="image/x-icon">
              <link rel="shortcut icon" href="'.asset('assets/images/favicon.png').'" type="image/x-icon">
              <title>D2C MARKETING| Dashboard</title>
              <!-- Google font-->
              <link rel="preconnect" href="https://fonts.googleapis.com">
              <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
              <link href="'.asset('css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;display=swap').'" rel="stylesheet">
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/font-awesome.css').'">
              <!-- ico-font-->
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/vendors/icofont.css').'">
              <!-- Themify icon-->
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/vendors/themify.css').'">
              <!-- Flag icon-->
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/vendors/flag-icon.css').'">
              <!-- Feather icon-->
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/vendors/feather-icon.css').'">
              <!-- Plugins css start-->
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/vendors/slick.css').'">
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/vendors/slick-theme.css').'">
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/vendors/scrollbar.css').'">
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/vendors/animate.css').'">
              <!-- Plugins css Ends-->
              <!-- Bootstrap css-->
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/vendors/bootstrap.css').'">
              <!-- App css-->
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/style.css').'">
              <link id="color" rel="stylesheet" href="'.asset('assets/css/color-1.css').'" media="screen">
              <!-- Responsive css-->
              <link rel="stylesheet" type="text/css" href="'.asset('assets/css/responsive.css').'">
              <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
              <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
              <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
              <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
              <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
              <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css" rel="stylesheet">
              <link href="https://cdn.syncfusion.com/ej2/material.css" rel="stylesheet">
              <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
              <style>
                  .card {
                      position: sticky !important; /* Ensure the card is a relative container */
                  }
                  .loading-overlay {
                    display: none;
                    background: rgba(255, 255, 255, 0.7);
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    top: 0;
                    z-index: 9998;
                    align-items: center;
                    justify-content: center;
                  }
                  .loading-overlay.is-active {
                    display: flex;
                  }
                  .icon-container {
                    position: absolute;
                    right: 10px;
                    top: calc(50% - 10px);
                  }
                  .loader123 {
                    position: relative;
                    height: 20px;
                    width: 20px;
                    display: inline-block;
                    animation: around 5.4s infinite;
                  }
                  
                  @keyframes around {
                    0% {
                      transform: rotate(0deg)
                    }
                    100% {
                      transform: rotate(360deg)
                    }
                  }
                  
                  .loader123::after, .loader123::before {
                    content: "";
                    background: white;
                    position: absolute;
                    display: inline-block;
                    width: 100%;
                    height: 100%;
                    border-width: 2px;
                    border-color: #333 #333 transparent transparent;
                    border-style: solid;
                    border-radius: 20px;
                    box-sizing: border-box;
                    top: 12px;
                    left: 0;
                    animation: around 0.7s ease-in-out infinite;
                  }
                  
                  .loader123::after {
                    animation: around 0.7s ease-in-out 0.1s infinite;
                    background: transparent;
                  }
                  
                  input[readonly] {
                      background-color: #f0f0f0;
                      color: #333;
                      border: 1px solid #ccc;
                      cursor: not-allowed;
                  }
                  
                  input[readonly]::placeholder {
                      color: #999;
                  }
                  
                  .select2-container--default .select2-results__option--highlighted[aria-selected] {
                      background-color: #006666;
                      color: white;
                  }
                  
                  .select2-container .select2-selection--single {
                      border-radius: 0.25rem !important;
                      border-color: #efefef;
                      height: 38px !important;
                      padding: 10px;
                  }
                  
                  .select2-container--default .select2-selection--single .select2-selection__rendered {
                      color: #444;
                      line-height: 16px;
                  }
                  
                  .select2-container--default .select2-selection--single .select2-selection__arrow {
                      height: 37px;
                      position: absolute;
                      top: 1px;
                      right: 1px;
                      width: 20px;
                  }
              </style>';

           
}

function ScriptLinks(){
    CheckUser();
    return '<!-- latest jquery-->
            <script src="'.asset('assets/js/jquery.min.js').'"></script>
            <!-- Bootstrap js-->
            <script src="'.asset('assets/js/bootstrap/bootstrap.bundle.min.js').'"></script>
            <!-- feather icon js-->
            <script src="'.asset('assets/js/icons/feather-icon/feather.min.js').'"></script>
            <script src="'.asset('assets/js/icons/feather-icon/feather-icon.js').'"></script>
            <!-- scrollbar js-->
            <script src="'.asset('assets/js/scrollbar/simplebar.js').'"></script>
            <script src="'.asset('assets/js/scrollbar/custom.js').'"></script>
            <!-- Sidebar jquery-->
            <script src="'.asset('assets/js/config.js').'"></script>
            <!-- Plugins JS start-->
            <script src="'.asset('assets/js/sidebar-menu.js').'"></script>
            <script src="'.asset('assets/js/sidebar-pin.js').'"></script>
            <script src="'.asset('assets/js/slick/slick.min.js').'"></script>
            <script src="'.asset('assets/js/slick/slick.js').'"></script>
            <script src="'.asset('assets/js/header-slick.js').'"></script>
            <!-- calendar js-->
            <!-- Plugins JS Ends-->
            <!-- Theme js-->
            <script src="'.asset('assets/js/script.js').'"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
            <script src="'.asset('assets/js/notify-js/notify.js').'"></script>
            <script src="'.asset('assets/js/notify-js/notify.min.js').'"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js"></script>
            <script src="https://cdn.syncfusion.com/ej2/dist/ej2.min.js"></script>';
}

function HeaderMenu(){
    CheckUser();
    return '<!-- loader starts-->
            <div class="loader-wrapper">
              <div class="loader"> 
                <div class="loader4"></div>
              </div>
            </div>
            <!-- loader ends-->
            <!-- tap on top starts-->
            <div class="tap-top"><i data-feather="chevrons-up"></i></div>
            <!-- tap on tap ends-->
            <!-- page-wrapper Start-->
            <div class="page-wrapper compact-wrapper" id="pageWrapper">
              <!-- Page Header Start-->
              <div class="page-header">
                <div class="header-wrapper row m-0">
                  <form class="form-inline search-full col" action="#" method="get">
                    <div class="form-group w-100">
                      <div class="Typeahead Typeahead--twitterUsers">
                        <div class="u-posRelative"> 
                          <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Riho .." name="q" title="" autofocus="">
                          <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading... </span></div><i class="close-search" data-feather="x"></i>
                        </div>
                        <div class="Typeahead-menu"> </div>
                      </div>
                    </div>
                  </form>
                  <div class="header-logo-wrapper col-auto p-0">  
                    <div class="logo-wrapper"> <a href="index.html"><img class="img-fluid for-light" src="'.asset('assets/images/logo/logo_dark.png').'" alt="logo-light"><img class="img-fluid for-dark" src="'.asset('assets/images/logo/logo.png').'" alt="logo-dark"></a></div>
                    <div class="toggle-sidebar"> <i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
                  </div>
                  <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0">
                    <div> <a class="toggle-sidebar" href="#"> <i class="iconly-Category icli"> </i></a>
                      <div class="d-flex align-items-center gap-2 ">
                        <h4 class="f-w-600">Welcome D2C</h4><img class="mt-0" src="'.asset('assets/images/hand.gif').'" alt="hand-gif">
                      </div>
                    </div>
                    <div class="welcome-content d-xl-block d-none"><span class="text-truncate col-12">Here’s what’s happening with your products today. </span></div>
                  </div>
                  <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
                    <ul class="nav-menus"> 
                      <li class="d-md-block d-none"> 
                        <div class="form search-form mb-0">
                          <div class="input-group"><span class="input-icon">
                            
                            <div ><i class="search" data-feather="search"> </i></div>
                              <input class="w-100" type="search" placeholder="Search"></span></div>
                        </div>
                      </li>
                      <li class="d-md-none d-block"> 
                        <div class="form search-form mb-0">
                          <div class="input-group"> <span class="input-show"> 
                           
                              <i class="search"  id="searchIcon" data-feather="search"> </i>
                          
                              <div id="searchInput">
                                <input type="search" placeholder="Search">
                              </div></span></div>
                        </div>
                      </li>
                      
                      <li> 
                        <div class="mode"><i class="moon" data-feather="moon"> </i></div>
                      </li>
                      
                      <li class="profile-nav onhover-dropdown"> 
                        <div class="media profile-media"><img class="b-r-10" src="'.asset('assets/images/dashboard/profile.png').'" alt="">
                          <div class="media-body d-xxl-block d-none box-col-none">
                            <div class="d-flex align-items-center gap-2"> <span>D2C</span><i class="middle fa fa-angle-down"> </i></div>
                            <p class="mb-0 font-roboto">Admin</p>
                          </div>
                        </div>
                        <ul class="profile-dropdown onhover-show-div">
                          
                          <li>
                          <form action="logout" id="logoutForm" style="display:none;" method="post">
                          '.csrf_field() .'
                          </form>
                          <button form="logoutForm" class="btn btn-pill btn-outline-primary btn-sm">Log out</button>
                        </ul>
                      </li>
                    </ul>
                  </div>
                  <script class="result-template" type="text/x-handlebars-template">
                    <div class="ProfileCard u-cf">                        
                    <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
                    <div class="ProfileCard-details"> 
                    <div class="ProfileCard-realName">{{name}}</div>
                    </div> 
                    </div>
                  </script>
                  <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
                </div>
              </div>
              <!-- Page Header Ends -->';
}

function SideMenuBars(){
    $checkUserResponse = CheckUser();
    if ($checkUserResponse instanceof \Illuminate\Http\RedirectResponse) {
        return $checkUserResponse;
    }
    $DuplicateAdmin = '<!-- Page Sidebar Start-->
    <div class="sidebar-wrapper" data-layout="stroke-svg">
      <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo.png').'" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"> </i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo-icon.png').'" alt=""></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo-icon.png').'" alt=""></a>
              <div class="mobile-back text-end"> <span>Back </span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            <li class="pin-title sidebar-main-title">
              <div> 
                <h6>Pinned</h6>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">General</h6>
              </div>
            </li>
            
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title link-nav active" href="index.html">
                
                <i class="color-white fa fa-home " data-feather="shopping-cart "></i><span> Dashboard</span></a>
            </li>
            
          </ul>
          <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
      </nav>
    </div>
    <!-- Page Sidebar Ends-->';

    $MainAdmin = '<!-- Page Sidebar Start-->
    <div class="sidebar-wrapper" data-layout="stroke-svg">
      <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo.png').'" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"> </i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo-icon.png').'" alt=""></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo-icon.png').'" alt=""></a>
              <div class="mobile-back text-end"> <span>Back </span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            <li class="pin-title sidebar-main-title">
              <div> 
                <h6>Pinned</h6>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">General</h6>
              </div>
            </li>
            
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title link-nav active" href="dashboard">
                
                <i class="color-white fa fa-home " data-feather="shopping-cart "></i><span> Dashboard</span></a></li>
              
            
            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-8">Marketing Management</h6>
              </div>
            </li>
            
            <li class="sidebar-list"> 
              <a class="sidebar-link sidebar-title" href="#">
                <i class="color-white fa fa-shopping-cart " data-feather="shopping-cart "></i>
              <span> Products</span></a>
              <ul class="sidebar-submenu">

                <li><a href="'.url('add_products').'">Add products</a></li>
                <li><a href="'.url('products').'">Product list</a></li>
              </ul>
            </li>
            <li class="sidebar-list"> 
              <a class="sidebar-link sidebar-title" href="#">
                <i class="color-white fa fa-user-circle-o" data-feather="shopping-cart "></i>
              <span> client</span></a>
              <ul class="sidebar-submenu">
              
              <li><a href="'.url('add_client').'">Add client</a></li>
              <li><a href="'.url('clients').'">Client list</a></li>


                
         

              </ul>
            </li>
            <li class="sidebar-list"> 
              <a class="sidebar-link sidebar-title" href="#">
                <i class="color-white fa fa-shopping-cart " data-feather="shopping-cart "></i>
              <span> orders</span></a>
              <ul class="sidebar-submenu">
              <li><a href="'.url('assign_orders').'"> Assign orders</a></li>
              <li><a href="'.url('orders').'">Order List</a></li></ul>
            </li>
            <li class="sidebar-list"> 
              <a class="sidebar-link sidebar-title" href="#">
                <i class="color-white fa fa-print" data-feather="shopping-cart "></i>
              <span>Invoices</span></a>
              <ul class="sidebar-submenu">
              <li><a href="'.url('invoice').'">Invoice</a></li></ul>
            </li>
              <li class="sidebar-list"> 
              <a class="sidebar-link sidebar-title" href="#">
                <i class="color-white fa fa-users" data-feather="shopping-cart "></i>
              <span>Employees</span></a>
              <ul class="sidebar-submenu">
              <li><a href="'.url('add_employee').'">Add Employee</a></li>
              <li><a href="'.url('employee_list').'">Employee List</a></li></ul>
            </li>
            
          </ul>
          <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
      </nav>
    </div>
    <!-- Page Sidebar Ends-->';
    
    $PackingAdmin = '<!-- Page Sidebar Start-->
    <div class="sidebar-wrapper" data-layout="stroke-svg">
      <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo.png').'" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"> </i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo-icon.png').'" alt=""></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo-icon.png').'" alt=""></a>
              <div class="mobile-back text-end"> <span>Back </span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            <li class="pin-title sidebar-main-title">
          
            </li>
            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Packing</h6>
              </div>
            </li>
            
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title link-nav active" href="dashboard">
                
                <i class="color-white fa fa-home " data-feather="shopping-cart "></i><span>Packing Dashboard</span></a>
            </li>
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title link-nav active" href="'.url('pack-pending-orders').'">
        
                
                <i class="color-white fa fa-home " data-feather="shopping-cart "></i><span>Pending Orders</span></a>
            </li>
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title link-nav active" href="'.url('pack-packed-orders').'">
            
                
                <i class="color-white fa fa-home " data-feather="shopping-cart "></i><span>Packed Orders</span></a>
            </li>
            
          </ul>
          <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
      </nav>
    </div>
    <!-- Page Sidebar Ends-->';
    
    $VerifierAdmin = '<!-- Page Sidebar Start-->
    <div class="sidebar-wrapper" data-layout="stroke-svg">
      <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo.png').'" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"> </i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo-icon.png').'" alt=""></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn"><a href="index.html"><img class="img-fluid" src="'.asset('assets/images/logo/logo-icon.png').'" alt=""></a>
              <div class="mobile-back text-end"> <span>Back </span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            <li class="pin-title sidebar-main-title">
          
            </li>
            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Packing</h6>
              </div>
            </li>
            
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title link-nav active" href="dashboard">
                
                <i class="color-white fa fa-home " data-feather="shopping-cart "></i><span>Verifier Dashboard</span></a>
            </li>
            <li class="sidebar-list">
              <a class="sidebar-link sidebar-title link-nav active" href="'.url('verifier-pending-orders').'">
                
                <i class="color-white fa fa-home " data-feather="shopping-cart "></i><span>Pending orders</span></a>
            </li>
            <li class="sidebar-list">
              <a class="sidebar-link sidebar-title link-nav active" href="'.url('verifier-verified-orders').'">
       
                
                <i class="color-white fa fa-home " data-feather="shopping-cart "></i><span>Verified orders</span></a>
            </li>
            <li class="sidebar-list">
              <a class="sidebar-link sidebar-title link-nav active" href="'.url('dispatched-orders').'">
                
                <i class="color-white fa fa-home " data-feather="shopping-cart "></i><span>Dispatched orders</span></a>
            </li>
            
          </ul>
          <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
      </nav>
    </div>
    <!-- Page Sidebar Ends-->';
    
    if(session('user')->user_type == 'marketing_admin'){
        return $MainAdmin;
    }elseif(session('user')->user_type == 'Packing'){
        return $PackingAdmin;
    }elseif(session('user')->user_type == 'Verifier'){
        return $VerifierAdmin;
    }else{
        return $DuplicateAdmin;
    }
    
}

function CheckUser()
{
    if (session('user') == null) {
        return redirect('/');
    }
}