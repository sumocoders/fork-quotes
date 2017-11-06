<?php

namespace Backend\Modules\Quotes\Actions;

use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Base\ActionIndex;
use Backend\Core\Engine\DataGridDatabase;
use Backend\Core\Language\Language;
use Backend\Core\Engine\Model;
use SpoonFilter;

final class Index extends ActionIndex
{
    public function execute(): void
    {
        parent::execute();
        $this->loadDataGrid();
        $this->parse();
        $this->display();
    }

    private function loadDataGrid()
    {
        // create the datagrid
        $this->dataGrid = new DataGridDatabase(
            $this->get('quotes_repository')->getDataGridQuery(),
            ['language' => Language::getWorkingLanguage()]
        );

        // check if this action is allowed
        if (Authentication::isAllowedAction('Edit')) {
            // strip html form quote to fix layout issues
            $this->dataGrid->setColumnFunction(
                [new SpoonFilter(), 'stripHTML'],
                ['[quote]'],
                'quote',
                true
            );

            // add column
            $this->dataGrid->addColumn(
                'edit',
                null,
                Language::lbl('Edit'),
                Model::createURLForAction('Edit') . '&amp;id=[id]',
                Language::lbl('Edit')
            );
        }

        $this->template->assign('dataGrid', (string) $this->dataGrid->getContent());
    }
}
