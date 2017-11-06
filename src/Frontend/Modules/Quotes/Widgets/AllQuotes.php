<?php

namespace Frontend\Modules\Quotes\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;

final class AllQuotes extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    private function parse()
    {
        $quotes = $this->get('quotes_repository')->getAllQuotes();

        $this->template->assign('quotes', $quotes);
    }
}
