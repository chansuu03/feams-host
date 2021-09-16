<?= $this->extend('adminlte') ?>

<?= $this->section('styles') ?>

<?= $this->endSection() ?>

<?= $this->section('page_header');?>
<div class="row mb-2">
    <div class="col-sm-6">
            <h1><?= esc($title)?></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('admin/elections')?>">Elections</a></li>
            <li class="breadcrumb-item active"><?= esc($title)?></li>
        </ol>
    </div>
</div>
<?= $this->endSection();?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header ">
    <strong>Election Title: </strong><?= esc($election['title'])?>
  </div>
  <div class="card-body">
    <div class="row d-flex justify-content-between ml-1">
      <span class="card-text">
        Vote dates: <?= esc(date('F d,Y g:iA',strtotime($election['vote_start'])))?> - <?= esc(date('F d,Y g:iA',strtotime($election['vote_end'])))?>
      </span>
      <span class="card-text">
        Vote count: <?= esc($voteCount)?>
      </span>
    </div>

    <div class="row mt-3">
      <!-- Full-time -->
      <div class="col-md-4">
        <h4 class="text-center">Top 4 Full-time</h4>
        <ul class="list-group">
          <?php $ctr = 0; foreach($votes as $vote):?>
            <?php if($vote['type'] == '1' && $ctr != 4):?>
              <li class="list-group-item d-flex justify-content-between">
                <span><?= esc($vote['first_name'])?> <?= esc($vote['last_name'])?></span>
                <span><?= esc($vote['voteCount'])?></span>
              </li>
              <?php $ctr++;?>
            <?php endif;?>
          <?php endforeach?>
        </ul>
      </div>
      <!-- Part-time -->
      <div class="col-md-4">
        <h4 class="text-center">Top 4 Part-time</h4>
        <ul class="list-group">
          <?php $ctr = 0; foreach($votes as $vote):?>
            <?php if($vote['type'] == '2' && $ctr != 4):?>
              <li class="list-group-item d-flex justify-content-between">
                <span><?= esc($vote['first_name'])?> <?= esc($vote['last_name'])?></span>
                <span><?= esc($vote['voteCount'])?></span>
              </li>
              <?php $ctr++;?>
            <?php endif;?>
          <?php endforeach?>
        </ul>
      </div>
      <!-- Admin -->
      <div class="col-md-4">
        <h4 class="text-center">Top 4 Admin</h4>
        <ul class="list-group">
          <?php $ctr = 0; foreach($votes as $vote):?>
            <?php if($vote['type'] == '3' && $ctr != 4):?>
              <li class="list-group-item d-flex justify-content-between">
                <span><?= esc($vote['first_name'])?> <?= esc($vote['last_name'])?></span>
                <span><?= esc($vote['voteCount'])?></span>
              </li>
              <?php $ctr++;?>
            <?php endif;?>
          <?php endforeach?>
        </ul>
      </div>
    </div>
    <!-- Voters -->
    <div class="row mt-2 ml-1">
      <h4 class="card-text">Voters</h4>
    </div>
    <div class="row mt-2">
      <div class="col">
        <ol>
          <?php foreach($voters as $voter):?>
            <li><?= $voter['first_name']?> <?= $voter['last_name']?></li>
          <?php endforeach?>
        </ol>
      </div>
    </div>
  </div>
  <?php if($election['status'] == 'Finished'):?>
    <div class="card-footer">
      <a class="btn btn-primary float-right" href="<?= base_url()?>/admin/elections/<?= esc($election['id'])?>/pdf" role="button" style="margin-right: 5px;">
        <i class="fas fa-download"></i> Generate PDF
      </a>
    </div>
  <?php endif;?>
</div>

<?= $this->endSection();?>

<?= $this->section('scripts') ?>

<script>
// BS4 tooltips
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
  
  // DataTables
  $(function () {
    $('#election_details').DataTable({
        "responsive": true,
        "autoWidth": false,
      });
  });
</script>
<?= $this->endSection();?>