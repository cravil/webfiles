<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE HTML>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $this->security->xss_clean($this->siteDescription) ?>">
    <meta name="keywords" content="<?php echo $this->security->xss_clean($this->siteKeywords) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $pageTitle ?></title>
    <!-- Bootstrap CSS -->
    
    <link rel="shortcut icon" href="<?php echo $this->favicon ?>">
    
    <?php if($this->uri->segment(1)=="" OR $this->uri->segment(1)=="terms" OR $this->uri->segment(1)=="privacy") {?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/home.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/responsive.css">
    <?php } else { ?>
    <!-- Font Icon Styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/icons.css">
    <!-- /font icon Styles -->
    <!-- Load Styles -->
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/bootstrap-formhelpers.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/chartist.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/style.min.css">
    <!-- /load styles -->
    <!-- include summernote css/js -->
    <link href="<?php echo base_url(); ?>assets/dist/summernote/summernote-bs4.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/intlTelInput.css">
    <?php } ?>
    <script src="<?php echo base_url(); ?>assets/dist/js/jquery.min.js"></script>
</head>
<?php if($this->uri->segment(1)=="" OR $this->uri->segment(1)=="terms" OR $this->uri->segment(1)=="privacy") { ?>
    <body class="light-version js-focus-visible">
    <!-- Preloader -->


    <!-- ##### Header Area Start ##### -->
    <header class="header-area fadeInDown" data-wow-delay="0.2s">
        <div class="classy-nav-container light breakpoint-off dark left">
            <div class="container">
                <!-- Classy Menu -->
                <nav class="classy-navbar light justify-content-between" id="dreamNav">

                    <!-- Logo -->
                    <a class="nav-brand light" href="<?php echo base_url() ?>">
                        <img id="dark-logo" class="logo-img logo-small hidden"
                            src="<?php echo $this->security->xss_clean($this->logoDark); ?>" alt="logo">
                        <img id="white-logo" class="logo-img logo-small"
                            src="<?php echo $this->security->xss_clean($this->logoWhite); ?>" alt="logo">
                    </a>

                    <!-- Navbar Toggler -->
                    <div class="classy-navbar-toggler demo">
                        <span class="navbarToggler"></span>
                    </div>

                    <!-- Menu -->
                    <div class="classy-menu menu-on">

                        <!-- close btn -->
                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>

                        <!-- Nav Start -->
                        <div class="classynav">
                            <ul id="nav">
                                <li class="active"><a href="<?php echo base_url() ?>signup">Get started</a></li>
                                <li class=""><a href="<?php echo base_url() ?>#about">About Us</a></li>
                                <li class=""><a href="<?php echo base_url() ?>#plans">Plans</a></li>
                                <li class=""><a href="<?php echo base_url() ?>#faq">FAQ</a></li>
                                <li class=""><a href="#contact">Contact Us</a></li>
                            </ul>

                            <!-- Button -->
                            <a href="<?php echo base_url() ?>login"
                                class="btn more-btn btn-info blue-grad lh-40p p-0-35p">Login</a>
                        </div>
                        <!-- Nav End -->
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- ##### Header Area End ##### -->
    <?php } else { ?>

    <body class="dt-header--fixed theme-dark dt-layout--full-width dt-sidebar--fixed o-auto">
        <!-- Root -->
        <div class="dt-root op-1">
            <div class="dt-root__inner">
                <?php if($pageTitle=="Login" OR $this->uri->segment(1)=="signup" OR $pageTitle=="Forgot Password" OR $pageTitle=="Reset Password" ) {?>
                <?php } else { ?>
                <!-- Header -->
                <header class="dt-header">
                    <!-- Header container -->
                    <div class="dt-header__container">
                        <!-- Brand -->
                        <div class="dt-brand">
                            <!-- Brand tool -->
                            <div class="dt-brand__tool" data-toggle="main-sidebar">
                                <div class="hamburger-inner"></div>
                            </div>
                            <!-- /brand tool -->

                            <!-- Brand logo -->
                            <span class="dt-brand__logo">
                                <a class="dt-brand__logo-link" href="index.php">
                                <img class="d-none d-sm-inline-block w-100" src="<?php echo $this->security->xss_clean($this->logoDark); ?>" alt="logo">
                                <img class="dt-brand__logo-symbol d-sm-none" src="<?php echo $this->security->xss_clean($this->logoDark); ?>" alt="logo">
                                </a>
                            </span>
                            <!-- /brand logo -->

                        </div>
                        <!-- /brand -->

                        <!-- Header toolbar-->
                        <div class="dt-header__toolbar">
                        

                            <div class="search-box d-none d-lg-block">
                            <?php if($displayBreadcrumbs == true) {?>
                                <h1 class="dt-page__title mt-4"><?php echo $breadcrumbs ?></h1>
                            <?php } else {?>
                                <form method="post">
                                <?php $csrf = array(
                                            'name' => $this->security->get_csrf_token_name(),
                                            'hash' => $this->security->get_csrf_hash()
                                    ); ?>
                                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Search <?php echo $pageTitle; ?>"
                                            name="searchText" value="<?php echo html_purify(set_value('searchText')); ?>" type="search">
                                        <span class="search-icon"><i class="icon icon-search icon-lg"></i></span>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <?php }?>
                            </div>

                            <!-- Header Menu Wrapper -->
                            <div class="dt-nav-wrapper">
                                <!-- Header Menu -->
                                <ul class="dt-nav d-lg-none">
                                    <li class="dt-nav__item dt-notification-search dropdown">

                                        <!-- Dropdown Link -->
                                        <a href="#" class="dt-nav__link dropdown-toggle no-arrow" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"> <i
                                                class="icon icon-search icon-fw icon-lg"></i> </a>
                                        <!-- /dropdown link -->

                                        <!-- Dropdown Option -->
                                        <div class="dropdown-menu">

                                            <!-- Search Box -->
                                            <form class="search-box right-side-icon">
                                                <input class="form-control form-control-lg" type="search"
                                                    placeholder="Search in app...">
                                                <button type="submit" class="search-icon"><i
                                                        class="icon icon-search icon-lg"></i></button>
                                            </form>
                                            <!-- /search box -->

                                        </div>
                                        <!-- /dropdown option -->

                                    </li>
                                </ul>
                                <!-- /header menu -->
                                <!-- Header Menu -->
                                <ul class="dt-nav">
                                    <li class="dt-nav__item dropdown">

                                        <!-- Dropdown Link -->
                                        <a href="#" class="dt-nav__link dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i
                                                class="flag-icon flag-icon-us flag-icon-rounded flag-icon-lg"></i><span>en-uk</span>
                                        </a>
                                        <!-- /dropdown link -->

                                    </li>
                                </ul>
                                <!-- /header menu -->

                                <!-- Header Menu -->
                                <ul class="dt-nav">
                                    <li class="dt-nav__item dropdown">

                                        <!-- Dropdown Link -->
                                        <a href="#" class="dt-nav__link dropdown-toggle no-arrow dt-avatar-wrapper"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img class="dt-avatar size-30"
                                                src="<?php echo $ppic; ?>"
                                                alt="<?php echo $firstName.' '.$lastName; ?>">
                                            <span class="dt-avatar-info d-none d-sm-block">
                                                <span
                                                    class="dt-avatar-name"><?php echo $firstName.' '.$lastName; ?></span>
                                            </span> </a>
                                        <!-- /dropdown link -->

                                        <!-- Dropdown Option -->
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="<?php echo base_url(); ?>profile"> <i
                                                    class="icon icon-user icon-fw mr-2 mr-sm-1"></i>Account
                                            </a>
                                            <a class="dropdown-item" href="<?php echo base_url(); ?>logout"> <i
                                                    class="icon icon-editors icon-fw mr-2 mr-sm-1"></i>Logout
                                            </a>
                                        </div>
                                        <!-- /dropdown option -->

                                    </li>
                                </ul>
                                <!-- /header menu -->
                            </div>
                            <!-- Header Menu Wrapper -->

                        </div>
                        <!-- /header toolbar -->

                    </div>
                    <!-- /header container -->

                </header>
                <!-- /header -->

                <!-- Site Main -->
                <main class="dt-main">
                    <!-- Sidebar -->
                    <aside id="main-sidebar" class="dt-sidebar ps ps--active-y">
                        <div class="dt-sidebar__container">

                            <!-- Sidebar Navigation -->
                            <ul class="dt-side-nav">
                                <?php if($role == ROLE_CLIENT) { ?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="dashboard"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>dashboard"
                                        class="dt-side-nav__link <?php if($this->uri->segment(1)=="dashboard"){echo "dt-active";}?>"
                                        title="Dashboard">
                                        <i class="icon icon-dashboard icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text">Dashboard</span>
                                    </a>
                                </li>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="deposits"){echo "open selected";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-revenue2 icon-fw icon-lg"></i> <span
                                            class="dt-side-nav__text">Deposits</span> </a>

                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="deposits" ? "display-b" : "display-n"?>">
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="deposits" && $this->uri->segment(2)=="new"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>deposits/new"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="deposits" && $this->uri->segment(2)=="new"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text">Deposit Funds</span> </a>
                                        </li>

                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>deposits"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="deposits" && $this->uri->segment(2)==""){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text">View deposits</span> </a>
                                        </li>
                                    </ul>
                                    <!-- /sub-menu -->

                                </li>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="withdrawals"){echo "selected open";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-revenue-new icon-fw icon-lg"></i><span
                                            class="dt-side-nav__text">Withdrawals</span> </a>

                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="withdrawals" ? "display-b" : "display-n"?>">
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="withdrawals"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>withdrawals/new"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="withdrawals" && $this->uri->segment(2)=="new"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text">Withdraw Funds</span> </a>
                                        </li>

                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>withdrawals"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="withdrawals" && $this->uri->segment(2)==""){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text">View withdrawals</span> </a>
                                        </li>
                                    </ul>
                                    <!-- /sub-menu -->

                                </li>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="earnings"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>earnings"
                                        class="dt-side-nav__link <?php if($this->uri->segment(1)=="earnings"){echo "dt-active";}?>"
                                        title="Dashboard">
                                        <i class="icon icon-dashboard icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text">Earnings</span>
                                    </a>
                                </li>
                                <!-- Menu Header -->
                                <?php } else {?>
                                <!-- Menu Header -->
                                <li class="dt-side-nav__item dt-side-nav__header">
                                    <span class="dt-side-nav__text">main</span>
                                </li>
                                <!-- /menu header -->
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="dashboard"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>dashboard"
                                        class="dt-side-nav__link <?php if($this->uri->segment(1)=="dashboard"){echo "dt-active";}?>"
                                        title="Dashboard">
                                        <i class="icon icon-dashboard icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text">Dashboard</span>
                                    </a>
                                </li>
                                <?php if($this->user_model->getPermissions('deposits', 'view', $userId) OR
                                $this->user_model->getPermissions('withdrawals', 'view', $userId) OR
                                $this->user_model->getPermissions('payouts', 'view', $userId) OR
                                $role == ROLE_ADMIN) {?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="withdrawals" || $this->uri->segment(1)=="deposits" || $this->uri->segment(1)=="earnings"){echo "selected open";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-revenue-new icon-fw icon-lg"></i><span
                                            class="dt-side-nav__text">Transactions</span> </a>

                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="withdrawals" || $this->uri->segment(1)=="deposits" || $this->uri->segment(1)=="earnings" ? "display-b" : "display-n"?>">
                                        <?php if($this->user_model->getPermissions('deposits', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="deposits"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>deposits"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="deposits"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text">Deposits</span> </a>
                                        </li>
                                        <?php }?>
                                        <?php if($this->user_model->getPermissions('withdrawals', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>withdrawals"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="withdrawals"){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text">Withdrawals</span> </a>
                                        </li>
                                        <?php }?>
                                        <?php if($this->user_model->getPermissions('payouts', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>earnings"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="earnings"){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text">Payouts</span> </a>
                                        </li>
                                        <?php }?>
                                    </ul>
                                    <!-- /sub-menu -->

                                </li>
                                <?php }?>

                                <?php if($this->user_model->getPermissions('teams', 'view', $userId) OR
                                $this->user_model->getPermissions('clients', 'view', $userId) OR
                                $role == ROLE_ADMIN) {?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="clients" || $this->uri->segment(1)=="team"){echo "selected open";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-contacts-app icon-fw icon-lg"></i><span
                                            class="dt-side-nav__text">Users</span> </a>

                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="clients" || $this->uri->segment(1)=="team" ? "display-b" : "display-n"?>">
                                        <?php if($this->user_model->getPermissions('clients', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="clients"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>clients"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="clients"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text">Clients</span> </a>
                                        </li>
                                        <?php }?>
                                        <?php if($this->user_model->getPermissions('teams', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>team"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="team"){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text">Team</span> </a>
                                        </li>
                                        <?php }?>
                                    </ul>
                                    <!-- /sub-menu -->

                                </li>
                                <?php }?>
                                <?php if($this->user_model->getPermissions('plans', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="plans"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>plans" class="dt-side-nav__link"
                                        title="Contacts App">
                                        <i class="icon icon-list icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text">Investment Plans</span>
                                    </a>
                                </li>
                                <?php }?>
                                <?php if($this->user_model->getPermissions('settings', 'email_templates', $userId) OR
                                    $this->user_model->getPermissions('settings', 'general_settings', $userId) OR
                                    $this->user_model->getPermissions('settings', 'API_settings', $userId) OR
                                    $this->user_model->getPermissions('settings', 'payment_methods', $userId) OR
                                    $role == ROLE_ADMIN) {?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="settings"){echo "selected open";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-settings icon-fw icon-lg"></i><span
                                            class="dt-side-nav__text">Settings</span> </a>
                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="settings" ? "display-b" : "display-n"?>">
                                        <?php if($this->user_model->getPermissions('settings', 'email_templates', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)=="email_templates"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>settings/email_templates"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)=="email_templates"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text">Email Templates</span> </a>
                                        </li>
                                        <?php }?>

                                        <?php if($this->user_model->getPermissions('settings', 'general_settings', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>settings"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)==""){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text">General Settings</span> </a>
                                        </li>
                                        <?php }?>

                                        <?php if($this->user_model->getPermissions('settings', 'API_settings', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>settings/addons"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)=="paymentsAPIs"){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text">Addons API</span> </a>
                                        </li>
                                        <?php }?>

                                        <?php if($this->user_model->getPermissions('settings', 'payment_methods', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>settings/paymentMethods"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)=="paymentMethods"){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text">Payment Methods</span> </a>
                                        </li>
                                        <?php }?>
                                    </ul>
                                    <!-- /sub-menu -->
                                </li>
                                <?php }?>

                                <?php }?>

                                <!-- /menu item -->

                            </ul>
                            <!-- /sidebar navigation -->

                        </div>
                    </aside>
                    <!-- /sidebar -->
                    <?php } } ?>