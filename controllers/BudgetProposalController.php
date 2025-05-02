<?php

class BudgetProposalController extends MiniEngine_Controller
{
    public function indexAction()
    {
        //
    } 

    public function unitAction($unit_id, $entity = 'table_of_contents')
    {
        //TODO check $entity is listed in $entites
        $this->view->entity = $entity;

        $this->view->unit_id = $unit_id;
        $ret = BudgetAPI::apiQuery("/unit/{$unit_id}", "查詢機關基本資料 unit_id: {$unit_id}");
        //TODO check unit exists via unit_id
        $unit_name = $ret->data->機關名稱;
        $this->view->unit_name = $unit_name;

        $input_year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);

        if ($entity == 'table_of_contents') {
            $ret = BudgetAPI::apiQuery(
                "/proposed_budget_expenditure_by_agencys?limit=0&agg=年度&單位代碼={$unit_id}",
                "取得 單位:{$unit_id} 年度列表"
            );
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

            $this->view->input_year = $input_year;
            $this->view->breadcrumbs = [
                ['預算案', '/budget_proposal'],
                [$unit_name],
                ["{$input_year} 年度"],
            ];
        }
    }
}
