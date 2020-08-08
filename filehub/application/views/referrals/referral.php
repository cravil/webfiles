
<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">

    <!-- Page Header -->
    <div class="dt-page__header">
        <h1 class="dt-page__title" style="display: inline;">My referrals</h1>
    </div>
    <!-- /page header -->

    <!-- Grid -->
    <div class="row">

        <!-- Grid Item -->
        <div class="col-xl-12">
            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <!-- Tables -->
                    <div class="table-responsive">

                        <div id="data-table_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <table id="data-table" class="table table-striped table-bordered table-hover dataTable" aria-describedby="data-table_info" role="grid">
                            <thead>
                            <tr role="row">
                                <th>Name</th>
                                <th>Interest Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                    if(!empty($transactions))
                    {
                        foreach($transactions as $transaction){
                    ?>
                    <tr>
                        <td><?php echo $transaction->firstName ?></td>
                        <td><?php echo $transaction->interest ?>%</td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                    </tbody>
                        </table>
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
    <!-- /site content -->
 