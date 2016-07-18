<?php

namespace Backend\Modules\Quotes\Installer;

use Backend\Core\Installer\ModuleInstaller;

final class Installer extends ModuleInstaller
{
    public function install()
    {
        $this->addModule('Quotes');

        $this->importSQL(dirname(__FILE__) . '/data/install.sql');
        $this->importLocale(dirname(__FILE__) . '/data/locale.xml');

        $this->setModuleRights(1, $this->getModule());
        $this->setActionRights(1, $this->getModule(), 'Index');
        $this->setActionRights(1, $this->getModule(), 'Add');
        $this->setActionRights(1, $this->getModule(), 'Edit');
        $this->setActionRights(1, $this->getModule(), 'Delete');

        $this->insertExtra($this->getModule(), 'widget', 'RandomQuote', 'RandomQuote');
        $this->insertExtra($this->getModule(), 'widget', 'AllQuotes', 'AllQuotes');

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $this->setNavigation(
            $navigationModulesId,
            'Quotes',
            'quotes/index',
            ['quotes/add', 'quotes/edit']
        );
    }
}
