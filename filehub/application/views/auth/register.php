        <!-- Login Container -->
        <div class="dt-login--container">

            <!-- Login Content -->
            <div class="dt-login__content-wrapper">

                <!-- Login Background Section -->
                <div class="dt-login__bg-section">

                    <div class="dt-login__bg-content">
                        <!-- Login Title -->
                        <h1 class="dt-login__title">Join <?php echo $this->security->xss_clean($this->siteTitle); ?></h1>
                        <!-- /login title -->

                        <p class="f-16">Sign up and explore <?php echo $this->security->xss_clean($this->siteTitle); ?>.</p>
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
                        <div class="col-md-12">
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
                        </div>
                        <h2 class="f-20">Create Your Account.</h2>
                        <!-- Form -->
                        <?php echo form_open(base_url( 'signup' ), array( 'id' => 'registerForm' ));?>
                          <div class="row">
                            <div class="col-md-6">
                              <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="f-name">First Name</label>
                                <input type="text" class="form-control" name="firstname" id="f-name"
                                    aria-describedby="f-name" placeholder="First Name" value="<?=set_value('firstname')?>">
                                    <label class="error" id="firstname"></label>
                            </div>
                            <!-- /form group -->
                            </div>
                            <div class="col-md-6">
                              <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="l-name">Last Name</label>
                                <input type="text" class="form-control" name="lastname" id="l-name"
                                    aria-describedby="l-name" placeholder="Last Name" value="<?=set_value('lastname')?>">
                                    <label class="error" id="lastname"></label>
                            </div>
                            <!-- /form group -->
                            </div>
                          </div>

                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="email-1">Email address</label>
                                <input type="email" class="form-control" name="email" id="email-1"
                                    aria-describedby="email-1" placeholder="Email" value="<?=set_value('email')?>">
                                    <label class="error" id="email"></label>
                            </div>
                            <!-- /form group -->

                            <div class="row">
                              <div class="col-md-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="sr-only" for="password-1">Password</label>
                                    <input type="password" class="form-control" name="password" id="password-1"
                                        placeholder="Password" value="<?=set_value('password')?>">
                                    <label class="error" id="password"></label>
                                </div>
                                <!-- /form group -->
                              </div>
                              <div class="col-md-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="sr-only" for="password-2">Password</label>
                                    <input type="password" class="form-control" name="cpassword" id="password-2"
                                        placeholder="Confirm password" value="<?=set_value('cpassword')?>">
                                    <label class="error" id="cpassword"></label>
                                </div>
                                <!-- /form group -->
                              </div>
                              <div class="col-md-12">
                              <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="ref">Referral Code</label>
                                <input type="text" class="form-control" name="ref" id="ref"
                                    aria-describedby="ref" placeholder="Referral Code" value="<?php echo $this->security->xss_clean($code) ?>">
                            </div>
                            <!-- /form group -->
                            </div>
                            </div>

                            <!-- Form Group -->
                            <div class="dt-checkbox d-block mb-6">
                            <?php $agreeCheck = array( 'name' => 'accept_terms', 'id' => 'accept_terms', 'value' => 'agree', 'checked' => set_checkbox('accept_terms', 'agree', FALSE)); ?>
                                
                                <?php echo form_checkbox($agreeCheck); ?>
                                <label class="dt-checkbox-content" for="checkbox-1">
                                I agree to <?php echo $this->security->xss_clean($companyName)."'s" ; ?> <a target="_blank" href="<?php echo base_url(); ?>privacy" class="checkbox-link">Privacy policy</a> and 
                                  <a target="_blank" href="<?php echo base_url(); ?>terms" class="checkbox-link">Terms of Use</a>
                                </label>
                                <label class="error red" id="terms" for="password"></label>
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="form-group">
                                <button type="submit" id="submit" class="btn btn-info text-uppercase">Create Account</button>
                                <span class="d-inline-block ml-4">Or
                                    <a class="d-inline-block font-weight-500 ml-3"
                                        href="<?php echo base_url("login") ?>">Login</a>
                                </span>
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
    <script src="<?php echo base_url('/assets/dist/js/functions.js') ?>"></script>