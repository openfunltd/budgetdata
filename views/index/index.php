<?php
$ret = BudgetAPI::apiQuery(
    "/proposed_budget_expenditure_by_agencys?limit=0&agg=單位代碼",
    "透過「歲出機關別預算表」確認目前有哪些單位尚未有資料"
    );
$units_with_data = $ret->aggs[0]->buckets;
$units_with_data = array_map(function($unit) {
    return $unit->單位代碼;
}, $units_with_data);
?>
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
        <a href="/budget/item/unit/<?= $this->escape($unit->機關編號) ?>" class="btn btn-block btn-outline-dark">
          <?php if (!in_array($unit->機關編號, $units_with_data)) { ?>
            <i class="bi bi-x-square-fill"></i>
          <?php } ?>
          <?= $this->escape($unit->機關名稱) ?>
        </a>
      </div>
      <?php if ($idx % 6 == 5 or $idx == count($this->units) - 1) { ?>
        </div>
      <?php } ?>
    <?php } ?>
  </div>
</div>
<?= $this->partial('common/footer') ?>
