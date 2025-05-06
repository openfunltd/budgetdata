<?php
$data = $this->data;
$unit_id = $data->unit_id;
$unit_name = $data->unit_name;
$year = $data->year;
$sub_unit = $data->sub_unit;
$sub_units = $data->sub_units;
$project_code = $data->project_code;

$query = "/proposed_budget_project/{$unit_id}-{$year}-{$sub_unit}-{$project_code}";
$ret = BudgetAPI::apiQuery($query, "查詢工作計畫 id:{$unit_id}-{$year}-{$sub_unit}-{$project_code}");
$project_data = $ret->data;

$project_name = $project_data->工作計畫名稱;
$code_n_name = "{$project_code} {$project_name}";

$project_data->預算金額 = (filter_var($project_data->預算金額, FILTER_VALIDATE_INT))
    ? number_format($project_data->預算金額)
    : $project_data->預算金額;
//TODO 找地方標示「單位：新臺幣千元」

$basic_condition = "&單位代碼={$unit_id}&年度={$year}&工作計畫編號={$project_code}";
$basic_reason = "單位代碼: {$unit_id}, 年度: {$year}, 工作計畫編號: {$project_code}"; 
if (count($sub_units) > 1) {
    $h1_text = "歲出計畫提要及分支計畫概況表 - {$sub_unit} - {$project_code} {$project_name}"; 
    $basic_condition .= "&單位={$sub_unit}";
    $basic_reason .= ", 單位: {$sub_unit}";
} else {
    $h1_text = "歲出計畫提要及分支計畫概況表 - {$project_code} {$project_name}"; 
}

//查詢分支計畫
$query = "/proposed_budget_branch_projects?limit=1000" . $basic_condition;
$reason = "查詢分支計畫 " . $basic_reason;
$ret = BudgetAPI::apiQuery($query, $reason);
$branch_projects = $ret->proposedbudgetbranchprojects;

//查詢子分支計畫
$query = "/proposed_budget_sub_branch_projects?limit=1000" . $basic_condition;
$reason = "查詢子分支計畫 " . $basic_reason; 
$ret = BudgetAPI::apiQuery($query, $reason);
$sub_branch_projects = $ret->proposedbudgetsubbranchprojects;

$rows = ProjectHelper::toRows($branch_projects, $sub_branch_projects);
?>
<?= $this->partial('common/header', ['title' => $h1_text]) ?>
<?= $this->partial('partial/content-header', ['h1_text' => $h1_text, 'breadcrumbs' => $data->breadcrumbs]) ?>
<div class="content mx-2 mt-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- project basic info -->
        <table class="table table-bordered table-sm table-hover">
          <tbody>
            <tr>
              <td width="15%">工作計畫名稱及編號</td>
              <td><?= $this->escape($code_n_name) ?></td>
            </tr>
            <tr>
              <td>預算金額</td>
              <td><?= $this->escape($project_data->預算金額) ?></td>
            </tr>
            <tr>
              <td>計畫內容</td>
              <td><?= $this->escape($project_data->計畫內容) ?></td>
            </tr>
            <tr>
              <td>預期成果</td>
              <td><?= $this->escape($project_data->預期成果) ?></td>
            </tr>
          </tbody>
        </table>
        <!-- end of project basic info -->
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <!-- project table -->
        <table id="project-table" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th rowspan="2"></th>
              <th class="text-center" colspan="3" data-dt-order="disable">分支計畫及用途別科目</th>
              <!-- TODO make 金額, 承辦單位 horizontally center -->
              <th class="text-center" rowspan="2">金額</th>
              <th rowspan="2">承辦單位</th>
              <th rowspan="2">說明</th>
            </tr>
            <tr>
              <th class="text-center">分支計畫</th>
              <th class="text-center">子分支計畫</th>
              <th class="text-center">子分支計畫</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row) { ?>
              <tr>
                <td></td>
                <td width="15%"><?= $this->escape($row[0]) ?></td>
                <td width="15%"><?= $this->escape($row[1]) ?></td>
                <td width="20%"><?= $this->escape($row[2]) ?></td>
                <td><?= $this->escape($row[3]) ?></td>
                <td><?= $this->escape($row[4]) ?></td>
                <td><?= $this->escape($row[5]) ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <!-- end of project table -->
      </div>
    </div>
  </div>
</div>
<?= $this->partial('common/footer') ?>
<script src="/static/js/budget_proposal/project.js"></script>
