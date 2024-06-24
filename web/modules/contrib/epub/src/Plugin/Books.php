<?php

namespace Mini\Modules\contrib\epub\src\Plugin;

use Mini\Cms\Modules\CurrentUser\CurrentUser;
use Mini\Cms\Modules\Extensions\ModuleHandler\ModuleHandler;
use Mini\Cms\Modules\Modal\RecordCollections;
use Mini\Cms\Modules\Streams\MiniWrapper;
use Mini\Cms\Theme\FileLoader;
use Mini\Modules\contrib\epub\src\Modal\BookMetaDataModal;

readonly class Books
{
    private BookMetaDataModal $meta_modal;

    private int $limit;

    private int $offset;

    private ModuleHandler $handler;


    public function __construct(int $limit, int $offset)
    {
        $this->meta_modal = new BookMetaDataModal();
        $this->limit = $limit;
        $this->offset = $offset;
        $this->handler = new ModuleHandler('epub');
    }

    public function range(bool $raw = false): RecordCollections|string
    {
        $books = $this->meta_modal->range($this->limit,$this->offset);
        if($raw) {
            return $books;
        }
        $template_file = (new FileLoader($this->handler->getPath()))->findFiles('book_catalogy.php');
        if(!empty($template_file[0])) {
            extract(
                [
                    'books'=>
                    [
                        "All genre" => $books,
                        'Business' => $books,
                        'Technology' => $books
                    ],
                    'current_user' => (new CurrentUser()),
            ]
            );
            ob_start();
            require_once (new MiniWrapper())->getRealPath($template_file[0]);
            $content = ob_get_contents();
            ob_end_clean();
            ob_end_flush();
            return $content;
        }
        return $books;
    }

    public function booksByOwner(int $uid, bool $raw = false): RecordCollections|string
    {
        $books = $this->meta_modal->byOwner($uid);
        if($raw) {
            return $books;
        }

        $template_file = (new FileLoader($this->handler->getPath()))->findFiles('book_my_book_dashboard.php');
        if(!empty($template_file[0])) {
            extract(
                [
                    'books'=> $books,
                    'current_user' => (new CurrentUser()),
                ]
            );
            ob_start();
            require_once (new MiniWrapper())->getRealPath($template_file[0]);
            $content = ob_get_contents();
            ob_end_clean();
            ob_end_flush();
            return $content;
        }
        return $books;
    }
}