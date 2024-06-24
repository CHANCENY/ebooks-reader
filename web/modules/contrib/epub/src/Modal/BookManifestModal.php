<?php

namespace Mini\Modules\contrib\epub\src\Modal;

use Mini\Cms\Modules\Modal\Columns\Number;
use Mini\Cms\Modules\Modal\Columns\VarChar;
use Mini\Cms\Modules\Modal\Modal;

class BookManifestModal extends Modal
{
    protected string $main_table = 'book_manifest_table';

    public function __construct()
    {
        $this->columns = array(
            self::buildColumnInstance(Number::class)->parent($this)->name('book_manifest_id')->size(11)->autoIncrement(true)->primary(true)->description("Manifest ID"),
            self::buildColumnInstance(VarChar::class)->parent($this)->name('manifest_href')->size(255)->nullable(false)->description("Manifest book href"),
            self::buildColumnInstance(VarChar::class)->parent($this)->name('manifest_name')->size(255)->nullable(false)->description("Manifest name"),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("manifest_type")->size(255)->nullable(true)->description("Manifest resource type"),
            self::buildColumnInstance(VarChar::class)->parent($this)->name("book_identify")->description("book identify id")->size(50)->nullable(false),
        );

        parent::__construct();
    }
}