<?php

namespace Mini\Modules\contrib\epub\src\Modal;

use Mini\Cms\Modules\Modal\Columns\Number;
use Mini\Cms\Modules\Modal\Columns\Text;
use Mini\Cms\Modules\Modal\Columns\VarChar;
use Mini\Cms\Modules\Modal\Modal;

/**
 * @class EbookMetaData is modal for ebook metadata storage.
 */
class BookMetaDataModal extends Modal
{
    protected string $main_table = 'book_meta_table';

    public function __construct()
    {
        // Modal fields.

        $this->columns = array(
            self::buildColumnInstance(Number::class)->name('book_m_id')->parent($this)->primary(true)->autoIncrement(true)->size(11)->description("Book Meta Data unique identifier"),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("title")->description("Title of book")->size(400)->nullable(false),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("creator")->description("Author of book")->size(255)->nullable(true),
            self::buildColumnInstance(Text::class)->parent($this)->name("description")->description("Description of book")->size(400)->nullable(true),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("version")->description("Version of book")->size(5)->nullable(true),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("publisher")->description("Publisher of book")->size(255)->nullable(true),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("date")->description("Date of publication")->size(100)->nullable(false),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("rights")->description("Rights")->size(400)->nullable(true),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("cover")->description("Cover of book")->size(400)->nullable(true),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("book_identify")->description("book identify id")->size(50)->nullable(false),
        );


        parent::__construct();
    }
}