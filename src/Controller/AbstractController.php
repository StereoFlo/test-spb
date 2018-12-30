<?php

namespace Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AbstractController
 * @package Controller
 */
abstract class AbstractController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request|RequestStack $request
     */
    public function setRequest(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }
}