<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $title; ?>
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        <?php $this->load->view('admin/'.$view); ?>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
<?php $this->load->view('admin/footer'); ?>

   