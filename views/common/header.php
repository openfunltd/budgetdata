<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $this->escape($this->title ?? 'BudgetData 預算統合資料網') ?></title>
<meta name="og:type" content="website">
<meta name="og:title" content="<?= $this->escape($this->title ?? 'BudgetData 預算統合資料網') ?>">
<meta name="og:description" content="<?= $this->escape($this->og_description ?? '幫助您快速查詢中央政府總預算的預決算資料') ?>">
<meta name="og:site_name" content="BudgetData 預算統合資料網">
<meta name="og:image" content="/static/images/og_budgetdata.png">
<?= $this->partial('partial/cdn_asset') ?>
</head>
<body class="layout-fixed" style="height: auto;">
  <div class="warpper">
    <?= $this->partial('partial/main-header', ['year_data' => $this->year_data]) ?>
    <?= $this->partial('partial/main-sidebar') ?>
    <div class="content-wrapper px-4 py-2" style="min-height: 1302.4px;">
