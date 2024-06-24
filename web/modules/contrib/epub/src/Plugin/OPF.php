<?php

namespace Mini\Modules\contrib\epub\src\Plugin;

/**
 * @class This is class is for holding meta-data of ebook.
 */
class OPF
{
    /**
     * Book title.
     * @var string
     */
    private string $title;

    /**
     * Book description.
     * @var string
     */
    private string $description;

    /**
     * Author of the book.
     * @var string
     */
    private string $creator;

    /**
     * Language book is using.
     * @var string
     */
    private string $language;

    /**
     * Identifier of a book.
     * @var string
     */
    private string $identifier;

    /**
     * Version of a book.
     * @var string
     */
    private string $version;

    /**
     * Publisher of a book.
     * @var string
     */
    private string $publisher;

    /**
     * Date of publication.
     * @var string
     */
    private string $date;

    /**
     * Rights of a book.
     * @var string
     */
    private string $rights;

    /**
     * Cover of a book
     * @var string
     */
    private string $cover;

    /**
     * Book identify.
     */
    private string $book_identify;

    public function getBookIdentify(): string
    {
        return $this->book_identify;
    }

    /**
     * Content values of these args should originate from content.opt
     * @param string $title
     * @param string $creator
     * @param string $language
     * @param string $identifier
     * @param string $publisher
     * @param string $date
     * @param string $description
     * @param string $rights
     * @param string $coverImageId
     * @param string $version
     * @param string $book_identify
     */
    public function __construct(
        string $title,
        string $creator,
        string $language,
        string $identifier,
        string $publisher,
        string $date,
        string $description,
        string $rights,
        string $coverImageId,
        string $version,
        string $book_identify
    )
    {
        $this->title = $title;
        $this->creator = $creator;
        $this->language = $language;
        $this->identifier = $identifier;
        $this->publisher = $publisher;
        $this->date = $date;
        $this->description = $description;
        $this->rights = $rights;
        $this->cover = $coverImageId;
        $this->version = $version;
        $this->book_identify = $book_identify;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreator(): string
    {
        return $this->creator;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getRights(): string
    {
        return $this->rights;
    }

    public function getCover(): string
    {
        return $this->cover;
    }


}