<?php

namespace Mini\Modules\contrib\epub\src\Plugin;

/**
 * @class Class is used to hold spine content of ebook.
 */
class Spine
{

    private string $book_identify;

    private array $spine_items;

    /**
     * @param array $spineItems
     * @param int|string $book_identify
     */
    public function __construct(array $spineItems, $book_identify)
    {
        $this->spine_items = $spineItems;
        $this->book_identify = $book_identify;
    }

    public function getBookIdentify(): string
    {
        return $this->book_identify;
    }

    public function getSpineItems(): array
    {
        $spines = [];
        foreach ($this->spine_items as $spine_item) {
            $spines[] = ['book_spine_name' => $spine_item,'book_identify'=>$this->book_identify];
        }
        return $spines;
    }


}