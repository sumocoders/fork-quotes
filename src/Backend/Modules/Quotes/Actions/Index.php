<?php

namespace Backend\Modules\Quotes\Actions;

use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Base\ActionIndex;
use Backend\Core\Engine\DataGridDB;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;

final class Index extends ActionIndex
{
    public function execute()
    {
        parent::execute();
        $this->loadDataGrid();
        $this->parse();
        $this->display();
    }

    private function loadDataGrid()
    {
        // create the datagrid
        $this->dataGrid = new DataGridDB(
            $this->get('quotes_repository')->getDataGridQuery(),
            ['language' => Language::getWorkingLanguage()]
        );

        // check if this action is allowed
        if (Authentication::isAllowedAction('Edit')) {
            // add column
            $this->dataGrid->addColumn(
                'edit',
                null,
                Language::lbl('Edit'),
                Model::createURLForAction('Edit') . '&amp;id=[id]',
                Language::lbl('Edit')
            );
        }

        $this->tpl->assign('dataGrid', (string) $this->dataGrid->getContent());
    }
}
