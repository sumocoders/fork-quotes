<?php

namespace Frontend\Modules\Quotes\Widgets;

use Backend\Modules\Quotes\Entity\Quote;
use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Language\Locale;

final class RandomQuote extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    private function parse()
    {
        $quote = $this->get('quotes_repository')->getRandomQuote(Locale::frontendLanguage());

        if ($quote instanceof Quote) {
            $this->template->assign('quote', $quote);
        }
    }
}
