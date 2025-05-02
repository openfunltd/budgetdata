<?php
$data = (object) [
    'unit_name' => $this->unit_name,
    'year' => $this->input_year,
    'breadcrumbs' => $this->breadcrumbs,
];
?>
<?= $this->partial("budget_proposal/unit_{$this->entity}", ['data' => $data]) ?>
