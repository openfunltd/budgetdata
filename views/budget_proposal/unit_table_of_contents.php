<?php
$data = $this->data;
$unit_id = $data->unit_id;
$unit_name = $data->unit_name;
$year = $data->year;
$h1_text = "預算案 - {$unit_name} - {$year} 年度"; 

//list budget projects
$ret = BudgetAPI::apiQuery(
    "/proposed_budget_projects?limit=1000&單位代碼={$unit_id}&年度={$year}&agg=單位",
    "查詢所有所屬單位的「歲出計畫提要及分支計畫概況表」"
);
$sub_units = $ret->aggs[0]->buckets;
$sub_units = array_map(function ($sub_unit) {
    return $sub_unit->單位;
}, $sub_units);
$url_project_template = "/budget_proposal/unit/{$unit_id}/project/%s?year={$year}&sub_unit=%s";
$projects = $ret->proposedbudgetprojects;
$projects = array_map(function ($project) use ($url_project_template, $is_multiple_sub_units){
    $code = $project->工作計畫編號;
    $name = $project->工作計畫名稱;
    $sub_unit = $project->單位;
    $project->code_n_name = "{$code} {$name}";
    $project->url = sprintf($url_project_template, $code, $sub_unit);
    return $project;
}, $projects);

//list expenditure_by_items_sub_units
$ret = BudgetAPI::apiQuery(
    "/proposed_budget_expenditure_by_items?limit=0&單位代碼={$unit_id}&年度={$year}&agg=單位",
    "查詢所有所屬單位的「各項費用彙計表列表」"
);
$expenditure_by_items_sub_units = $ret->aggs[0]->buckets;
$url_expenditure_by_items_template = "/budget_proposal/unit/{$unit_id}/expenditure_by_items?year={$year}&sub_unit=%s";
$expenditure_by_items_sub_units = array_map(function ($sub_unit) use ($url_expenditure_by_items_template){
   $sub_unit->url = sprintf($url_expenditure_by_items_template, $sub_unit->單位);
   return $sub_unit;
}, $expenditure_by_items_sub_units);

//urls
$url_template = "/budget_proposal/unit/{$unit_id}/%s?year={$year}";
$url_income_by_sources = sprintf($url_template, 'income_by_sources');
$url_expenditure_by_agencies = sprintf($url_template, 'expenditure_by_agencies');
$url_expenditure_by_policies= sprintf($url_template, 'expenditure_by_policies');
?>
<?= $this->partial('common/header', ['title' => $h1_text]) ?>
<?= $this->partial('partial/content-header', ['h1_text' => $h1_text, 'breadcrumbs' => $data->breadcrumbs]) ?>
<div class="content mx-2 mt-3">
  <div class="container-fluid">
    <h2 class="fs-4 my-3">歲出/歲入</h2>
    <ul>
      <li><a href="<?= $this->escape($url_income_by_sources) ?>">歲入來源別預算表</a></li>
      <li><a href="<?= $this->escape($url_expenditure_by_agencies) ?>">歲出機關別預算表</a></li>
      <!-- TODO 未來應改用打 API 確認有無「歲出政事別預算表」-->
      <?php if (in_array($unit_id,[371, 401])) { //核能安全委員會及所屬 and 立法院 ?>
        <li><a href="<?= $this->escape($url_expenditure_by_policies) ?>">歲出政事別預算表</a></li>
      <?php } ?>
    </ul>
    <h2 class="fs-4 my-3">歲出計畫提要及分支計畫概況表</h2>
    <?php if (count($sub_units) == 1) { ?>
      <ul>
        <?php foreach ($projects as $project) { ?>
          <li>
            <a href="<?= $this->escape($project->url) ?>">
              <?= $this->escape($project->code_n_name) ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    <?php } else { ?>
      <?php foreach ($sub_units as $sub_unit) { ?>
        <h3 class="fs-5"><?= $this->escape($sub_unit) ?></h3>
        <ul>
          <?php foreach ($projects as $project) { ?>
            <?php if ($project->單位 != $sub_unit) { continue; } ?>
            <li>
             <a href="<?= $this->escape($project->url) ?>">
                <?= $this->escape($project->code_n_name) ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      <?php } ?>
    <?php } ?>
    <h2 class="fs-4 my-3">各項費用彙計表</h2>
    <ul>
      <?php if (count($expenditure_by_items_sub_units) == 1) { ?>
        <li>
          <a href="<?= $this->escape($expenditure_by_items_sub_units[0]->url)?>">
            <?= $this->escape($expenditure_by_items_sub_units[0]->單位) ?>
          </a>
        </li>
      <?php } else { ?>
        <?php foreach ($expenditure_by_items_sub_units as $sub_unit) { ?>
          <li>
            <a href="<?= $this->escape($sub_unit->url)?>">
              <?= $this->escape($sub_unit->單位) ?>
            </a>
          </li>
        <?php } ?>
      <?php } ?>
    </ul>
  </div>
</div>
<?= $this->partial('common/footer') ?>
