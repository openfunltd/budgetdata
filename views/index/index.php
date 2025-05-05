<?php
$h1_text = "首頁";
?>
<?= $this->partial('common/header', ['title' => $this->app_name]) ?>
<?= $this->partial('partial/content-header', ['h1_text' => $h1_text]) ?>
<div class="content px-2">
  <div class="container-fluid">
    <div class="row py-0 py-md-1">
      <div class="col-md-2 py-2 py-md-0">
        <a href="/budget_proposal" class="btn btn-block btn-outline-dark">預算案</a>
      </div>
      <div class="col-md-2 py-2 py-md-0">
        <a href="#" class="btn btn-block btn-outline-dark disabled">法定預算</a>
      </div>
      <div class="col-md-2 py-2 py-md-0">
        <a href="#" class="btn btn-block btn-outline-dark disabled">決算</a>
      </div>
    </div>
  </div>
</div>
<?= $this->partial('common/footer') ?>
