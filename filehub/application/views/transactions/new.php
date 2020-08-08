<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">

            <!-- Profile Banner -->
            <div class="profile__banner">

                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-light display-i"><?php echo $breadcrumbs; ?></h1>
                    <?php if($pageTitle == 'New Withdrawal'){?>
                    <a href="<?php echo base_url(); ?>withdrawals" class="btn btn-light btn-sm display-i ft-right">My withdrawals</a>
                    <?php } else if($pageTitle == 'New Deposits'){?>
                    <a href="<?php echo base_url(); ?>deposits" class="btn btn-light btn-sm display-i ft-right"><?php echo $role == ROLE_ADMIN ? 'All Deposits' : 'My Deposits' ?></a>
                    <?php } ?>

                    <div class="dt-entry__header mt-1m">
                        <!-- Entry Heading -->
                        <div class="dt-entry__heading">
                        </div>
                        <!-- /entry heading -->
                    </div>
                </div>
                <!-- /page header -->

                <div class="profile__banner-detail">
                    <!-- Avatar Wrapper -->
                    <div class="col-12">
                        <div class="row">
                            <!-- Grid Item -->
                            <div class="col-sm-6 col-12 z0">

                            </div>
                            <!-- Grid Item -->

                            <!-- Grid Item -->
                            <div class="col-sm-6 col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                            <?php if($pageTitle == 'New Withdrawal'){
                                                echo "Available Funds";
                                            } else if($pageTitle == 'New Deposits'){
                                                echo "All Deposits";
                                            } ?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-revenue-new icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php if($pageTitle == 'New Withdrawal'){?>
                                                    <?php if($withdrawals>$earningsInfo)
                                                    { 
                                                        echo to_currency($accountInfo);
                                                    } else 
                                                    {
                                                        echo to_currency($accountInfo);
                                                    } ?>
                                                    <?php } else if($pageTitle == 'New Deposits'){
                                                    echo $activeDeposits > 0 ? to_currency($activeDeposits) : to_currency('0.00');
                                                } ?>
                                                </div>
                                                <span class="d-block">
                                                    <?php if($pageTitle == 'New Withdrawal'){
                                                    echo "Total Funds Available";
                                                } else if($pageTitle == 'New Deposits'){
                                                    echo "Total Deposits";
                                                } ?>
                                                </span>
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
                        </div>
                    </div>
                    <!-- /avatar wrapper -->
                </div>

            </div>
            <!-- /profile banner -->

            <!-- Profile Content -->
            <div class="profile-content marg-t-17 marg-t-0 ">

                <!-- Grid -->
                <div class="row">

                    <!-- Grid Item -->
                    <div class="col-xl-6 col-md-6 col-12 order-xl-1 z20">
                        <!-- Card -->
                        <div class="dt-card">
                            <div class="dt-card__body">
                                <?php
                                    $this->load->helper('form');
                                    $error = $this->session->flashdata('error');
                                    if($error)
                                    {
                                ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">×</button>
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                                <?php } 
                                    $success = $this->session->flashdata('success');
                                    if($success)
                                    {
                                ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">×</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <?php if(!empty($userInfo->pmtAccount) OR $role == ROLE_ADMIN) {?>
                            <div class="dt-card__body">
                                <!-- Form -->
                                <form role="form" id="addWithdrawal" method="post" role="form">
                                    <?php $csrf = array(
                                        'name' => $this->security->get_csrf_token_name(),
                                        'hash' => $this->security->get_csrf_hash()
                                ); ?>
                                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if($role != ROLE_CLIENT) { ?>
                                            <!-- Row -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="email">Client Email</label>
                                                        <input type="email" class="form-control" name="email" value=""
                                                            id="email" aria-describedby="email"
                                                            placeholder="Enter email">
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                            </div>
                                            <!-- /row -->
                                            <?php } ?>
                                            <?php if($pageTitle == 'New Deposits'){ ?>
                                            <?php if($role == ROLE_CLIENT) {?>
                                            <div id="step1">
                                                <div class="form-group">
                                                    <label class="display-6" for="fname">Select your preferred
                                                        investment plan</label>
                                                    <div class="token-currency-choose payment-list">
                                                        <div class="row guttar-15px">
                                                            <?php foreach($plans as $plan) { ?>
                                                            <div class="col-6">
                                                                <div class="payment-item pay-option">
                                                                    <input class="pay-option-check pay-method"
                                                                        type="radio"
                                                                        id="<?php echo $plan->name.$plan->id ?>"
                                                                        min="<?php echo $plan->minInvestment ?>"
                                                                        max="<?php echo $plan->maxInvestment ?>"
                                                                        name="plan" value="<?php echo $plan->id ?>">
                                                                    <label class="pay-option-label"
                                                                        for="<?php echo $plan->name.$plan->id ?>">
                                                                        <span
                                                                            class="text-center display-7"><?php echo number_format($plan->profit, 0).'% '.$plan->periodName.' for '.$this->plans_model->getMaturity($plan->id)->maturity_desc ?></span>
                                                                        <br>
                                                                        <span
                                                                            class="text-center display-7"><?php echo to_currency($plan->minInvestment).' - '.to_currency($plan->maxInvestment) ?></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                        <label class="display-6 text-center error font-15p"></label>
                                                    </div>

                                                </div>
                                                <!-- Form Group -->
                                                <div class="form-group mb-0">
                                                    <button id="next" class="btn btn-info text-uppercase w-100">
                                                        Proceed to Amount</button>
                                                </div>
                                                <!-- /form group -->
                                            </div>
                                            <?php } else if ($role == ROLE_ADMIN) {?>
                                            <!-- Row -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="fname">Investment Plan</label>
                                                        <select class="form-control" name="plan" id="simple-select">
                                                            <option value="" selected disabled hidden>Select investment
                                                                plan</option>
                                                            <?php foreach($plans as $plan) { ?>
                                                            <option value="<?php echo $plan->id ?>">
                                                                <?php echo $plan->name.' at '.$plan->profit.'% '.$plan->periodName.' investment: '.to_currency($plan->minInvestment).' - '.to_currency($plan->maxInvestment)  ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                            </div>
                                            <!-- /row -->
                                            <?php } }?>
                                            <div id="step2" class="<?php if($role == ROLE_ADMIN OR $pageTitle == 'New Withdrawal'){ echo 'display-b'; }else{ echo 'display-n' ;}  ?>">
                                                <!-- Row -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="amount">Enter Amount</label>
                                                            <input type="number" class="form-control" name="amount"
                                                                value="" id="amount" aria-describedby="amount"
                                                                placeholder="Enter amount">
                                                        </div>
                                                        <!-- /form group -->
                                                    </div>
                                                </div>
                                                <!-- /row -->
                                                <?php if($pageTitle == 'New Deposits'){ ?>
                                                <?php if($role == ROLE_CLIENT) {?>
                                                <!-- Row -->
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="form-group">
                                                            <label for="amount">Payment Method</label>
                                                            <div class="row">
                                                                <?php foreach($paymentMethods as $method) {?>
                                                                <div class="cnt_min col-md-2">
                                                                    <input type="radio" name="payMethod"
                                                                        value="<?php echo $method->ref; ?>" /><img
                                                                        src="<?php echo base_url(); ?>assets/dist/img/<?php echo $method->logo; ?>"
                                                                        alt="Select payment method"
                                                                        class="selected_img">
                                                                </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } } ?>
                                                <!-- Form Group -->
                                                <div class="form-group mb-0">
                                                    <button type="submit" id="submitButtonForm"
                                                        class="btn btn-info text-uppercase w-100 mt-2m">
                                                        <?php if($pageTitle == 'New Deposits'){ if($role == ROLE_CLIENT) {echo 'Proceed to make payment'; } else { echo 'Proceed to deposit'; } } else { echo 'process withdrawal';} ?></button>
                                                </div>
                                                <!-- /form group -->
                                            </div>
                                        </div>

                                </form>
                                <!-- /form -->
                            </div>
                            <!-- /card body -->
                            <?php } else {?>
                            <div class="card-body">
                                <!-- Card Title-->
                                <h2 class="card-title">No payment method on record</h2>
                                <!-- Card Title-->
                                <!-- Card Text-->
                                <p class="card-text">
                                    Please setup your payment account by clicking on the link below.
                                </p>
                                <!-- /card text-->
                                <!-- Card Link-->
                                <a href="<?php echo base_url() ?>profile#tab-pane3"
                                    class="btn btn-info text-uppercase">Setup payment account</a>
                                <!-- /card link-->
                            </div>
                            <?php } ?>
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
    </div>
</div>
<script src="<?php echo base_url('/assets/dist/js/functions.js') ?>"></script>