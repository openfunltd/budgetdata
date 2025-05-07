<?php
$data = (object) [
    'unit_id' => $this->unit_id,
    'unit_name' => $this->unit_name,
    'sub_unit' => $this->sub_unit,
    'sub_units' => $this->sub_units,
    'year' => $this->input_year,
    'project_code' => $this->project_code,
    'breadcrumbs' => $this->breadcrumbs,
    'year_data' => $this->year_data,
];
?>
<?= $this->partial("budget_proposal/unit_{$this->entity}", ['data' => $data]) ?>
