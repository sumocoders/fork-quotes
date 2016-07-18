<?php

namespace Frontend\Modules\Quotes;

use Frontend\Core\Engine\Base\Config as FrontendBaseConfig;

class Config extends FrontendBaseConfig
{
    /**
     * The default action.
     *
     * @var string
     */
    protected $defaultAction = 'Index';

    /**
     * The disabled actions.
     *
     * @var array
     */
    protected $disabledActions = [];
}
