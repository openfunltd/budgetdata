<?= $this->partial('common/header', ['title' => $this->app_name]) ?>
<div class="content-header">
  <h1>預算單位</h1>
</div>
<div class="content px-2">
  <div class="container-fluid">
    <?php foreach ($this->units as $idx => $unit) { ?>
      <?php if ($idx % 6 == 0) { ?>
        <div class="row py-1">
      <?php } ?>
      <div class="col-md-2">
        <button type="button p-1" class="btn btn-block btn-outline-dark">
          <?= $this->escape($unit->機關名稱) ?>
        </button>
      </div>
      <?php if ($idx % 6 == 5 or $idx == count($this->units) - 1) { ?>
        </div>
      <?php } ?>
    <?php } ?>
  </div>
</div>
<?= $this->partial('common/footer') ?>
