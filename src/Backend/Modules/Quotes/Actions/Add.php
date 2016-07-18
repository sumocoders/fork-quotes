<?php

namespace Backend\Modules\Quotes\Actions;

use Backend\Core\Engine\Base\ActionAdd;
use Backend\Core\Engine\Model;
use Backend\Modules\Quotes\Form\QuoteType;

final class Add extends ActionAdd
{
    public function execute()
    {
        parent::execute();

        $form = new QuoteType('add');
        if ($form->handle()) {
            $quote = $form->getQuote();
            $this->get('quotes_repository')->add($quote);

            $this->redirect(
                Model::createURLForAction(
                    'Index',
                    null,
                    null,
                    [
                        'report' => 'added',
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
