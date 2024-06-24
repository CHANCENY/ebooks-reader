<?php

namespace Mini\Cms\default\Controllers;

use Mini\Cms\Connections\Database\Database;
use Mini\Cms\Controller\ContentType;
use Mini\Cms\Controller\ControllerInterface;
use Mini\Cms\Controller\Request;
use Mini\Cms\Controller\Response;
use Mini\Cms\Controller\StatusCode;
use Mini\Cms\Entity;
use Mini\Cms\Services\Services;
use Mini\Cms\StorageManager\Connector;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ContentStructureView implements ControllerInterface
{

    public function __construct(private Request &$request, private Response &$response)
    {
    }

    public function isAccessAllowed(): bool
    {
        return true;
    }

    public function writeBody(): void
    {
        $content_name = $this->request->get('content_type_name');
        if(empty($content_name)) {
            (new RedirectResponse('/structure/content-types'))->send();
        }

        $entity = Entity::load($content_name);

        $this->response->setContentType(ContentType::TEXT_HTML)
            ->setStatusCode(StatusCode::OK)
            ->write(Services::create('render')->render('content_types_viewing.php',['entity'=>$entity]));
    }
}