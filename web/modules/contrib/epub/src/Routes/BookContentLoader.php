<?php

namespace Mini\Modules\contrib\epub\src\Routes;

use Mini\Cms\Controller\ContentType;
use Mini\Cms\Controller\ControllerInterface;
use Mini\Cms\Controller\Request;
use Mini\Cms\Controller\Response;
use Mini\Cms\Controller\StatusCode;
use Mini\Cms\Theme\FileLoader;
use Mini\Modules\contrib\epub\src\Plugin\Book;

class BookContentLoader implements ControllerInterface
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
        $book_identify = $this->request->get('book_identify');
        $file_name = $this->request->get('file_name');

        if($book_identify && $file_name) {
            $book = new Book($book_identify);
            $file = (new FileLoader($book->getBookRootPath()))->findFiles($file_name);
            if(!empty($file)) {
                $content = $book->bookContent($file[0]);
                if($content) {
                    $this->response->setStatusCode(StatusCode::OK)
                        ->setContentType(ContentType::APPLICATION_JSON)
                        ->write(['content' => $content]);
                }
                else {
                    $this->response->setStatusCode(StatusCode::NOT_FOUND);
                }
            }
            else {
                $this->response->setStatusCode(StatusCode::NOT_FOUND);
            }
        }
    }
}