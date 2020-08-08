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
                    <h1 class="dt-page__title text-white display-i"><?php echo $pageTitle?></h1>
                    <?php if($pageTitle == 'Withdrawals'){?>
                        <?php if($role == ROLE_CLIENT) { ?>
                        <a href="<?php echo base_url(); ?>withdrawals/new" class="btn btn-light btn-sm display-i ft-right">Make a withdrawal</a>
                        <?php } ?>
                    <?php } else if($pageTitle == 'Deposits'){?>
                        <?php if($role == ROLE_ADMIN) { ?>
                    <a href="<?php echo base_url(); ?>deposits/new" class="btn btn-light btn-sm display-i ft-right">Make a deposit</a>
                    <?php } } ?>
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
                            <?php if($pageTitle == 'Earnings') {?>
                            <div class="col-sm-4 col-12">
                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">
                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">Principal Repayments</span>
                                        <!-- Media -->
                                        <div class="media">
                                            <i class="icon icon-revenue icon-6x mr-6 align-self-center"></i>
                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php echo $principalEarnings > 0 ? to_currency($principalEarnings) : to_currency('0.00'); ?></div>
                                                <span class="d-block">
                                                    Principal Repayments</span>
                                            </div>
                                            <!-- /media body -->
                                        </div>
                                        <!-- /media -->
                                    </div>
                                    <!-- /card body -->
                                </div>
                                <!-- /card -->
                            </div>
                            <?php }?>
                            <!-- Grid Item -->
                            <div class="col-sm-<?php echo $pageTitle == 'Earnings' ? '4': '6'; ?> col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                        <?php if($role == ROLE_CLIENT) { ?>
                                            <?php if($pageTitle == 'Withdrawals'){
                                                echo "Pending withdrawals";
                                            } else if($pageTitle == 'Deposits'){
                                                echo "Active Deposits";
                                            } else if($pageTitle == 'Earnings'){
                                                echo "Interest Earnings";
                                            } 
                                        } else if($role == ROLE_ADMIN OR $role == ROLE_MANAGER) {?>
                                        <?php if($pageTitle == 'Withdrawals'){
                                                echo "Pending withdrawals";
                                            } else if($pageTitle == 'Deposits'){
                                                echo "Active Deposits";
                                            } else if($pageTitle == 'Earnings'){
                                                echo "Interest Earnings";
                                            } 
                                        }?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-revenue icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php if($pageTitle == 'Withdrawals'){
                                                    echo $withdrawalsInfo > 0 ? to_currency($withdrawalsInfo) : to_currency('0.00');
                                                    } else if($pageTitle == 'Deposits'){
                                                        echo $activeDeposits > 0 ? to_currency($activeDeposits) : to_currency('0.00');
                                                    } else if($pageTitle == 'Earnings'){
                                                        echo $interestEarnings > 0 ? to_currency($interestEarnings): to_currency('0.00');
                                                    } ?>
                                                </div>
                                                <span class="d-block">
                                                    <?php if($pageTitle == 'Withdrawals'){
                                                    echo "withdrawals pending";
                                                    } else if($pageTitle == 'Deposits'){
                                                        echo "Locked deposits";
                                                    } else if($pageTitle == 'Earnings'){
                                                        echo "Interest Earnings";
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

                            <!-- Grid Item -->
                            <div class="col-sm-<?php echo $pageTitle == 'Earnings' ? '4': '6'; ?> col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                            <?php if($pageTitle == 'Withdrawals'){
                                                echo $role == ROLE_ADMIN ? "Next Payments Due" : "Total Earnings";;
                                            } else if($pageTitle == 'Deposits'){
                                                echo "Inactive Deposits";
                                            } else if($pageTitle == 'Earnings'){
                                                echo "Referral Earnings";
                                            } ?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-revenue-new icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php if($pageTitle == 'Withdrawals'){
                                                    echo $earningsInfo > 0 ? to_currency($earningsInfo) : to_currency('0.00');
                                                } else if($pageTitle == 'Deposits'){
                                                    echo $inActiveDeposits > 0 ? to_currency($inActiveDeposits) : to_currency('0.00');
                                                } else if($pageTitle == 'Earnings'){
                                                    echo $referralEarnings > 0 ? to_currency($referralEarnings) : to_currency('0.00');
                                                } ?>
                                                </div>
                                                <span class="d-block">
                                                    <?php if($pageTitle == 'Withdrawals'){
                                                    echo $role == ROLE_ADMIN ? "Next Payments Due" : "Total Earnings to date";
                                                    } else if($pageTitle == 'Deposits'){
                                                        echo "All Inactive Deposits";
                                                    } else if($pageTitle == 'Earnings'){
                                                        echo "Referral Earnings";
                                                    }?>
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
            <div class="profile-content">

                <!-- Grid -->
                <div class="row">

                    <!-- Grid Item -->
                    <div class="col-xl-12 col-12 order-xl-1">
                        <!-- Card -->
                        <div class="dt-card">
                        <?php if(!empty($userInfo->pmtAccount) OR $role == ROLE_ADMIN OR $role == ROLE_MANAGER) {?>
                            <div class="dt-card__body">
                            <!-- Card Body -->
                                <!-- Tables -->
                                <div class="table-responsive dataTables_wrapper dt-bootstrap4">
                                    <div class="table-responsive">
                                    <?php if(!empty($transactions))
                                            { ?>
                                        <table class="table table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr role="row">
                                                    <th>Transaction ID</th>
                                                    <?php if($role == ROLE_ADMIN OR $role == ROLE_MANAGER) { ?>
                                                    <th>Client</th>
                                                    <?php } ?>
                                                    <th>Amount</th>
                                                    <?php if($pageTitle == 'Withdrawals'){?>
                                                    <th>Status</th>
                                                    <?php }?>
                                                    <?php if($pageTitle == 'Earnings'){?>
                                                    <th>Type</th>
                                                    <?php }?>
                                                    <th>Created On</th>
                                                    <?php if($pageTitle == 'Deposits'){?>
                                                        <th>Maturity Date</th>
                                                    <?php }?>
                                                    <?php if(($pageTitle == 'Deposits' || $pageTitle == 'Withdrawals') && ($role == ROLE_ADMIN OR $role == ROLE_MANAGER)){?>
                                                    <th class="text-center"></th>
                                                    <?php } else if($pageTitle == 'Deposits') {?>
                                                    <th>Status</th>
                                                    <?php }?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach($transactions as $transaction)
                                                { 
                                                ?>
                                                <tr id="row<?php echo $transaction->id ?>">
                                                    <td><?php echo $transaction->txnCode ?></td>
                                                    <?php if($role == ROLE_ADMIN OR $role == ROLE_MANAGER) { ?>
                                                    <td><?php echo $transaction->firstName.' '.$transaction->lastName ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td><?php echo to_currency($transaction->amount) ?></td>
                                                    <?php if($pageTitle == 'Withdrawals'){?>
                                                    <td><?php echo $transaction->status == 0 ? 'pending' : 'completed' ?>
                                                    </td>
                                                    <?php }?>
                                                    <?php if($pageTitle == 'Earnings'){?>
                                                    <td><?php echo $transaction->type; ?></td>
                                                    <?php }?>
                                                    <td><?php echo date("d-m-Y", strtotime($transaction->createdDtm)) ?>
                                                    </td>
                                                    <?php if($pageTitle == 'Deposits'){?>
                                                    <td><?php echo $transaction->maturityDtm; ?></td>
                                                    <!-- Check the access for this component -->
                                                    <?php if($this->user_model->getPermissions('deposits', 'edit', $userId) OR $role == ROLE_ADMIN) {?>
                                                    <td>
                                                    <?php if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && $transaction->status == 1) {?>
                                                    Withdrawn
                                                    <?php } else if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && $transaction->status == 0) {?>
                                                    Deposit Matured
                                                    <?php } else {?>
                                                        <a class="btn btn-sm btn-info trans-btn" href="<?php echo base_url().'deposits/editTrans/'.$transaction->id; ?>" title="Delete">Edit</a> |
                                                        <button class="btn btn-sm btn-danger confirmAction trans-btn" title="Delete" id="confirmButton<?php echo $transaction->id ?>" value="<?php echo base_url('deposits/deleteTrans/').$transaction->id ?>" data-toggle="modal" data-target="#confirmationModal">Delete</a>
                                                    <?php }?>
                                                    </td>
                                                    <?php }?>
                                                    <?php if($role == ROLE_CLIENT) {?>
                                                    <td class="collastcl" id="col<?php echo $transaction->txnCode ?>">
                                                    <?php if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && $transaction->status == 0) {?>
                                                        <button class="btn btn-sm btn-info reinvest trans-btn" id="reinvest<?php echo $transaction->txnCode ?>" data-toggle="modal" value="<?php echo $transaction->txnCode ?>" data-target="#modal">Re-invest</button>
                                                        <button data-toggle="modal" id="<?php echo $transaction->txnCode ?>" data-target="#modal" value="<?php echo to_currency($transaction->amount) ?>" class="btn btn-sm btn-info withdraw trans-btn">Withdraw</button>
                                                    <?php } else if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && $transaction->status == 1) {?>
                                                    Withdrawn
                                                    <?php } else if(($transaction->maturityDtm > date('Y-m-d H:i:s')) && $transaction->status == 0) {?>
                                                    Pending Maturity
                                                    <?php } else if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && $transaction->status == 3) {?>
                                                    Maturity Reached
                                                    <?php } else if(($transaction->maturityDtm > date('Y-m-d H:i:s')) && $transaction->status == 3) {?>
                                                    Pending Maturity
                                                    <?php }?>
                                                    </td>
                                                    <?php } ?>
                                                    <?php } ?>
                                                    <?php if($pageTitle == 'Withdrawals' && ($role == ROLE_ADMIN OR $role == ROLE_MANAGER)) {?>
                                                    <td class="text-center p-0-5m">
                                                        <?php if($transaction->status == 0){?>
                                                        <!-- Check the access for this component -->
                                                        <?php if($this->user_model->getPermissions('withdrawals', 'approve', $userId) OR $role == ROLE_ADMIN) {?>
                                                        <button class="btn btn-sm btn-info confirmAction trans-btn"
                                                            data-userid="" title="accept" 
                                                            id="confirmButton<?php echo $transaction->id ?>" 
                                                            value="<?php echo base_url('approveWithdrawal/').$transaction->id ?>" 
                                                            data-toggle="modal" 
                                                            data-target="#confirmationModal">Approve</button>
                                                        <?php } else { ?>
                                                        Pending Payment
                                                        <?php } } ?>  
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->pagination->create_links(); ?>
                                        <?php } else { ?>
                                        <div class="text-center mt-5">
                                            <img src="<?php echo base_url('assets/dist/img/no-search-results.png') ?>" class="w-20rm">
                                            <h1>No transactions can be found</h1>
                                        </div>
                                        <?php }?>
                                    </div>
                                    <!-- /tables -->

                                </div>
                                <!-- /card body -->
                                <?php } else {?>
                                <div class="card-body">

                                    <!-- Card Title-->
                                    <h2 class="card-title">No payment account on record</h2>
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
                        <?php if($role == ROLE_ADMIN) {?>
                        <div class="dt-card__body">

                            <!-- Modal -->
                            <div class="modal fade display-n" id="confirmationModal" tabindex="-1" role="dialog"
                                aria-labelledby="model-8" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">

                                    <!-- Modal Content -->
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="model-8">Enter
                                                Password To Proceed</h3>
                                            <button type="button" class="close"
                                                data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <!-- /modal header -->

                                        <?php echo form_open(base_url() , array( 'class' => 'confirm-form' ));?>
                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <input type="hidden" id="transID" name="id"/>
                                            <div class="form-group">
                                                <input class="form-control" name="password"
                                                    id="password" type="password">
                                            </div>

                                        </div>
                                        <!-- /modal body -->

                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                            <button type="button"
                                                class="btn btn-secondary btn-sm"
                                                data-dismiss="modal">Cancel
                                            </button>
                                            <button type="submit"
                                                class="btn btn-primary btn-sm" id="buttonSubmit">Save changes
                                            </button>
                                        </div>
                                        <!-- /modal footer -->
                                        <?php echo form_close();?>

                                    </div>
                                    <!-- /modal content -->

                                </div>
                            </div>
                            <!-- /modal -->
                        </div>
                        <?php }?>

                    </div>
                    <!-- /grid -->

                </div>
                <!-- /profile content -->

            </div>
            <!-- /Profile -->

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade display-n" id="modal" tabindex="-1" role="dialog"
        aria-labelledby="model-8" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Content -->
            <div class="modal-content">
                <form method="post" id="modalForm" role="form">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h3 class="modal-title" id="model-8"></h3>
                        <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <!-- /modal header -->
                    <!-- Modal Body -->
                    <!-- Form Group -->
                    <div class="modal-body mb--10 hide" id="reinvestPlans">
                        <div class="form-group">
                            <label for="fname">Select Investment Plan</label>
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
                    </div>
                    <!-- /form group -->
                    <div class="modal-body" id="modalBody">
                    </div>
                    <!-- /modal body -->
                    <!-- Modal Footer -->
                    <div class="modal-footer" id="modalFooter">
                        <button type="button"
                            class="btn btn-secondary btn-sm"
                            data-dismiss="modal">Cancel now
                        </button>
                        <div id="continue"></div>
                    </div>
                    <!-- /modal footer -->
                </form>

            </div>
            <!-- /modal content -->
        </div>
    </div>
    <!-- /modal -->
    <script src="<?php echo base_url('/assets/dist/js/trans.js') ?>"></script>