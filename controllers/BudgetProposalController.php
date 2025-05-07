<?php

class BudgetProposalController extends MiniEngine_Controller
{
    public function indexAction()
    {
        //
    } 

    public function unitAction($unit_id, $entity = 'table_of_contents', $project_code = null)
    {
        //TODO check $entity is listed in $entites
        $this->view->entity = $entity;

        $this->view->unit_id = $unit_id;
        $ret = BudgetAPI::apiQuery("/unit/{$unit_id}", "查詢機關基本資料 unit_id: {$unit_id}");
        //TODO check unit exists via unit_id
        $unit_name = $ret->data->機關名稱;
        $this->view->unit_name = $unit_name;

        $input_year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);
        $sub_unit = filter_input(INPUT_GET, 'sub_unit', FILTER_SANITIZE_STRING);

        $year_data = self::getInputYear($entity, $unit_id, $input_year, $sub_unit, $project_code);
        $input_year = $year_data->input_year;
        $sub_units = self::getSubUnits($sub_unit, $unit_id, $input_year);

        $this->view->year_data = $year_data;
        $this->view->input_year = $input_year;
        $this->view->sub_unit = $sub_unit;
        $this->view->sub_units = $sub_units;
        $this->view->project_code = $project_code;
        $this->view->breadcrumbs = self::getBreadcrumbs(
            $entity,
            $unit_name,
            $unit_id,
            $input_year,
            $sub_unit,
            $sub_units,
            $project_code,
        );
    }

    private static function getInputYear($entity, $unit_id, $input_year, $sub_unit, $project_code)
    {
        $endpoints = [
            'table_of_contents' => '/proposed_budget_expenditure_by_agencys',
            'income_by_sources' => '/proposed_budget_income_by_sources',
            'expenditure_by_agencies' => '/proposed_budget_expenditure_by_agencys',
            'expenditure_by_policies' => '/proposed_budget_expenditure_by_policys',
            'project' => '/proposed_budget_projects',
            'expenditure_by_items' => '/proposed_budget_expenditure_by_items',
        ];

        $url = "{$endpoints[$entity]}?limit=0&agg=年度&單位代碼={$unit_id}";
        if ($entity == 'project') {
            $url .= "&單位={$sub_unit}&工作計畫編號={$project_code}";
        }
        $ret = BudgetAPI::apiQuery($url, "取得 單位:{$unit_id} 年度列表");
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

        $base_url = "/budget_proposal/unit/{$unit_id}/{$entity}";
        if (isset($project_code)) {
            $base_url .= "/{$project_code}";
        }
        if (isset($sub_unit)) {
            $base_url .= "?sub_unit={$sub_unit}";
        }

        return (object) [
            'input_year' => $input_year,
            'years' => $years,
            'base_url' => $base_url,
        ];
    }

    private static function getBreadcrumbs($entity, $unit_name, $unit_id, $input_year, $sub_unit, $sub_units, $project_code)
    {
        if ($entity == 'table_of_contents') {
            $breadcrumbs = [
                ['預算案', '/budget_proposal'],
                [$unit_name],
                ["{$input_year} 年度"],
            ];
        } elseif ($entity == 'project') {
            if (count($sub_units) == 1) {
                $breadcrumbs = [
                    ['預算案', '/budget_proposal'],
                    [$unit_name, "/budget_proposal/unit/{$unit_id}?year={$input_year}"],
                    ["{$input_year} 年度"],
                    ['歲出計畫提要及分支計畫概況表'],
                    [$project_code],
                ];
            } else {
                $breadcrumbs = [
                    ['預算案', '/budget_proposal'],
                    [$unit_name, "/budget_proposal/unit/{$unit_id}?year={$input_year}"],
                    ["{$input_year} 年度"],
                    ['歲出計畫提要及分支計畫概況表'],
                    [$sub_unit],
                    [$project_code],
                ];
            }
        } elseif ($entity == 'expenditure_by_items') {
            $breadcrumbs = [
                ['預算案', '/budget_proposal'],
                [$unit_name, "/budget_proposal/unit/{$unit_id}?year={$input_year}"],
                ["{$input_year} 年度"],
                ['各項費用彙計表'],
                [$sub_unit],
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

    private static function getSubUnits($sub_unit, $unit_id, $input_year)
    {
        if (is_null($sub_unit)) {
            return [];
        }

        $ret = BudgetAPI::apiQuery(
            "/proposed_budget_projects?limit=0&單位代碼={$unit_id}&年度={$input_year}&agg=單位",
            "查詢機關:$unit_id 是否有其他所屬單位"
        );
        return $ret->aggs[0]->buckets;
    }
}
