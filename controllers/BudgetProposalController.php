<?php

class BudgetProposalController extends MiniEngine_Controller
{
    public function indexAction()
    {
        $this->view->breadcrumbs = [['機關']];
    } 
}
