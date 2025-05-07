<?php
$year_data = $this->year_data;
$input_year = $year_data->input_year;
$years = $year_data->years;
$base_url = $year_data->base_url;
?>
<nav class="main-header navbar navbar-expand navbar-light">
  <!-- left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fa fa-bars"></i>
      </a>
    </li>
    <?php if (isset($year_data)) { ?>
      <li class="nav-item dropdown">
        <a id="navbarYearsDropdown" class="nav-link bg-info rounded dropdown-toggle" href="#" data-bs-toggle="dropdown">
          <?= $this->escape($input_year) ?> 年度
        </a>
        <div class="dropdown-menu py-0">
          <?php foreach ($years as $year) { ?>
            <?php if ($year == $input_year) { ?>
              <a class="dropdown-item disabled" href="#"><?= $this->escape($year) ?> 年度</a>
            <?php } else { ?>
              <?php
              if (strpos($base_url, '?') !== false) {
                  $url = $base_url . "&year={$year}";
              } else {
                  $url = $base_url . "?year={$year}";
              }
              ?>
              <a class="dropdown-item" href="<?= $this->escape($url) ?>"><?= $this->escape($year) ?> 年度</a>
            <?php }?>
          <?php } ?>
        </div>
      </li>
    <?php } ?>
  </ul>

  <!-- right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>
