        <!-- Login Container -->
        <div class="dt-login--container">

            <!-- Login Content -->
            <div class="dt-login__content-wrapper">

                <!-- Login Background Section -->
                <div class="dt-login__bg-section">

                    <div class="dt-login__bg-content">
                        <!-- Login Title -->
                        <h1 class="dt-login__title">Change Password</h1>
                        <!-- /login title -->

                        <p class="f-16">Change your <?php echo $this->security->xss_clean($this->siteTitle) ?> password.</p>
                    </div>


                    <!-- Brand logo -->
                    <div class="dt-login__logo">
                        <a class="dt-brand__logo-link" href="<?php echo base_url() ?>">
                            <img class="dt-brand__logo-img" src="<?php echo $this->security->xss_clean($this->logoWhite) ?>" alt="Logo">
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
                        <h2 class="f-20">Enter your new password below.</h2>
                        <!-- Form -->
                        <?php echo form_open(base_url( 'createPasswordUser' ));?>
                            <input type="email" class="form-control" placeholder="Email" name="email"
                                value="<?php echo $email; ?>" readonly required hidden />
                            <input type="text" class="form-control" placeholder="Email" name="activation_code"
                                value="<?php echo $activation_code; ?>" readonly required hidden />
                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="password-1">New Password</label>
                                <input type="password" class="form-control" name="password" id="password-1"
                                    aria-describedby="password-1" placeholder="Enter new password">
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="password-2">Confirm Password</label>
                                <input type="password" class="form-control" name="cpassword" id="password-2"
                                    placeholder="Confirm Password">
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary text-uppercase">Change Password</button>
                            </div>
                            <!-- /form group -->

                        <?php echo form_close();?>
                        <!-- /form -->

                    </div>
                    <!-- /login content inner -->
                </div>
                <!-- /login content section -->

            </div>
            <!-- /login content -->

        </div>
        <!-- /login container -->