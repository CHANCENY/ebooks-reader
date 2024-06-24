<?php

namespace Mini\Modules\contrib\epub\src\Routes;

use Mini\Cms\Controller\ContentType;
use Mini\Cms\Controller\ControllerInterface;
use Mini\Cms\Controller\Request;
use Mini\Cms\Controller\Response;
use Mini\Cms\Controller\StatusCode;
use Mini\Modules\contrib\epub\src\Plugin\Book;

class BookImages implements ControllerInterface
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
        $book_identify = $this->request->get('book');
        $image = $this->request->get('img');

        if($book_identify && $image) {
           $book = new Book($book_identify);
           $list = explode('/', $image);
           $image_name = end($list);
           $complete_path = $book->findBookFile($image_name);

           if($complete_path) {
               $file = new \SplFileInfo($complete_path);

               // Allowed a content type to response.
               $contentTypes = [
                   ContentType::IMAGE_JPEG,
                   ContentType::IMAGE_PNG,
                   ContentType::IMAGE_GIF,
                   ContentType::IMAGE_BMP,
                   ContentType::IMAGE_SVG,
                   ContentType::IMAGE_WEBP,
                   ContentType::IMAGE_JPG
               ];

               $file_type = null;
               foreach ($contentTypes as $contentType) {
                   if($contentType instanceof ContentType && str_ends_with($contentType->value, trim(strtolower($file->getExtension())))) {
                      $file_type = $contentType;
                      break;
                   }
               }
               if($file_type) {
                   $this->response->setContentType($file_type);
                   $this->response->setStatusCode(StatusCode::OK);
                   $this->response->write(file_get_contents($complete_path));
               }
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