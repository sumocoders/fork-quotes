<?php

namespace Backend\Modules\Quotes\Repository;

use Backend\Core\Engine\Model;
use Backend\Modules\Quotes\Entity\Quote;
use Common\ModuleExtraType;
use SpoonDatabase;

final class QuotesRepository
{
    /**
     * @var SpoonDatabase
     */
    private $database;

    /**
     * @param SpoonDatabase $database
     */
    public function __construct(SpoonDatabase $database)
    {
        $this->database = $database;
    }

    /**
     * @return string
     */
    public function getDataGridQuery()
    {
        return 'SELECT id, name, quote, created_on, edited_on
                FROM quotes WHERE language = :language';
    }

    /**
     * @param Quote $quote
     *
     * @throws \SpoonDatabaseException
     *
     * @return Quote
     */
    public function add(Quote $quote)
    {
        $this->addToModulesExtras(
            $quote->setId(
                $this->database->insert(
                    'quotes',
                    $quote->toArray()
                )
            )
        );

        $this->save($quote);

        return $quote;
    }

    /**
     * @param int $id
     *
     * @return Quote
     */
    public function find($id)
    {
        $quoteRecord = $this->database->getRecord('SELECT * FROM quotes where id = ?', $id);

        return Quote::fromArray($quoteRecord);
    }

    /**
     * Returns all quotes.
     *
     * @return Quote[]
     */
    public function getAllQuotes()
    {
        $quotes = (array) $this->database->getRecords('SELECT * FROM quotes');

        return array_map(
            function (array $quote) {
                return Quote::fromArray($quote);
            },
            $quotes
        );
    }

    /**
     * Returns a random quote.
     *
     * @return Quote
     */
    public function getRandomQuote()
    {
        $randomId = (int) $this->database->getVar('SELECT id FROM quotes ORDER BY RAND() LIMIT 1');

        return $this->find($randomId);
    }

    /**
     * @param Quote $quote
     *
     * @throws \SpoonDatabaseException
     */
    public function save(Quote $quote)
    {
        $this->database->update(
            'quotes',
            $quote->toArray(),
            'id = ?',
            [$quote->getId()]
        );

        $this->updateModulesExtras($quote);
    }

    /**
     * @param Quote $quote
     *
     * @throws \SpoonDatabaseException
     */
    public function delete(Quote $quote)
    {
        $quote->deleteImage();
        $this->removeFromModulesExtras($quote);
        $this->database->delete('quotes', 'id = ?', [$quote->getId()]);
    }

    /**
     * @param Quote $quote
     *
     * @return Quote
     */
    private function addToModulesExtras(Quote $quote)
    {
        return $quote->setModulesExtrasId(
            Model::insertExtra(
                ModuleExtraType::widget(),
                'Quotes',
                'SingleQuote',
                'SingleQuote',
                [
                    'id' => $quote->getId(),
                    'extra_label' => $quote->getName(),
                    'language' => $quote->getLanguage(),
                    'edit_url' => Model::createURLForAction(
                        'Edit',
                        'Quotes',
                        $quote->getLanguage(),
                        [
                            'id' => $quote->getId(),
                        ]
                    ),
                ]
            )
        );
    }

    /**
     * @param Quote $quote
     */
    private function removeFromModulesExtras(Quote $quote)
    {
        $this->database->delete('modules_extras', 'id = ?', [$quote->getModulesExtrasId()]);
        $this->database->delete('pages_blocks', 'extra_id = ?', [$quote->getModulesExtrasId()]);
    }

    /**
     * @param Quote $quote
     */
    private function updateModulesExtras(Quote $quote)
    {
        $this->database->update(
            'modules_extras',
            [
                'module' => 'Quotes',
                'type' => 'widget',
                'label' => 'SingleQuote',
                'action' => 'SingleQuote',
                'data' => serialize(
                    [
                        'id' => $quote->getId(),
                        'extra_label' => $quote->getName(),
                        'language' => $quote->getLanguage(),
                        'edit_url' => Model::createURLForAction(
                            'Edit',
                            'Quotes',
                            $quote->getLanguage(),
                            [
                                'id' => $quote->getId(),
                            ]
                        ),
                    ]
                ),
            ],
            'id = ?',
            [ $quote->getModulesExtrasId() ]
        );
    }
}
