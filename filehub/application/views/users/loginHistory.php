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
                    <h1 class="dt-page__title text-white display-i">Track login history</h1>
                    <a href= "javascript:history.back()" class="btn btn-light btn-sm display-i ft-right">Back</a>
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
                            <!-- Card Body -->
                            <div class="dt-card__body">
                                <!-- Tables -->
                                <div class="table-responsive dataTables_wrapper dt-bootstrap4">
                                    <div class="table-responsive">
                                        <span class="d-block">
                                        </span>
                                        <?php if(!empty($userRecords))
                                            { ?>
                                        <table class="table table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr role="row">
                                                    <th>IP</th>
                                                    <th>User Agent</th>
                                                    <th>Agent Full String</th>
                                                    <th>Platform</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($userRecords as $record)
                                                    {
                                                ?>
                                                <tr>
                                                    <td><?php echo $this->security->xss_clean($record->machineIp) ?></td>
                                                    <td><?php echo $this->security->xss_clean($record->userAgent) ?></td>
                                                    <td><?php echo $this->security->xss_clean($record->agentString) ?></td>
                                                    <td><?php echo $this->security->xss_clean($record->platform) ?></td>
                                                    <td><?php echo $this->security->xss_clean($record->createdDtm) ?></td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->pagination->create_links(); ?>
                                        <?php } else { ?>
                                        <div class="text-center mt-5">
                                            <img src="<?php echo base_url('assets/dist/img/no-search-results.png') ?>" class="w-20rm">
                                            <h1>No records can be found</h1>
                                        </div>
                                        <?php }?>
                                    </div>
                                    <!-- /tables -->
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