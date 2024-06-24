<?php

namespace Mini\Modules\contrib\epub\src\Routes;

use Mini\Cms\Controller\ControllerInterface;
use Mini\Cms\Controller\Request;
use Mini\Cms\Controller\Response;
use Mini\Cms\Controller\Route;
use Mini\Cms\Controller\StatusCode;
use Mini\Cms\Modules\Storage\Tempstore;
use Mini\Modules\contrib\epub\src\Plugin\Book;

class BookReader implements ControllerInterface
{

    public function __construct(private Request &$request, private Response &$response)
    {
    }

    /**
     * @inheritDoc
     */
    public function isAccessAllowed(): bool
    {
        return TRUE;
    }

    public function writeBody(): void
    {
        $book_id = $this->request->get('book_identify'); //1718688720
        if($book_id) {
            $book = new Book($book_id);
            if(!empty($book->getBookTitle())) {
                $current_route = Tempstore::load('current_route');
                if($current_route instanceof Route) {
                    /**@var $cont \Mini\Cms\Routing\Route **/
                    $cont = $current_route->getLoadedRoute();
                    $cont->setRouteTitle($book->getBookTitle());
                }
                $this->response->write($book->loadFistPageContent());
            }
            else {
                $this->response->setStatusCode(StatusCode::NOT_FOUND);
                $this->response->write("<div><h2>Not book found</h2></div>");
            }
        }
        else {
            $this->response->setStatusCode(StatusCode::NOT_FOUND);
            $this->response->write("<p>Book Not Found</p>");
        }
    }
}