<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">

    <!-- Page Header -->
    <div class="dt-page__header">
        <h1 class="dt-page__title" style="display: inline;">Edit Email</h1>
        <a href="<?php echo base_url(); ?>settings/email_templates" class="btn btn-light btn-sm" style="display: inline;float: right;">Back</a>
    </div>
    <!-- /page header -->

    <div class="row">
        <div class="col-12">
        <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
        </div>
    </div>

    <!-- Grid -->
    <div class="row">

                        <!-- Grid Item -->
                        <div class="col-12">

                            <!-- Card -->
                            <div class="dt-card">
                                <form method="post">
                                    <!-- Card Header -->
                                    <div class="dt-card__header">

                                        <!-- Card Heading -->
                                        <div class="dt-card__heading">
                                        <div class="form-group form-row">
                                            <label for="normal-input-3" class="col-md-1 col-sm-3 col-form-label col-form-label-lg text-sm-left">Subject:</label>
                                            <div class="col-md-11 col-sm-9">
                                                <input type="text" class="form-control" name="email_subject" id="normal-input-3" value="<?php echo $emailInfo->mail_subject; ?>" placeholder="placeholder">
                                            </div>
                                        </div>
                                        </div>
                                        <!-- /card heading -->

                                    </div>
                                    <!-- /card header -->

                                    <!-- Card Body -->
                                    <div class="dt-card__body">
                                    
                                        <textarea id="summernote" name="email_body"><?php echo $emailInfo->mail_body; ?></textarea>
                                        <button style="width: 100%;" type="submit" class="btn btn-primary text-uppercase">Save & Update</button>
                                    </div>
                                    <!-- /card body -->
                                    
                                </form>

                            </div>
                            <!-- /card -->

                        </div>
                        <!-- /grid item -->

                    </div>
    <!-- /grid -->

</div>
    <!-- /site content -->
 