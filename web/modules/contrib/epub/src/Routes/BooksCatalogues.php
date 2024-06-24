<?php

namespace Mini\Modules\contrib\epub\src\Routes;

use Mini\Cms\Controller\ControllerInterface;
use Mini\Cms\Controller\Request;
use Mini\Cms\Controller\Response;
use Mini\Modules\contrib\epub\src\Modal\BookManifestModal;
use Mini\Modules\contrib\epub\src\Modal\BookMetaDataModal;
use Mini\Modules\contrib\epub\src\Plugin\Book;
use Mini\Modules\contrib\epub\src\Plugin\Books;

class BooksCatalogues implements ControllerInterface
{

    public function __construct(private Request &$request, private Response &$response)
    {
    }

    /**
     * @inheritDoc
     */
    public function isAccessAllowed(): bool
    {
        return true;
    }

    public function writeBody(): void
    {
        $books = new Books(10,0);
        $this->response->write($books->range());
    }
}