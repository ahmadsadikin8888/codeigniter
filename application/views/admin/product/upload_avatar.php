<?php $this->load->view("admin/_layout/top.php") ?>
<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<div class="card mb-3">
    <div class="card-header">
        <a href="<?php echo site_url('admin/products/') ?>"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Avatar</label>
                <input class="form-control-file" type="file" name="avatar" id="avatar" accept="image/png, image/jpeg, image/jpg, image/gif">
                <?php if (isset($error)) : ?>
                    <div class="invalid-feedback"><?= $error ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" name="save" class="btn btn-success">Upload</button>

        </form>

    </div>

    <div class="card-footer small text-muted">
        * required fields
    </div>

</div>
<?php $this->load->view("admin/_layout/bottom.php") ?>