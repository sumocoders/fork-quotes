<?php

namespace Backend\Modules\Quotes;

use Backend\Core\Engine\Base\Config as BackendBaseConfig;

final class Config extends BackendBaseConfig
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
