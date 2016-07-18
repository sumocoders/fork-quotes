<?php

namespace Backend\Modules\Quotes\Actions;

use Backend\Core\Engine\Base\ActionDelete;
use Backend\Core\Engine\Model;
use Backend\Modules\Quotes\Entity\Quote;

final class Delete extends ActionDelete
{
    public function execute()
    {
        parent::execute();

        try {
            /** @var Quote $quote */
            $quote = $this->get('quotes_repository')->find($this->getParameter('id', 'int'));
        } catch (\InvalidArgumentException $e) {
            $this->redirect(
                Model::createURLForAction(
                    'Index',
                    null,
                    null,
                    [
                        'error' => 'non-existing',
                    ]
                )
            );

            return;
        }

        $this->get('quotes_repository')->delete($quote);

        $this->redirect(
            Model::createURLForAction(
                'Index',
                null,
                null,
                [
                    'report' => 'deleted',
                ]
            )
        );
    }
}
