

    <!-- ##### Welcome Area Start ##### -->
    <section class="hero-section blue-bg relative section-padding" id="home">

        <div class="hero-section-content">

            <div class="container h-100">
                <div class="row h-100 mb-50 align-items-center">

                    <!-- Welcome Content -->
                    <div class="col-12 col-lg-6 col-md-12">
                        <div class="welcome-content">
                            <div class="promo-section">
                                <h3 class="special-head gradient-text cyan">Our aim is to unlock our clients' financial
                                    freedom</h3>
                            </div>
                            <h1 class="w-text wow fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.2s">
                                High Returns With Calculated Risk </h1>
                            <p class="g-text wow fadeInUp main-pg-txt a-delay-3" data-wow-delay="0.3s">
                                <?php echo $pageTitle ?> is intended for people willing to achieve wealth and financial
                                freedom but unable to do so because they're not financial experts.</p>
                            <div class="dream-btn-group wow fadeInUp main-pg-txt a-delay-4" data-wow-delay="0.4s">
                                <a href="<?php echo base_url() ?>signup" class="btn more-btn btn-primary pink mr-3">Sign Up</a>
                                <a href="<?php echo base_url() ?>login" class="btn more-btn btn-info blue-grad"> Login</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="main-ilustration main-ilustration-5"></div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- ##### Welcome Area End ##### -->


    <div class="clearfix"></div>

    <div class="clearfix"></div>

    <!-- ##### About Us Area Start ##### -->
    <section class="special section-padding-100-70 clearfix" id="about">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 offset-lg-0 col-md-12 no-padding-left">
                    <div class="welcome-meter wow fadeInUp mb-30 main-pg-txt a-delay-7" data-wow-delay="0.7s">
                        <img src="<?php echo base_url() ?>assets/dist/img/About-us-banner-img.png" alt="">
                    </div>
                </div>

                <div class="col-12 col-lg-6 offset-lg-0">
                    <div class="who-we-contant">
                        <div class="dream-dots text-left fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.2s">
                            <span class="gradient-text blue">Get to know us</span>
                        </div>
                        <h4 class="fadeInUp" data-wow-delay="0.3s">About <?php echo $pageTitle ;?></h4>
                        <p class="fadeInUp" data-wow-delay="0.4s">Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Suscipit eum illum tempora? Ducimus eum culpa voluptates dolorem dolorum et sit nisi,
                            mollitia animi porro fuga sequi, molestias repellat excepturi nobis eum culpa voluptates
                            dolorem dolorum et.Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Suscipit eum illum tempora? Ducimus eum culpa voluptates dolorem dolorum et sit nisi,
                            mollitia animi porro fuga sequi, molestias repellat excepturi nobis eum culpa voluptates
                            dolorem dolorum et.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ##### About Us Area End ##### -->

    <section class=" fuel-features features section-padding-100 clearfix" id="plans">

        <div class="container has-shadow">
            <div class="section-heading text-center">
                <!-- Dream Dots -->
                <div class="dream-dots justify-content-center fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.2s">
                    <span class="gradient-text blue">Investment Plans</span>
                </div>
                <h2 class="wow fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.3s">Our investment Plans
                </h2>
            </div>
            <div class="row align-items-center">
                <?php foreach($plans as $plan) {?>
                <div class="col-lg-4 col-md-6 col-sm-12 mt-md-30">
                    <div class="services-block-four v2 txt-center">
                            <h3><a href="#"><?php echo $this->security->xss_clean($plan->name) ?></a></h3>
                            <h2 class="black"><?php echo $this->security->xss_clean(number_format($plan->profit, 1)).'% '.$this->security->xss_clean($plan->periodName) ?></h2>
                            <h5><?php echo to_currency($this->security->xss_clean($plan->minInvestment)).' - '.to_currency($this->security->xss_clean($plan->maxInvestment)) ?></h5>
                        <a href="#" class="icon_foot">
                            <i class="fa fa-long-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>
    </section>
    <section class="special section-padding-100-70 clearfix" id="about">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 offset-lg-0">
                    <div class="who-we-contant">
                        <div class="dream-dots text-left fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.2s">
                            <span class="gradient-text blue">Sign up and become an affiliate</span>
                        </div>
                        <h4 class="fadeInUp" data-wow-delay="0.3s">Affiliate Program</h4>
                        <p class="fadeInUp" data-wow-delay="0.4s">Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Suscipit eum illum tempora? Ducimus eum culpa voluptates dolorem dolorum et sit nisi,
                            mollitia animi porro fuga sequi, molestias repellat excepturi nobis eum culpa voluptates
                            dolorem dolorum et.Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Suscipit eum illum tempora? Ducimus eum culpa voluptates dolorem dolorum et sit nisi,
                            mollitia animi porro fuga sequi, molestias repellat excepturi nobis eum culpa voluptates
                            dolorem dolorum et.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6 offset-lg-0 col-md-12 no-padding-left">
                    <div class="welcome-meter wow fadeInUp mb-30 main-pg-txt a-delay-7" data-wow-delay="0.7s">
                        <img src="<?php echo base_url(); ?>assets/dist/img/affiliate-program.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ##### FAQ & Timeline Area Start ##### -->
    <div class="faq-timeline-area section-padding-100-85" id="faq">
        <div class="container">
            <div class="section-heading text-center">
                <!-- Dream Dots -->
                <div class="dream-dots justify-content-center fadeInUp" data-wow-delay="0.2s">
                    <span class="gradient-text blue">REPEATED QUESTIONS</span>
                </div>
                <h2 class="fadeInUp" data-wow-delay="0.3s"> Frequently Asked Questions</h2>
                <p class="fadeInUp" data-wow-delay="0.4s">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed
                    quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>
            </div>
            <div class="row align-items-center">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="dream-faq-area mt-s ">
                        <div class="row">
                            <dl class="col-lg-6 mb-0">
                                <!-- Single FAQ Area -->
                                <dt class="v2 wave fadeInUp" data-wow-delay="0.2s">Where are you incorporated?
                                </dt>
                                <dd class="fadeInUp" data-wow-delay="0.3s">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat
                                        nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda
                                        dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>
                                </dd>
                                <!-- Single FAQ Area -->
                                <dt class="v2 wave fadeInUp" data-wow-delay="0.3s">Can I join ProInvest?</dt>
                                <dd class="display-n">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat
                                        nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda
                                        dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>
                                </dd>
                                <!-- Single FAQ Area -->
                                <dt class="v2 wave fadeInUp" data-wow-delay="0.4s">What are the costs associated with opening an account?
                                </dt>
                                <dd class="display-n">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat
                                        nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda
                                        dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>
                                </dd>
                                <!-- Single FAQ Area -->
                                <dt class="v2 wave fadeInUp" data-wow-delay="0.5s">When can I withdraw my earnings?
                                </dt>
                                <dd class="display-n">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat
                                        nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda
                                        dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>
                                </dd>
                            </dl>
                            <dl class="col-lg-6 mb-0">
                                <!-- Single FAQ Area -->
                                <dt class="v2 wave fadeInUp" data-wow-delay="0.3s">I'm having trouble logging into my account</dt>
                                <dd class="display-n">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat
                                        nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda
                                        dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>
                                </dd>
                                <!-- Single FAQ Area -->
                                <dt class="v2 wave fadeInUp" data-wow-delay="0.3s">How do you invest the funds?</dt>
                                <dd class="display-n">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat
                                        nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda
                                        dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>
                                </dd>
                                <!-- Single FAQ Area -->
                                <dt class="v2 wave fadeInUp" data-wow-delay="0.4s">What is the minimu amount that I can start with?
                                </dt>
                                <dd class="display-n">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat
                                        nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda
                                        dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>
                                </dd>
                                <!-- Single FAQ Area -->
                                <dt class="v2 wave fadeInUp" data-wow-delay="0.5s">How much money do I make if i invite a friend?
                                </dt>
                                <dd class="display-n">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat
                                        nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda
                                        dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ##### FAQ & Timeline Area End ##### -->

    