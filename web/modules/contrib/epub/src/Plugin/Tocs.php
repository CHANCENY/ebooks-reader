<?php

namespace Mini\Modules\contrib\epub\src\Plugin;

/**
 * @class Tocs for
 */
class Tocs
{

    /**
     * Docs list.
     * @var array
     */
    private array $tocs;

    private string $book_identify;

    /**
     * @param array $tocs
     * @param int|string $book_identify
     */
    public function __construct(array $tocs, $book_identify)
    {
        $this->tocs = $tocs;
        $this->book_identify = $book_identify;
    }

    public function getTocs(): array
    {
        foreach ($this->tocs as &$toc) {
            $toc['book_identify'] = $this->book_identify;
        }
        return $this->tocs;
    }

    public function getBookIdentify(): string
    {
        return $this->book_identify;
    }



}