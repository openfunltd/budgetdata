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
        $input_year = self::getInputYear($entity, $unit_id, $input_year);

        $this->view->input_year = $input_year;
        $this->view->breadcrumbs = self::getBreadcrumbs($entity, $unit_name, $unit_id, $input_year);
    }

    private static function getInputYear($entity, $unit_id, $input_year)
    {
        $endpoints = [
            'table_of_contents' => '/proposed_budget_expenditure_by_agencys',
            'income_by_sources' => '/proposed_budget_income_by_sources',
            'expenditure_by_agencies' => '/proposed_budget_expenditure_by_agencys',
            'expenditure_by_policies' => '/proposed_budget_expenditure_by_policys',
        ];

        $ret = BudgetAPI::apiQuery(
            "{$endpoints[$entity]}?limit=0&agg=年度&單位代碼={$unit_id}",
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

        return $input_year;
    }

    private static function getBreadcrumbs($entity, $unit_name, $unit_id, $input_year)
    {
        if ($entity == 'table_of_contents') {
            $breadcrumbs = [
                ['預算案', '/budget_proposal'],
                [$unit_name],
                ["{$input_year} 年度"],
            ];
        } else {
            $breadcrumbs = [
                ['預算案', '/budget_proposal'],
                [$unit_name, "/budget_proposal/unit/{$unit_id}?year={$input_year}"],
                ["{$input_year} 年度"],
            ];

            $titles = [
                'income_by_sources' => '歲入來源別預算表',
                'expenditure_by_agencies' => '歲出機關別預算表',
                'expenditure_by_policies' => '歲出政事別預算表',
            ];
            $breadcrumbs[] = [$titles[$entity]];
        }

        return $breadcrumbs;
    }
}
