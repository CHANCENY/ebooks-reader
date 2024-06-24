<?php

namespace Mini\Modules\contrib\epub\src\Routes;

use Mini\Cms\Controller\ContentType;
use Mini\Cms\Controller\ControllerInterface;
use Mini\Cms\Controller\Request;
use Mini\Cms\Controller\Response;
use Mini\Cms\Controller\StatusCode;
use Mini\Cms\Modules\CurrentUser\CurrentUser;
use Mini\Modules\contrib\epub\src\Plugin\Book;

class BookConfigLoader implements ControllerInterface
{

    public function __construct(private Request &$request,private Response &$response)
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
        if ($book_identify) {
            $book = new Book($book_identify);
            $configs['book'] = [
                'title' => $book->getBookTitle(),
                'description' => $book->getBookDescription(),
                'author' => $book->getBookAuthor(),
                'publisher' => $book->getBookPublisher(),
                'date' => $book->getPublicationDate(),
                'cover' => $book->getBookCover(),
                'id' => $book_identify,
                'rights' => $book->getBookCopyright(),
            ];
            $current_user = new CurrentUser();

            $configs['user'] = [
                'id' => $current_user->id(),
                'firstname' => $current_user->getFirstname(),
                'lastname' => $current_user->getLastname(),
            ];

            $configs['links'] = [
                'chapter_loader' => '/book/read/chapter/',
                //TODO: add more links for frontend.
            ];
            $this->response->setStatusCode(StatusCode::OK)
                ->setContentType(ContentType::APPLICATION_JSON)
                ->write(['system' => $configs]);
        }
        else {
            $this->response->setStatusCode(StatusCode::FORBIDDEN);
        }
    }
}