<?php

namespace Mini\Modules\contrib\epub\src\Modal;

use Mini\Cms\Modules\Modal\Columns\Number;
use Mini\Cms\Modules\Modal\Columns\VarChar;
use Mini\Cms\Modules\Modal\Modal;

class BookTocsModal extends Modal
{
    protected string $main_table = 'book_tocs_table';

    public function __construct()
    {

        $this->columns = array(
            self::buildColumnInstance(Number::class)->parent($this)->name('book_tocs_id')->autoIncrement(true)->primary(true)->size(11),
            self::buildColumnInstance(VarChar::class)->parent($this)->name('tocs_title')->description("tocs label")->nullable(false)->size(300),
            self::buildColumnInstance(Number::class)->parent($this)->name('tocs_play_order')->description("play order")->size(11),
            self::buildColumnInstance(VarChar::class)->parent($this)->name('tocs_content_src')->description("content source path")->size(500),
            self::buildColumnInstance(VarChar::class)->parent($this)->name('book_identify')->description("content book identify")->size(50),
        );
        parent::__construct();
    }
}