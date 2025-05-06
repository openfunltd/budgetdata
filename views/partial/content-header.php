<!-- content-header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
       <h1><?= $this->escape($this->h1_text)?></h1>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-12">
        <!-- breadcrumb -->
        <ol class="breadcrumb">
          <?php if (!is_null($this->breadcrumbs)) { ?>
            <li class="breadcrumb-item" aria-current="page"><a href="/">首頁</a></li>
            <?php foreach ($this->breadcrumbs as $idx => $crumb) { ?>
              <li class="breadcrumb-item<?= $this->if($idx == count($this->breadcrumbs) - 1, ' active') ?>">
                <?php if (count($crumb) > 1) { ?>
                  <a href="<?= $this->escape($crumb[1]) ?>"><?= $this->escape($crumb[0]) ?></a>
                <?php } else { ?>
                  <?= $this->escape($crumb[0]) ?>
                <?php } ?>
              </li>
            <?php } ?>
          <?php } ?>
        </ol>
        <!-- end of breadcrumb -->
      </div>
    </div>
  </div>
</div>
<!-- end of content-header -->
