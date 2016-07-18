<?php

namespace Backend\Modules\Quotes\Actions;

use Backend\Core\Engine\Base\ActionEdit;
use Backend\Core\Engine\Model;
use Backend\Modules\Quotes\Form\QuoteType;
use Backend\Modules\Quotes\Entity\Quote;

final class Edit extends ActionEdit
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
        $this->tpl->assign('quote', $quote->toArray());

        $form = new QuoteType('edit', $quote);
        if ($form->handle()) {
            $quote = $form->getQuote();
            $this->get('quotes_repository')->save($quote);

            $this->redirect(
                Model::createURLForAction(
                    'Index',
                    null,
                    null,
                    [
                        'report' => 'edited',
                        'highlight' => 'row-' . $quote->getId(),
                    ]
                )
            );

            return;
        }

        $form->parse($this->tpl);

        $this->parse();
        $this->display();
    }
}
