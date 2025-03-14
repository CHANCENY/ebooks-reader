<?php

namespace Mini\Cms\default\Controllers;

use Mini\Cms\Controller\ContentType;
use Mini\Cms\Controller\ControllerInterface;
use Mini\Cms\Controller\Request;
use Mini\Cms\Controller\Response;
use Mini\Cms\Controller\StatusCode;
use Mini\Cms\Entities\Node;
use Mini\Cms\Field;
use Mini\Cms\Services\Services;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ContentDelete implements ControllerInterface
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
        $node = Node::load((int) $this->request->get('node'));

        $action = $this->request->get('action');

        if(empty($action)) {
            $this->response->setContentType(ContentType::TEXT_HTML)
                ->setStatusCode(StatusCode::OK)
                ->write(Services::create('render')->render('confirmation_page.php',['title'=>'Are you sure you want to delete this node?']));
        }

        if((int) $action === 1) {
            if($node->delete()) {
                (new RedirectResponse('/structure/contents'))->send();
            }
        }

        if ($action !== null && (int) $action === 0) {
            (new RedirectResponse('/structure/content/node/'.$node->id()))->send();
        }
    }
}