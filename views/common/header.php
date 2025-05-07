<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $this->escape($this->title) ?></title>
<?= $this->partial('partial/cdn_asset') ?>
</head>
<body class="layout-fixed" style="height: auto;">
  <div class="warpper">
    <?= $this->partial('partial/main-header', ['year_data' => $this->year_data]) ?>
    <?= $this->partial('partial/main-sidebar') ?>
    <div class="content-wrapper px-4 py-2" style="min-height: 1302.4px;">
