<?php

class ExpenditureByItemHelper
{
    public static function toRows($items, $projects)
    {
        //get 用途別科目編號(code) for each row
        $items = array_map(function($item) {
            $item->code = ($item->第二級用途別科目編號 == '') ? $item->第一級用途別科目編號 : $item->第二級用途別科目編號;
            return $item;
        }, $items);
        $codes = [];
        foreach ($items as $item) {
            $code = $item->code;
            if (!in_array($code, $codes)) {
                $codes[] = $code;
            }
        }

        //get data of each cell
        $rows = [];
        foreach ($codes as $code) {
            $row = [];
            $filtered_items = array_filter($items, function($item) use ($code) {
                return $item->code == $code;
            });
            $filtered_items = array_values($filtered_items);

            $row[] = $filtered_items[0]->第一級用途別科目編號;
            $row[] = $filtered_items[0]->第一級用途別科目名稱;
            $row[] = $filtered_items[0]->第二級用途別科目編號;
            $row[] = $filtered_items[0]->第二級用途別科目名稱;

            foreach ($projects as $project) {
                $project_item = array_filter($filtered_items, function($item) use ($project) {
                    return $item->工作計畫編號 == $project->工作計畫編號;
                });

                if (count($project_item) == 0) {
                    $row[] = '-'; //無「費用」資料
                } else {
                    $project_item = array_values($project_item)[0];
                    //prettified 費用 data
                    $row[] = (filter_var($project_item->費用, FILTER_VALIDATE_INT))
                        ? number_format($project_item->費用)
                        : $project_item->費用;
                }
            }
            $rows[] = $row;
        }

        return $rows;
    }
}
