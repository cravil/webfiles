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
                    <h1 class="dt-page__title text-white display-i"><?php echo $pageTitle; ?></h1>

                    <?php if($pageTitle == "Clients"){ ?>
                    <!-- Check the access for this component -->
                    <?php if($this->user_model->getPermissions('clients', 'add', $userId) OR $role == ROLE_ADMIN) {?>
                    <a href="<?php echo base_url(); ?>clients/newClient" class="btn btn-light btn-sm display-i ft-right">Add Client</a>
                    <?php }?>
                    <?php } else if ($pageTitle == "Team") { ?>
                    <!-- Check the access for this component -->
                    <?php if($this->user_model->getPermissions('teams', 'add', $userId) OR $role == ROLE_ADMIN) {?>
                    <a href="<?php echo base_url(); ?>team/newManager" class="btn btn-light btn-sm display-i ft-right">Add New Manager</a>
                    <?php }?>
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
                            <div class="col-sm-6 col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                            <?php if($pageTitle == "Clients") {?>
                                            Total Clients
                                            <?php } else if($pageTitle == "Team") { ?>
                                            Team Size
                                            <?php } ?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-users icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php echo $allUsers; ?>
                                                </div>
                                                <span class="d-block">
                                                    <?php if($pageTitle == "Clients") {?>
                                                    Registered Users
                                                    <?php } else if($pageTitle == "Team") { ?>
                                                    Team members
                                                    <?php } ?>
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
                            <div class="col-sm-6 col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                            <?php if($pageTitle == "Clients") {?>
                                            Users since last week
                                            <?php } else if($pageTitle == "Team") { ?>
                                            Last login
                                            <?php } ?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-users icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php if($pageTitle == "Clients") {
                                                     echo $clientsThisWeek;  }
                                                    else if($pageTitle == "Team") { 
                                                    echo date('Y-m-d', $lastLogin->timestamp) ;
                                                    } ?>
                                                </div>
                                                <span class="d-block">
                                                    <?php if($pageTitle == "Clients") {?>
                                                    New users since last week
                                                    <?php } else if($pageTitle == "Team") { ?>
                                                    Last manager login
                                                    <?php } ?>
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
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <?php if($pageTitle == "Clients"){ ?>
                                                    <th>Created On</th>
                                                    <?php } else if($pageTitle == "Team") {?>
                                                    <th>Role</th>
                                                    <?php } ?>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($userRecords as $record)
                                                    {
                                                ?>
                                                <tr>
                                                    <td><?php echo $this->security->xss_clean($record->firstName).' '.$this->security->xss_clean($record->lastName) ?></td>
                                                    <td><?php echo $this->security->xss_clean($record->email) ?></td>
                                                    <?php if($pageTitle == "Clients"){ ?>
                                                    <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                                    <?php } else if($pageTitle == "Team") {?>
                                                    <td><?php echo $record->role ?></td>
                                                    <?php } ?>

                                                    <td class="text-center">
                                                        <!-- Check the access for this component -->
                                                        <?php if($this->user_model->getPermissions('loginHistory', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                                        <a class="btn btn-sm btn-primary trans-btn"
                                                            href="<?= base_url().'login-history/'.$record->userId; ?>"
                                                            title="Login history">Login History</a> |
                                                        <?php }?>
                                                        <?php if($pageTitle == "Clients") {?>
                                                        <!-- Check the access for this component -->
                                                        <?php if($this->user_model->getPermissions('clients', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                                        <a class="btn btn-sm btn-info trans-btn"
                                                            href="<?php echo base_url().'clients/viewClient/'.$record->userId; ?>"
                                                            title="Edit">View</a>
                                                        <?php }?>
                                                        <?php } else if($pageTitle == "Team") {?>
                                                        <!-- Check the access for this component -->
                                                        <?php if($this->user_model->getPermissions('teams', 'edit', $userId) OR $role == ROLE_ADMIN) {?>
                                                        <a class="btn btn-sm btn-info trans-btn"
                                                            href="<?php echo base_url('team/editManager/').$record->userId; ?>"
                                                            title="Edit">Edit</a>
                                                        <?php }?>
                                                        <?php }?>
                                                    </td>
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