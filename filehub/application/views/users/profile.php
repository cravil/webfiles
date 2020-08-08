<?php
$userId = $this->security->xss_clean($userInfo->userId);
$fname = set_value('fname') == false ? $this->security->xss_clean($userInfo->firstName) : set_value('fname');
$lname = set_value('lname') == false ? $this->security->xss_clean($userInfo->lastName) : set_value('lname');
$email = set_value('email') == false ? $this->security->xss_clean($userInfo->email) : set_value('email');
$mobile = $this->security->xss_clean($userInfo->mobile);
$code = $this->security->xss_clean($userInfo->refCode);
$roleId = $this->security->xss_clean($userInfo->roleId);
$total = $this->security->xss_clean($accountInfo);
?>
<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">

            <!-- Profile Banner -->
            <div class="profile__banner">

                <!-- Profile Banner Top -->
                <div class="profile__banner-detail">
                    <!-- Avatar Wrapper -->
                    <div class="dt-avatar-wrapper">
                        <!-- Avatar -->
                        <img class="dt-avatar dt-avatar__shadow size-90 mr-sm-4"
                            src="<?php echo $ppic ?>" id="ppic" alt="Logo">
                        <!-- /avatar -->

                        <!-- Info -->
                        <div class="dt-avatar-info">
                            <span
                                class="dt-avatar-name display-4 mb-2 font-weight-light"><?php echo $fname.' '.$lname ?></span>
                            <span class="f-16"><?php echo $email ?></span>
                            <div class="dropdown mt-2">

                                <!-- Profile Pic Uploader -->
                                <?php echo form_open(base_url( 'Upload/upload_file' ) , array( 'id' => 'upload_form', 'enctype' => 'multipart/form-data' ));?>
                                    <div class="upload-btn-wrapper">
                                    <button class="dropdown-toggle no-arrow text-white bg-transparent border-n">
                                        <i class="icon icon-settings icon-xl mr-2"></i>
                                        <span class="d-sm-inline-block">Change Profile Pic</span>
                                    </button>
                                    <input type="file" name="profile-pic" id="imgInp"/>
                                    </div>
                                    <button id="ppic-save" type="submit" class="btn btn-info display-n bg-transparent border-n">
                                        <i class="icon icon-circle-add-o icon-xl mr-2"></i>
                                        <span class="d-sm-inline-block">Save Photo</span>
                                    </button>
                                <?php echo form_close();?>
                            </div>
                        </div>
                        <!-- /info -->
                    </div>
                    <!-- /avatar wrapper -->

                    <div class="ml-sm-auto">
                        <!-- List -->
                        <div class="col-sm-12">
                            <?php if($roleId == ROLE_CLIENT) { ?>
                            <!--
                            <div class="d-flex align-items-baseline mb-1">
                                <span
                                    class="display-2 font-weight-500 text-light mr-2"><?php //echo $accountInfo>0 ? to_currency($accountInfo) : to_currency('0.00') ?></span>
                            </div>
                            <p class="mb-0">Overall balance</p>
                            -->
                            <?php } else if ($roleId == ROLE_ADMIN) { ?>
                            <div class="d-flex align-items-baseline mb-1">
                                <span class="display-2 font-weight-500 text-white mr-2">Admin</span>
                            </div>
                            <?php } else if ($roleId == ROLE_MANAGER) { ?>
                            <div class="d-flex align-items-baseline mb-1">
                                <span class="display-2 font-weight-500 text-white mr-2">Manager</span>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- /list -->
                    </div>
                </div>
                <!-- /profile banner top -->

            </div>
            <!-- /profile banner -->

            <!-- Profile Content -->
            <div class="profile-content">

                <!-- Grid -->
                <div class="row">

                    <!-- Grid Item -->
                    <div class="col-xl-12 order-xl-1">

                        <!-- Card -->
                        <div class="card">

                            <!-- Card Header -->
                            <div
                                class="card-header card-nav bg-transparent border-bottom d-sm-flex justify-content-sm-between">
                                <h3 class="mb-2 mb-sm-n5">Account Details</h3>

                                <ul class="card-header-links nav nav-underline" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tab-pane1" role="tab"
                                            aria-controls="tab-pane1" aria-selected="true">My Profile</a>
                                    </li>
                                    <?php if ($roleId == ROLE_CLIENT) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-pane3" role="tab"
                                            aria-controls="tab-pane3" aria-selected="true">Payment account</a>
                                    </li>
                                    <?php } ?>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-pane2" role="tab"
                                            aria-controls="tab-pane2" aria-selected="true">Security</a>
                                    </li>
                                </ul>

                            </div>
                            <!-- /card header -->

                            <!-- Card Body -->
                            <div class="card-body pb-2">

                                <!-- Tab Content-->
                                <div class="tab-content">

                                    <!-- Tab panel -->
                                    <div id="tab-pane1" class="tab-pane active">
                                        <!-- Grid -->
                                        <div class="row">
                                            <!-- Grid Item -->
                                            <div class="col-xl-12">
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

                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'profileUpdate' ) , array( 'id' => 'editProfile' ));?>
                                                    <!-- Row -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="fname">First Name</label>
                                                                        <input type="text" value="<?php echo $fname; ?>"
                                                                            name="fname" class="form-control <?php echo form_error('fname') == TRUE ? 'inputTxtError' : ''; ?>" id="fname"
                                                                            aria-describedby="fname" placeholder="First name">
                                                                        <label class="error" for="fname"><?php echo form_error('fname'); ?></label>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="lname">Last Name</label>
                                                                        <input type="text" value="<?php echo $lname; ?>"
                                                                            name="lname" class="form-control <?php echo form_error('lname') == TRUE ? 'inputTxtError' : ''; ?>" id="lname"
                                                                            aria-describedby="lname" placeholder="Last name">
                                                                        <label class="error" for="fname"><?php echo form_error('lname'); ?></label>
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
                                                                        <label for="email">Email</label>
                                                                        <input type="email" value="<?php echo $email; ?>"
                                                                            class="form-control <?php echo form_error('email') == TRUE ? 'inputTxtError' : ''; ?>" name="email" id="email"
                                                                            aria-describedby="email" placeholder="Email">
                                                                        <label class="error" for="fname"><?php echo form_error('email'); ?></label>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="phone">Phone</label>
                                                                        <input type="tel" value="<?php echo $mobile; ?>"
                                                                            class="form-control <?php echo form_error('phone') == TRUE ? 'inputTxtError' : ''; ?>" name="phone" id="phone"
                                                                            aria-describedby="phone">
                                                                        <input type="hidden" name="phonefull" id="phonefull" />
                                                                        <label class="error" for="phone"><?php echo form_error('phone'); ?></label>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group mb-10">
                                                                        <button type="button" class="btn btn-info text-uppercase w-100"
                                                                            data-toggle="modal" data-target="#form-modal">Save</button>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                            </div>
                                                            <!-- /row -->
                                                        </div>
                                                        <div class="col-md-6">
                                                            <?php if ($roleId == ROLE_CLIENT) { ?>
                                                            <!-- Row -->
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="ref">Referral code</label>
                                                                        <p class="display-4"><?php echo $code ?>
                                                                            <p>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                            </div>
                                                            <!-- /row -->
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="dt-card__body">
                                                        <!-- Modal -->
                                                        <div class="modal fade display-n" id="form-modal" tabindex="-1" role="dialog"
                                                            aria-labelledby="model-8" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">

                                                                <!-- Modal Content -->
                                                                <div class="modal-content">

                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h3 class="modal-title" id="model-8">Enter
                                                                            Password</h3>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <!-- /modal header -->

                                                                    <!-- Modal Body -->
                                                                    <div class="modal-body">
                                                                        <form>
                                                                            <div class="form-group">
                                                                                <input class="form-control <?php echo form_error('password') == TRUE ? 'inputTxtError' : ''; ?>" name="password"
                                                                                    id="password" type="password">
                                                                                <label class="error" for="fname"><?php echo form_error('password'); ?></label>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- /modal body -->

                                                                    <!-- Modal Footer -->
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary btn-sm"
                                                                            data-dismiss="modal">Cancel
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm">Save changes
                                                                        </button>
                                                                    </div>
                                                                    <!-- /modal footer -->

                                                                </div>
                                                                <!-- /modal content -->

                                                            </div>
                                                        </div>
                                                        <!-- /modal -->
                                                    </div>
                                                <?php echo form_close();?>
                                                <!-- /form -->
                                            </div>
                                            <!-- /grid item -->
                                        </div>
                                        <!-- /grid -->
                                    </div>
                                    <!-- /tab panel -->
                                    <?php if ($roleId == ROLE_CLIENT) { ?>
                                    <!-- Tab panel -->
                                    <div id="tab-pane3" class="tab-pane">
                                        <!-- Grid -->
                                        <div class="row">
                                            <!-- Grid Item -->
                                            <div class="col-xl-6">
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
                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'paymentInfo' ));?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="paymentType">Type</label>
                                                                        <select class="form-control" name="paymentType" id="simple-select">
                                                                            <option value="" selected disabled hidden>Select Payment Type</option>
                                                                            <?php foreach($withdrawalMethods as $method) {?>
                                                                                <option value="<?= $this->security->xss_clean($method->name) ?>" <?php echo $method->name == $userInfo->pmtType ? 'selected' : ''; ?>><?php echo $this->security->xss_clean($method->name) ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="bitcoinAd">Account</label>
                                                                        <input type="text"
                                                                            value="<?php echo $this->security->xss_clean($userInfo->pmtAccount) ?>"
                                                                            class="form-control" name="paymentAccount"
                                                                            aria-describedby="bitcoinAd"
                                                                            placeholder="Payment Account">
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                            </div>
                                                            <!-- /row -->
                                                        </div>
                                                    </div>

                                                    <!-- Form Group -->
                                                    <div class="form-group mb-0">
                                                        <button type="button" class="btn btn-primary text-uppercase"
                                                            data-toggle="modal" data-target="#form1-modal">Save</button>
                                                    </div>
                                                    <!-- /form group -->
                                                    <div class="dt-card__body">

                                                        <!-- Modal -->
                                                        <div class="modal fade display-n" id="form1-modal" tabindex="-1"
                                                            role="dialog" aria-labelledby="model-8" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered"
                                                                role="document">

                                                                <!-- Modal Content -->
                                                                <div class="modal-content">

                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h3 class="modal-title" id="model-8">Enter
                                                                            Password</h3>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <!-- /modal header -->

                                                                    <!-- Modal Body -->
                                                                    <div class="modal-body">
                                                                        <form>
                                                                            <div class="form-group">
                                                                            <input class="form-control <?php echo form_error('password') == TRUE ? 'inputTxtError' : ''; ?>" name="password"
                                                                                    id="password" type="password">
                                                                                <label class="error" for="fname"><?php echo form_error('password'); ?></label>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- /modal body -->

                                                                    <!-- Modal Footer -->
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary btn-sm"
                                                                            data-dismiss="modal">Cancel
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm">Save changes
                                                                        </button>
                                                                    </div>
                                                                    <!-- /modal footer -->
                                                                </div>
                                                                <!-- /modal content -->
                                                            </div>
                                                        </div>
                                                        <!-- /modal -->
                                                    </div>
                                                <?php echo form_close();?>
                                                <!-- /form -->

                                            </div>
                                            <!-- /grid item -->

                                        </div>
                                        <!-- /grid -->

                                    </div>
                                    <!-- /tab panel -->
                                    <?php } ?>

                                    <!-- Tab panel -->
                                    <div id="tab-pane2" class="tab-pane">
                                        <div class="row">
                                            <!-- Grid Item -->
                                            <div class="col-xl-6">
                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'resetPasswordUser' ) , array( 'id' => 'resetForm' ));?>
                                                    <input type="email" name="login_email" class="form-control"
                                                        value="<?php echo $email; ?>" hidden>
                                                    <input type="text" name="redirect" class="form-control" value="1"
                                                        hidden>
                                                    <button type="submit" class="btn btn-primary text-uppercase">Reset
                                                        Password</button>
                                                    <small id="emailHelp1" class="form-text mb-2m">An email will be sent with
                                                        instructions on how you can reset your password.</small>
                                                <?php echo form_close();?>
                                                <!-- /form -->
                                            </div>
                                            <!-- /grid item -->
                                        </div>
                                        <!-- /grid -->
                                    </div>
                                    <!-- /tab panel -->

                                </div>
                                <!-- /tab content-->

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
    <!-- /site content -->

<script src="<?php echo base_url('/assets/dist/js/profile.js') ?>"></script>
<script src="<?php echo base_url('/assets/dist/js/intlTelInput.js') ?>"></script>
<script src="<?php echo base_url('/assets/dist/js/utils.js') ?>"></script>
<script>
    $(document).ready(function () {
        var input = document.querySelector("#phone");
        window.intlTelInput(input,
        {
            separateDialCode: true,
            hiddenInput: "fullMobile"
        });
        $("form").submit(function() {
        });
    });
</script>