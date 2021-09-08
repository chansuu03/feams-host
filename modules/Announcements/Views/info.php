<?= $this->extend('adminlte') ?>

<?= $this->section('page_header') ?>
<div class="row mb-2">
    <div class="col-sm-6">
            <h1><?= esc($title)?></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url()?>/admin/announcements">Announcements</a></li>
            <li class="breadcrumb-item active"><?= esc($title)?></li>
        </ol>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if(!empty(session()->getFlashdata('failMsg'))):?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('failMsg');?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php endif;?>
<?php if(!empty(session()->getFlashdata('successMsg'))):?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('successMsg');?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php endif;?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <img src="<?= base_url()?>/public/uploads/announcements/<?= esc($announce['image'])?>" class="rounded img-fluid" alt="Announcement image">
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-6 text-justify">
            <?= esc($announce['description'], 'raw')?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<?= $this->endSection() ?>
    