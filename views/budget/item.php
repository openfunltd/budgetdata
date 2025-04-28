<?= $this->partial('common/header', ['title' => $this->title . " | BudgetData"]) ?>
<div class="content-header">
  <h1>
    <?= $this->escape($this->content_header) ?>
  </h1>
</div>
<div class="content px-2">
  <div class="container-fluid">
    <!-- start of year tabs -->
    <div class="card card-dark card-outline card-outline-tabs">
      <!-- start of year tabs card-header -->
      <div class="card-header p-0 border-bottom-0">
        <ul id="year-tabs-four-tab" class="nav nav-tabs" role="tablist">
          <?php foreach ($this->years as $year) { ?>
            <?php $year = $this->escape($year); ?>
              <li class="nav-item">
                <a class="nav-link <?= $this->if($year == $this->input_year, 'active') ?>"
                  id="year-tabs-four-<?= $year ?>-tab" data-toggle="pill"
                  href="/budget/item/unit/<?= $this->escape($this->id) . "/{$year}" ?>" role="tab"
                  aria-controls="year-tabs-four-114" aria-selected="true"
                >
                  <?= $year ?> 年
                </a>
              </li>
          <?php } ?>
        </ul>
      </div>
      <!-- end of year tabs card-header -->
      <!-- start of card-body -->
      <div class="card-body">
      </div>
      <!-- end of card-body -->
    </div>
    <!-- end of year tabs -->
  </div>
</div>
<?= $this->partial('common/footer') ?>
