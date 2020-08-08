<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">
            <!-- Profile Banner -->
            <div class="profile__banner">
                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-white display-i"><?php echo $pageTitle ?></h1>
                    <div class="dt-entry__header mt-1m">
                        <!-- Entry Heading -->
                        <div class="dt-entry__heading">
                        </div>
                        <!-- /entry heading -->
                    </div>
                </div>
                <!-- /page header -->
            </div>
            <!-- /profile banner -->

            <!-- Profile Content -->
            <div class="profile-content">

                <!-- Grid -->
                <div class="row">

                    <!-- Grid Item -->
                    <div class="col-xl-12 col-12 order-xl-1">
                        <!-- Card -->
                        <div class="col-12">
                            <div class="row">

                                <?php if(!empty($paymentMethods)) {
                                foreach($paymentMethods as $method) {?>
                                <!-- Grid Item -->
                                <div class="col-sm-4 col-12">

                                    <!-- Card -->
                                    <div class="dt-card dt-card__full-height text-dark">

                                        <!-- Card Body -->
                                        <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                            <!-- Media -->
                                            <div class="media">
                                                <img class="icon mr-6 align-self-center mt--20p mx-w-100p mh-100p"
                                                    src="<?php echo base_url(); ?>assets/dist/img/<?php echo $method->logo; ?>"
                                                    alt="Stripe Img">

                                                <!-- Media Body -->
                                                <div class="media-body">
                                                    <div class="display-5 font-weight-600 mb-1 init-counter">
                                                        <?php echo $method->name ?></div>
                                                        <?php if($pageTitle == 'Payment Methods') {?>
                                                            <h2 id="methcol<?php echo $method->id ?>" class="<?php echo $method->status == "0" ? 'red' : 'green'  ?>"><?php echo $method->status == '0' ? 'Inactive' : 'Active'; ?></h2>
                                                        <?php } ?>
                                                        <?php echo form_open($pageTitle == 'Add-ons Settings' ? base_url( 'paymentAPIInfo' ) : base_url( 'paymentmethodInfo' ), array('class' => 'methodFormButton'));?>
                                                        <input name="method" value="<?php echo $method->name ?>" hidden/>
                                                        <button type="submit" class="btn btn-info method-button">Update</button>
                                                        <?php echo form_close();?>
                                                </div>
                                                <!-- /media body -->

                                            </div>
                                            <!-- /media -->
                                        </div>
                                        <!-- /card body -->

                                    </div>
                                    <!-- /card -->

                                </div>
                                <!-- Grid Item -->
                                <?php } ?>
                                <div class="col-xl-12 col-12">
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4"><?php echo $this->pagination->create_links(); ?></div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="col-xl-12 col-12">
                                    <div class="text-center mt-5">
                                        <img src="<?php echo base_url('assets/dist/img/no-search-results.png') ?>" class="w-20rm">
                                        <h1>No <?php echo $pageTitle ?> can be found</h1>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <!-- /card -->
                    </div>
                    <!-- /grid item -->
                    
                </div>
                <!-- /grid -->

            </div>
            <!-- /profile content -->

        </div>
        <!-- /Profile -->
        <aside class="dt-customizer dt-drawer position-right">
            <div class="dt-customizer__inner">

                <!-- Customizer Header -->
                <div class="dt-customizer__header">

                    <!-- Customizer Title -->
                    <div class="dt-customizer__title">
                        <h3 class="mb-0" id="method-header"></h3>
                    </div>
                    <!-- /customizer title -->

                    <!-- Close Button -->
                    <button type="button" class="close" data-toggle="customizer">
                        <span aria-hidden="true">×</span>
                    </button>
                    <!-- /close button -->

                </div>
                <!-- /customizer header -->

                <!-- Customizer Body -->
                <div class="dt-customizer__body ps-custom-scrollbar ps ps--active-y">
                <div class="loader" id="loader"></div>
                    <!-- Customizer Body Inner  -->
                    <div class="dt-customizer__body-inner" id="sideContent">
                        <!-- Section -->
                        <section>
                            <img id="methImg" src="" alt="Logo" class="methImg mt-mb-25">
                        </section>
                        <!-- /section -->
                        <?php echo form_open( base_url( 'settings/paymentMethodUpdate' ) , array( 'id' => 'methodForm', 'class' => 'form-group' ));?>                            
                            <!-- Section -->
                            <section class="d-lg-block" id="layout-chooser">
                                
                                <div class="hide" id="bankForm">
                                    <div class="col-md-12 col-sm-12 ml--15p">
                                        <div class="form-group mb--10">
                                            <label for="bname">Bank Name</label>
                                            <input type="text" name="bname" class="form-control" id="bname" aria-describedby="bname" placeholder="Bank Name">
                                            <label class="error" for="bname"></label>
                                        </div>
                                        <div class="form-group mb--10">
                                            <label for="bname">Account Name</label>
                                            <input type="text" name="acname" class="form-control" id="acname" aria-describedby="acname" placeholder="Account Name">
                                            <label class="error" for="acname"></label>
                                        </div>
                                        <div class="form-group mb--10">
                                            <label for="acname">Account Number</label>
                                            <input type="text" name="acnumber" class="form-control" id="acnumber" aria-describedby="acnumber" placeholder="Account Number">
                                            <label class="error" for="acnumber"></label>
                                        </div>
                                        <div class="form-group mb--10">
                                            <label for="swcode">Swift Code</label>
                                            <input type="text" name="swcode" class="form-control" id="swcode" aria-describedby="swcode" placeholder="Swift Code">
                                            <label class="error" for="swcode"></label>
                                        </div>
                                    </div>
                                </div>
                                <h4 id="statusTitle">Status</h4>

                                <div class="col-md-10 col-sm-9 ml--15p">

                                    <!-- Radio Button -->
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="active" name="status" value="1" class="custom-control-input">
                                        <label class="custom-control-label" for="active">Active
                                        </label>
                                    </div>
                                    <!-- /radio button -->

                                    <!-- Radio Button -->
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="inactive" name="status" value="0" class="custom-control-input">
                                        <label class="custom-control-label" for="inactive">Inactive
                                        </label>
                                    </div>
                                    <!-- /radio button -->

                                </div>

                            </section>
                            <!-- /section -->
                            <input class="hide" name="method" id="methID" hidden/>
                            <button class="btn btn-info w-100" name="save" id="methSave">Save</button>
                        <?php echo form_close();?>
                    </div>
                    <!-- /customizer body inner -->
                </div>
                <!-- /customizer body -->

            </div>
        </aside>

    </div>
    <script src="<?php echo base_url('/assets/dist/js/payments.js') ?>"></script>