<?php
$payMethod = $depositInfo->paymentMethod;
$amount = $depositInfo->amount;
$planId = $depositInfo->planId;
$date = $depositInfo->createdDtm;
?>
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
                    <h1 class="dt-page__title text-white display-i">Edit Deposit</h1>
                    <a href="javascript:history.back()" class="btn btn-light btn-sm display-i ft-right">back</a>
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
                                <?php } ?>
                                <?php  
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
                                <!-- Card Body -->
                                <!-- Form -->
                                <form role="form" id="addDeposit" method="post" role="form">
                                    <?php $csrf = array(
                                        'name' => $this->security->get_csrf_token_name(),
                                        'hash' => $this->security->get_csrf_hash()
                                ); ?>
                                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Row -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="email">Client Email</label>
                                                        <input type="email" class="form-control" name="email"
                                                            value="<?php echo $email; ?>" id="email"
                                                            aria-describedby="email" placeholder="Enter email">
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                            </div>
                                            <!-- /row -->
                                            <!-- Row -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="amount">Enter Amount</label>
                                                        <input type="number" class="form-control" name="amount"
                                                            value="<?php echo $amount; ?>" id="amount"
                                                            aria-describedby="amount" placeholder="Enter amount">
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                            </div>
                                            <!-- /row -->
                                            <!-- Row -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="fname">Select Plan</label>
                                                        <select class="form-control" name="plan" id="simple-select">
                                                            <?php foreach ($plans as $plan) { ?>
                                                            <option value="<?php echo $plan->id ?>"
                                                                <?php if ($planId == $plan->id) echo 'SELECTED'; ?>>
                                                                <?php echo $plan->name.' at '.$plan->profit.'% '.$plan->periodName.' investment: '.to_currency($plan->minInvestment).' - '.to_currency($plan->maxInvestment)  ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                            </div>
                                            <!-- /row -->
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Row -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="fname">Payment Method</label>
                                                        <select name="payMethod" class="form-control"
                                                            id="simple-select">
                                                            <option value="manual"
                                                                <?php if (strtolower($payMethod) == 'manual') echo 'SELECTED'; ?>>
                                                                Direct Deposit</option>
                                                            <?php foreach($paymentMethods as $method) {?>
                                                            <option value="<?php echo $method->name ?>"
                                                                <?php if (strtolower($payMethod) == strtolower($method->name)) echo 'SELECTED'; ?>>
                                                                <?php echo $this->security->xss_clean($method->name) ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="date">Deposit date</label>
                                                        <input type="text" class="form-control" name="date"
                                                            value="<?php echo $date; ?>" id="date"
                                                            aria-describedby="date" placeholder="YYYY-MM-DD">
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                            </div>
                                            <!-- /row -->
                                        </div>
                                        <!-- Form Group -->
                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-primary text-uppercase">Save</button>
                                        </div>
                                        <!-- /form group -->
                                </form>
                                <!-- /form -->
                            </div>
                            <!-- /card body -->
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

<!-- /site content -->
<script src="<?php echo base_url('/assets/dist/js/functions.js') ?>"></script>