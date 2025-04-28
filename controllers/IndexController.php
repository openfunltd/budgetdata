<?php

class IndexController extends MiniEngine_Controller
{
    public function indexAction()
    {
        $this->view->app_name = getenv('APP_NAME');
        $ret = BudgetAPI::apiQuery('/units?limit=1000&output_fields=機關編號&output_fields=機關名稱', '條列中央預算單位');
        $units = $ret->units;
        $this->view->units = $units;
    }

    public function robotsAction()
    {
        header('Content-Type: text/plain');
        echo "#\n";
        return $this->noview();
    }
}
