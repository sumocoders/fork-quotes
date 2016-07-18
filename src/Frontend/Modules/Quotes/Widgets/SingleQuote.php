<?php

namespace Frontend\Modules\Quotes\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;

final class SingleQuote extends FrontendBaseWidget
{
    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    private function parse()
    {
        $quote = $this->get('quotes_repository')->find($this->data['id']);

        $this->tpl->assign('quote', $quote);
    }
}
