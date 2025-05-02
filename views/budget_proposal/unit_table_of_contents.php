<?php
$data = $this->data;
$unit_name = $data->unit_name;
$year = $data->year;
$h1_text = "預算案 - {$unit_name} - {$year} 年度"; 
?>
<?= $this->partial('common/header', ['title' => $h1_text]) ?>
<?= $this->partial('partial/content-header', ['h1_text' => $h1_text, 'breadcrumbs' => $data->breadcrumbs]) ?>
<div class="content px-2">
  <div class="container-fluid">
  </div>
</div>
<?= $this->partial('common/footer') ?>
