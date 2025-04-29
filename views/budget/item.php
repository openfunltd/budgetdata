<?= $this->partial('common/header', ['title' => $this->title . " | BudgetData"]) ?>
<div class="content-header">
  <h1>
    <?= $this->escape($this->content_header) ?>
  </h1>
</div>
<div class="content px-2">
  <div class="container-fluid">
    <!-- year tabs -->
    <ul class="nav nav-tabs" id="year-tab" role="tablist">
      <?php foreach ($this->years as $year) { ?>
        <?php $year = $this->escape($year); ?>
        <li class="nav-item">
          <a class="nav-link<?= $this->if($year == $this->input_year, ' active') ?>" id="year-<?= $year ?>-tab"
            data-toggle="pill" aria-selected="true" role="tab" aria-controls="year-<?= $year ?>"
            href="/budget/item/unit/<?= $this->escape($this->id) . "/{$year}" ?>"
          >
            <?= $year ?> 年
          </a>
        </li>
      <?php } ?>
    </ul>
    <!-- end of year tabs -->

    <!-- partial: partial/proposed_budget_income_by_source -->
    <?= $this->partial('partial/proposed_budget_income_by_source', ['unit_id' => $this->id, 'year' => $this->input_year]) ?>
    <!-- end of partial: partial/proposed_budget_income_by_source -->

    <!-- partial: partial/proposed_budget_expenditure_by_agency -->
    <?= $this->partial('partial/proposed_budget_expenditure_by_agency', ['unit_id' => $this->id, 'year' => $this->input_year]) ?>
    <!-- end of partial: partial/proposed_budget_expenditure_by_agency -->

    <!-- partial: partial/proposed_budget_expenditure_by_policy -->
    <?= $this->partial('partial/proposed_budget_expenditure_by_policy', ['unit_id' => $this->id, 'year' => $this->input_year]) ?>
    <!-- end of partial: partial/proposed_budget_expenditure_by_policy -->

  </div>
</div>
<?= $this->partial('common/footer') ?>
<script src="/static/js/proposed-budget.js"></script>
