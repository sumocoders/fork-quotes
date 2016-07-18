<?php

namespace Frontend\Modules\Quotes\Widgets;

use Backend\Modules\Quotes\Entity\Quote;
use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;

final class AllQuotes extends FrontendBaseWidget
{
    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    private function parse()
    {
        $quotes = $this->get('quotes_repository')->getAllQuotes();

        $this->tpl->assign('quotes', $quotes);
    }
}
