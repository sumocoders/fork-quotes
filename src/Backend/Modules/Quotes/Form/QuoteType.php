<?php

namespace Backend\Modules\Quotes\Form;

use Backend\Core\Engine\Form;
use Backend\Core\Engine\FormImage;
use Backend\Core\Language\Language;
use Backend\Core\Engine\TwigTemplate;
use Backend\Modules\Quotes\Entity\Quote;

final class QuoteType
{
    /** @var Form */
    private $form;

    /** @var Quote */
    private $quote;

    /**
     * @param string $name
     * @param Quote|null $quote
     */
    public function __construct($name, Quote $quote = null)
    {
        $this->form = new Form($name);
        $this->quote = $quote;

        $this->build();
    }

    /**
     * @return Quote|null
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * Adds all needed fields to the form.
     */
    public function build()
    {
        $this->form->addText(
            'name',
            $this->quote instanceof Quote ? $this->quote->getName() : null,
            null,
            'form-control title',
            'form-control danger title'
        )->makeRequired();

        $this->form->addText(
            'author',
            $this->quote instanceof Quote ? $this->quote->getAuthor() : null
        )->makeRequired();

        $this->form->addEditor(
            'quote',
            $this->quote instanceof Quote ? $this->quote->getQuote() : null
        )->makeRequired();

        $this->form->addCheckbox(
            'visible',
            $this->quote instanceof Quote ? $this->quote->isVisible() : true
        );

        $this->form->addImage('image');
    }

    /**
     * @param TwigTemplate $template
     */
    public function parse(TwigTemplate $template)
    {
        $this->form->parse($template);

        if ($this->quote instanceof Quote) {
            $template->assign('previewImage', $this->quote->getPreviewImage());
        }
    }

    /**
     * @return bool
     */
    private function isValid()
    {
        $fields = $this->form->getFields();

        $fields['name']->isFilled(Language::err('FieldIsRequired'));
        $fields['quote']->isFilled(Language::err('FieldIsRequired'));
        $fields['author']->isFilled(Language::err('FieldIsRequired'));

        return $this->form->isCorrect();
    }

    /**
     * @return bool
     */
    public function handle()
    {
        if (!$this->form->isSubmitted() || !$this->isValid()) {
            return false;
        }
        $fields = $this->form->getFields();

        if ($this->quote instanceof Quote) {
            $this->quote->changeQuote(
                $fields['name']->getValue(),
                $fields['quote']->getValue(),
                $fields['author']->getValue()
            );

            $this->quote->changeVisibility($fields['visible']->isChecked());

            if ($fields['image']->isFilled()) {
                $this->quote->deleteImage();
            }

            $this->handleImage($fields['image']);

            return true;
        }

        $this->quote = Quote::create(
            $fields['name']->getValue(),
            $fields['quote']->getValue(),
            $fields['author']->getValue(),
            $this->handleImage($fields['image']),
            $fields['visible']->isChecked()
        );

        return true;
    }

    /**
     * @param FormImage $image
     *
     * @return string|void
     */
    private function handleImage(FormImage $image)
    {
        if (!$image->isFilled()) {
            return;
        }

        $filename = md5(microtime()) . '.' . $image->getExtension();

        $image->generateThumbnails(Quote::getImageDirectoryPath(), $filename);

        if ($this->quote instanceof Quote) {
            $this->quote->changeImage($filename);

            return;
        }

        return $filename;
    }
}
