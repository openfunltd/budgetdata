<?php

class BudgetController extends MiniEngine_Controller
{
    public function itemAction($type, $id, $input_year = null)
    {
        $this->view->type = $type;
        $this->view->id = $id;

        $ret = BudgetAPI::apiQuery("/unit/{$id}", "查詢預算單位名稱 id: {$id}");
        $unit_name = $ret->data->機關名稱;

        //TODO check unit exists

        $ret = BudgetAPI::apiQuery("/proposed_budget_projects?limit=0&agg=年度&單位代碼={$id}", "取得 單位單位:{$id} 年度列表");
        $year_agg = $ret->aggs[0]->buckets;
        $years = array_map(function ($year) {
            return $year->年度;
        }, $year_agg);
        usort($years, function ($yearA, $yearB) {
            return $yearB <=> $yearA;
        });

        if (is_null($input_year)) {
            $input_year = $years[0];
        }

        //TODO check $input_year is in $years

        $this->view->title = "{$unit_name} {$input_year} 年度預決算";
        $this->view->content_header = "預決算 / {$unit_name}";
        $this->view->input_year = $input_year;
        $this->view->years = $years;
    }
}
