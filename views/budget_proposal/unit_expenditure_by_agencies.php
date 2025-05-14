<?php
$data = $this->data;
$unit_id = $data->unit_id;
$unit_name = $data->unit_name;
$year = $data->year;
$h1_text = "歲出機關別預算表"; 
$year_data = $data->year_data;
$this->title = $h1_text . " - {$unit_name} {$year} 年度預算案";
$this->og_description = "支出預算按各主管機關分列的表格，用來顯示每個部會或機關預計花多少錢";
$this->year_date = $year_data;

$query = "/proposed_budget_expenditure_by_agencys?limit=1000&單位代碼={$unit_id}&年度={$year}";
$ret = BudgetAPI::apiQuery($query, "查詢單位: {$unit_id} {$year} 年的歲入來源別預算表");
$rows = $ret->proposedbudgetexpenditurebyagencys;

$query = "/proposed_budget_projects?limit=1000&單位代碼={$unit_id}&年度={$year}&單位={$unit_name}";
$reason = "查詢有無詳細的工作計劃 單位代碼: {$unit_id}, 年度: {$year}, 單位: {$unit_name}";
$ret = BudgetAPI::apiQuery($query, $reason);
$projects = $ret->proposedbudgetprojects;


$rows = array_map(function ($row) use ($projects, $unit_id, $year, $unit_name) {
    $row->款名 = str_replace("\n", '', $row->款名);
    $row->項名 = str_replace("\n", '', $row->項名);
    $row->目名 = str_replace("\n", '', $row->目名);
    $row->節名 = str_replace("\n", '', $row->節名);
    $row->名稱 = (!empty($row->節名)) ? $row->節名 : '';
    $row->名稱 = (empty($row->名稱) and !empty($row->目名)) ? $row->目名 : $row->名稱;
    $row->名稱 = (empty($row->名稱) and !empty($row->項名)) ? $row->項名 : $row->名稱;
    $row->名稱 = (empty($row->名稱) and !empty($row->款名)) ? $row->款名 : $row->名稱;
    $row->本年度預算數_formatted = (filter_var($row->本年度預算數, FILTER_VALIDATE_INT))
        ? number_format($row->本年度預算數)
        : $row->本年度預算數;
    $row->上年度預算數_formatted = (filter_var($row->上年度預算數, FILTER_VALIDATE_INT))
        ? number_format($row->上年度預算數)
        : $row->上年度預算數;
    $row->前年度決算數_formatted = (filter_var($row->前年度決算數, FILTER_VALIDATE_INT))
        ? number_format($row->前年度決算數)
        : $row->前年度決算數;
    $row->本年度與上年度比較_formatted = (filter_var($row->本年度與上年度比較, FILTER_VALIDATE_INT))
        ? number_format($row->本年度與上年度比較)
        : $row->本年度與上年度比較;

    $code = $row->編號;
    $has_detail = false;
    foreach ($projects as $project) {
        $project_code = $project->工作計畫編號;
        if ($project_code == $code) {
            $has_detail = true;
            break;
        }
    }
    $row->has_detail = $has_detail;
    $row->url_detail = sprintf("/budget_proposal/unit/%s/project/%s?year=%d&sub_unit=%s",
        $unit_id,
        $code,
        $year,
        $unit_name,
    );

    return $row;
}, $rows);
?>
<?= $this->partial('common/header', $this) ?>
<?= $this->partial('partial/content-header', ['h1_text' => $h1_text, 'breadcrumbs' => $data->breadcrumbs]) ?>
<div class="content mx-2 mt-3">
  <div class="container-fluid">
    <!-- expenditure_by_agencies -->
    <div class="row py-3">
      <div class="col-12">
        <table id="expenditure-by-agencies-table" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th></th>
              <th>款</th>
              <th>項</th>
              <th>目</th>
              <th>節</th>
              <th>編號</th>
              <th>名稱</th>
              <th>本年度預算數</th>
              <th>上年度預算數</th>
              <th>前年度決算數</th>
              <th>本年度與上年度比較</th>
              <th>說明</th>
              <th>詳細資料</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row) { ?>
              <tr>
                <td></td>
                <td><?= $this->escape($row->款) ?></td>
                <td><?= $this->escape($row->項) ?></td>
                <td><?= $this->escape($row->目) ?></td>
                <td><?= $this->escape($row->節) ?></td>
                <td><?= $this->escape($row->編號) ?></td>
                <td><?= $this->escape($row->名稱) ?></td>
                <td><?= $this->escape($row->本年度預算數_formatted) ?></td>
                <td><?= $this->escape($row->上年度預算數_formatted) ?></td>
                <td><?= $this->escape($row->前年度決算數_formatted) ?></td>
                <td><?= $this->escape($row->本年度與上年度比較_formatted) ?></td>
                <td><?= $this->escape($row->說明) ?></td>
                <td>
                  <?php if ($row->has_detail == true) { ?>
                    <a href="<?= $this->escape($row->url_detail) ?>">
                      <i class="fa-solid fa-eye"></i>
                    </a>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- end of expenditure_by_agencies -->
  </div>
</div>
<?= $this->partial('common/footer') ?>
<script src="/static/js/budget_proposal/expenditure_by_agencies.js"></script>
