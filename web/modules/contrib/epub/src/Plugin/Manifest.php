<?php

namespace Mini\Modules\contrib\epub\src\Plugin;

/**
 * @class This is classed is for a holding book manifest.
 */
class Manifest
{
    /**
     * Book manifest.
     * @var array
     */
    private array $manifest;

    /**
     * Book id.
     * @var string|int
     */
    private string $book_identify;

    /**
     * @param array $items
     * @param int|string $book_identify
     */
    public function __construct(array $items, $book_identify)
    {
        $this->book_identify = $book_identify;
        $this->manifest = $items;
    }

    public function getManifest(): array
    {
        foreach ($this->manifest as &$manifest_item) {
           $manifest_item['book_identify'] = $this->book_identify;
        }
        return $this->manifest;
    }

    public function getBookIdentify(): string
    {
        return $this->book_identify;
    }


}