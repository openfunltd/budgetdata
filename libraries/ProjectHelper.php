<?php

class ProjectHelper
{
    public static function toRows($branch_projects, $sub_branch_projects)
    {
        return self::getRows([], $sub_branch_projects, $branch_projects);
    }

    private static function toPrettyRow($project)
    {
        $row = [];
        $row[] = $project->分支計畫編號;
        $row[] = $project->分支計畫名稱;
        $row[] = (filter_var($project->金額, FILTER_VALIDATE_INT))
            ? number_format($project->金額)
            : $project->金額;
        $row[] = $project->承辦單位 ?? '';
        $row[] = $project->說明 ?? '';
        return $row;
    }

    private static function getRows($rows, $sub_branch_projects, $parent_projects)
    {
        foreach ($parent_projects as $parent_project) {
            $rows[] = self::toPrettyRow($parent_project);
            $parent_project_code = $parent_project->分支計畫編號;

            $child_projects = array_filter(
                $sub_branch_projects,
                function($project) use ($parent_project_code) {
                    return $project->母科目編號 == $parent_project_code;
                }
            );

            //recursion
            if (count($child_projects) > 0) {
                $rows = self::getRows($rows, $sub_branch_projects, $child_projects);
            }
        }

        return $rows;
    }
}
