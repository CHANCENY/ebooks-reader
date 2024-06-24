<?php

namespace Mini\Modules\contrib\epub\src\Plugin;

use Mini\Cms\Modules\Modal\RecordCollections;
use Mini\Modules\contrib\epub\src\Modal\BookManifestModal;
use Mini\Modules\contrib\epub\src\Modal\BookMetaDataModal;
use Mini\Modules\contrib\epub\src\Modal\BookSpineModal;
use Mini\Modules\contrib\epub\src\Modal\BookTocsModal;

/**
 * @class BookSaver we be used to save book info in modals.
 */
class BookSaver
{

    /**
     * OPF object.
     * @var OPF|null
     */
    private OPF|null $opt;

    /**
     * Spine object.
     * @var Spine|null
     */
    private Spine|null $spine;

    /**
     * Manifest object.
     * @var Manifest|null
     */
    private Manifest|null $manifest;

    /**
     * Tocs object.
     * @var Tocs|null
     */
    private Tocs|null $tocs;

    public function __construct(OPF|null $opf, Manifest|null $manifest, Tocs|null $tocs, Spine|null $spine)
    {
        $this->opt = $opf;
        $this->manifest = $manifest;
        $this->tocs = $tocs;
        $this->spine = $spine;
    }

    public function saveNewBook(): RecordCollections|bool
    {
        if($this->opt) {
            $opf_modal = new BookMetaDataModal();
            $opf_modal->store([
                'title' => $this->opt->getTitle(),
                'description' => $this->opt->getDescription(),
                'version' => $this->opt->getVersion(),
                'cover' => $this->opt->getCover(),
                'creator' => $this->opt->getCreator(),
                'publisher' => $this->opt->getPublisher(),
                'date' => $this->opt->getDate(),
                'rights' => $this->opt->getRights(),
                'book_identify' => $this->opt->getBookIdentify(),
            ]);
        }

        if($this->spine) {
            $spine_modal = new BookSpineModal();
            $spine_modal->massStore($this->spine->getSpineItems());
        }

        if($this->tocs) {
            $toc_modal = new BookTocsModal();
            $toc_modal->massStore($this->tocs->getTocs());
        }

        if($this->manifest) {
            $manifest_modal = new BookManifestModal();
            $manifest_modal->massStore($this->manifest->getManifest());
        }
        return false;
    }
}