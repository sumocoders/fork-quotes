<?php

namespace Backend\Modules\Quotes\Entity;

use Backend\Core\Engine\Model;
use Common\Language;
use InvalidArgumentException;
use DateTime;

final class Quote
{
    /** @var int $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var string $quote */
    private $quote;

    /** @var string $image */
    private $image;

    /** @var DateTime $createdOn */
    private $createdOn;

    /** @var DateTime $editedOn */
    private $editedOn;

    /** @var string $language */
    private $language;

    /** @var int $modulesExtrasId */
    private $modulesExtrasId;

    /**
     * @param string $name
     * @param string $quote
     * @param string $createdOn
     * @param string $editedOn
     * @param string $language
     * @param string|null $image
     * @param int|null $id
     * @param int|null $modulesExtrasId
     */
    private function __construct(
        $name,
        $quote,
        $createdOn,
        $editedOn,
        $language,
        $image = null,
        $id = null,
        $modulesExtrasId = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->quote = $quote;
        $this->image = $image;
        $this->createdOn = $createdOn;
        $this->editedOn = $editedOn;
        $this->language = $language;
        $this->modulesExtrasId = $modulesExtrasId;
    }

    /**
     * @param string $name
     * @param string $quote
     * @param string $image
     *
     * @return Quote
     */
    public static function create($name, $quote, $image = null)
    {
        return new self(
            $name,
            $quote,
            new DateTime(),
            new DateTime(),
            Language::callLanguageFunction('getWorkingLanguage'),
            $image
        );
    }

    /**
     * @param int $id
     *
     * @return Quote
     */
    public function setId($id)
    {
        if ($this->id != null) {
            throw new InvalidArgumentException('id can only be set once');
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @param $modulesExtrasId
     *
     * @return Quote
     */
    public function setModulesExtrasId($modulesExtrasId)
    {
        if ($this->modulesExtrasId != null) {
            throw new InvalidArgumentException('id can only be set once');
        }
        $this->modulesExtrasId = $modulesExtrasId;

        return $this;
    }

    /**
     * @param string $name
     * @param string $quote
     *
     * @return Quote
     */
    public function changeQuote($name, $quote)
    {
        $this->name = $name;
        $this->quote = $quote;
        $this->editedOn = new DateTime();

        return $this;
    }

    /**
     * @param string $image
     *
     * @return Quote
     */
    public function changeImage($image)
    {
        $this->deleteImage();

        $this->image = $image;
        $this->editedOn = new DateTime();

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quote' => $this->quote,
            'image' => $this->image,
            'created_on' => $this->createdOn,
            'edited_on' => $this->editedOn,
            'language' => $this->language,
            'modules_extras_id' => $this->getModulesExtrasId(),
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getModulesExtrasId()
    {
        return $this->modulesExtrasId;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * @return null|string
     */
    public function getPreviewImage()
    {
        if ($this->image !== null) {
            return self::getImageDirectoryUrl('x150') . '/' . $this->image;
        }
    }

    /**
     * Deletes all the different sizes of the image.
     */
    public function deleteImage()
    {
        Model::deleteThumbnails(self::getImageDirectoryPath(), $this->image);
        $this->image = null;
        $this->editedOn = new DateTime();
    }

    /**
     * @param string|null $size
     *
     * @return string
     */
    public static function getImageDirectoryPath($size = null)
    {
        if ($size === null) {
            return FRONTEND_FILES_PATH . '/Quotes';
        }

        return FRONTEND_FILES_PATH . '/Quotes/' . $size;
    }

    /**
     * @param string|null $size
     *
     * @return string
     */
    public static function getImageDirectoryUrl($size = null)
    {
        if ($size === null) {
            return FRONTEND_FILES_URL . '/Quotes';
        }

        return FRONTEND_FILES_URL . '/Quotes/' . $size;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param array $quoteRecord
     *
     * @return Quote
     */
    public static function fromArray(array $quoteRecord)
    {
        return new self(
            $quoteRecord['name'],
            $quoteRecord['quote'],
            DateTime::createFromFormat('Y-m-d H:i:s', $quoteRecord['created_on']),
            DateTime::createFromFormat('Y-m-d H:i:s', $quoteRecord['edited_on']),
            $quoteRecord['language'],
            $quoteRecord['image'],
            $quoteRecord['id'],
            $quoteRecord['modules_extras_id']
        );
    }
}
