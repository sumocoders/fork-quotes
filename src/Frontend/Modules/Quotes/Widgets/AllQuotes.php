<?php

namespace Frontend\Modules\Quotes\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Language\Locale;

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
        $quotes = $this->get('quotes_repository')->getAllQuotes(Locale::frontendLanguage());

        $this->template->assign('quotes', $quotes);
    }
}
