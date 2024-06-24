<?php

namespace Mini\Modules\contrib\epub\src\Routes;

use Mini\Cms\Controller\ControllerInterface;
use Mini\Cms\Controller\Request;
use Mini\Cms\Controller\Response;
use Mini\Modules\contrib\epub\src\Plugin\Books;
use Mini\Modules\contrib\epub\src\Plugin\BookSaver;
use Mini\Modules\contrib\epub\src\Plugin\Extractor;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MyBookShop implements ControllerInterface
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
        // Handling of post requests
        if($this->request->isMethod('POST')) {
            $this->postDataHandler();
            (new RedirectResponse($this->request->getRequestUri()))->send();
            exit;
        }
        $books = new Books(0,0);
        $this->response->write($books->booksByOwner($this->request->get('uid')));
    }

    private function postDataHandler(): void
    {
        // Uploading new books.
        if($this->request->getPayload()->get('new_book')) {
            $fids = $this->request->getPayload()->all('ebook');

            // Looping through all books.
            foreach ($fids as $fid) {
                try{
                    $ext = new Extractor((int) $fid);
                    $ext->extract();

                    $ext->collectToc();
                    $ext->collectOpfContent();

                    $bose = new BookSaver($ext->opt,$ext->manifest,$ext->tocs,$ext->spine);
                    $bose->saveNewBook();
                }catch (\Throwable){}
            }
        }
    }
}