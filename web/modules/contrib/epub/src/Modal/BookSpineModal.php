<?php

namespace Mini\Modules\contrib\epub\src\Modal;

use Mini\Cms\Modules\Modal\Columns\Number;
use Mini\Cms\Modules\Modal\Columns\VarChar;
use Mini\Cms\Modules\Modal\Modal;

class BookSpineModal extends Modal
{
    protected string $main_table = "book_spine_table";

    public function __construct()
    {
        $this->columns = array(
            self::buildColumnInstance(Number::class)->parent($this)->name("book_spine_id")->size(11)->primary(true)->autoIncrement(true)->description("book spine ID"),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("book_spine_name")->description("book spine name")->nullable(false)->size(255),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("book_identify")->description("book spine identify")->nullable(false)->size(255),
        );
        parent::__construct();
    }
}