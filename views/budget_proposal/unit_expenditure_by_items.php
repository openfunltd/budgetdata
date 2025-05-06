<?php
$data = $this->data;
$unit_id = $data->unit_id;
$unit_name = $data->unit_name;
$year = $data->year;
$sub_unit = $data->sub_unit;

$query = "/proposed_budget_expenditure_by_items?limit=1000&單位={$sub_unit}&年度={$year}";
$reason = "查詢各項費用彙計表列表資料 單位: {$sub_unit}, 年度= {$year}";
$ret = BudgetAPI::apiQuery($query, $reason);
$expenditure_by_items = $ret->proposedbudgetexpenditurebyitems;
$project_codes = [];
$projects = [];
foreach ($expenditure_by_items as $item) {
    $project_code = $item->工作計畫編號;
    if (!in_array($project_code, $project_codes)) {
        $project_codes[] = $project_code;
        $projects[] = (object) [
            '工作計畫編號' => $item->工作計畫編號,
            '工作計畫名稱' => $item->工作計畫名稱,
        ];
    }
}
$rows = ExpenditureByItemHelper::toRows($expenditure_by_items, $projects);

$h1_text = "各項費用彙計表 - {$unit_name}"; 
?>
<?= $this->partial('common/header', ['title' => $h1_text]) ?>
<?= $this->partial('partial/content-header', ['h1_text' => $h1_text, 'breadcrumbs' => $data->breadcrumbs]) ?>
<div class="content mx-2 mt-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <table id="expenditure-by-items-table" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th colspan="4" class="text-center">用途別科目名稱及編號</th>
              <?php foreach ($projects as $project) { ?>
                <th rowspan="2">
                  <?= $this->escape($project->工作計畫編號)?><br><?= $this->escape($project->工作計畫名稱)?>
                </th>
              <?php } ?>
            </tr>
              <th>第一級用途別科目編號</th>
              <th>第一級用途別科目名稱</th>
              <th>第二級用途別科目編號</th>
              <th>第二級用途別科目名稱</th>
            <tr>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row) { ?>
              <tr>
                <?php foreach ($row as $cell) { ?>
                  <td><?= $this->escape($cell) ?></td>
                <?php } ?>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->partial('common/footer') ?>
<script src="/static/js/budget_proposal/project.js"></script>
