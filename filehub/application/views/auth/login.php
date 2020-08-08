        <!-- Login Container -->
        <div class="dt-login--container">

            <!-- Login Content -->
            <div class="dt-login__content-wrapper">

                <!-- Login Background Section -->
                <div class="dt-login__bg-section">

                    <div class="dt-login__bg-content">
                        <!-- Login Title -->
                        <h1 class="dt-login__title">Login</h1>
                        <!-- /login title -->

                        <p class="f-16">Sign in and explore <?php echo $this->security->xss_clean($this->siteTitle) ?>.</p>
                    </div>


                    <!-- Brand logo -->
                    <div class="dt-login__logo">
                        <a class="dt-brand__logo-link" href="<?php echo base_url() ?>">
                            <img class="dt-brand__logo-img" src="<?php echo $this->security->xss_clean($this->logoWhite) ?>" alt="logo">
                        </a>
                    </div>
                    <!-- /brand logo -->

                </div>
                <!-- /login background section -->

                <!-- Login Content Section -->
                <div class="dt-login__content">

                    <!-- Login Content Inner -->
                    <div class="dt-login__content-inner">
                        <?php
              $this->load->helper('form');
              $error = $this->session->flashdata('error');
              if($error)
              { ?>
                        <div class="alert border-0 alert-primary bg-gradient m-b-30 alert-dismissible fade show border-radius-none"
                            role="alert">
                            <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <?php } ?>
                        <?php  
                      $success = $this->session->flashdata('success');
                      if($success)
                      {
                  ?>
                        <div class="alert border-0 alert-primary bg-gradient m-b-30 alert-dismissible fade show border-radius-none"
                            role="alert">
                            <?php echo $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <?php } ?>
                        <?php echo validation_errors('<div class="alert border-0 alert-primary bg-gradient m-b-30 alert-dismissible fade show border-radius-none" role="alert">', ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>'); ?>
                        <h2 class="f-20">Enter your email and password below.</h2>
                        <!-- Form -->
                        <?php echo form_open(base_url( 'login' ), array( 'id' => 'loginForm' ));?>

                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="email-1">Email address</label>
                                <input type="email" class="form-control" name="email" id="email-1"
                                    aria-describedby="email-1" placeholder="Enter email" value="<?=set_value('email')?>">
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="password-1">Password</label>
                                <input type="password" class="form-control" name="password" id="password-1"
                                    placeholder="Password" value="<?=set_value('password')?>">
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="dt-checkbox d-block mb-6">
                                <input type="checkbox" id="checkbox-1">
                                <label class="dt-checkbox-content" for="checkbox-1">
                                    Keep me login on this device
                                </label>
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-info text-uppercase">Login</button>
                                <span class="d-inline-block ml-4">Or
                                    <a class="d-inline-block font-weight-500 ml-3"
                                        href="<?php echo base_url("signup") ?>">Create New Account</a>
                                </span>
                            </div>
                            <!-- /form group -->
                        <?php echo form_close();?>
                        <!-- /form -->

                    </div>
                    <!-- /login content inner -->

                    <!-- Login Content Footer -->
                    <div class="dt-login__content-footer">
                        <a href="<?php echo base_url('forgotPassword'); ?>">Can’t access your account?</a>
                    </div>
                    <!-- /login content footer -->

                </div>
                <!-- /login content section -->

            </div>
            <!-- /login content -->

        </div>
        <!-- /login container -->