<?php


namespace TemplateBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TemplateController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Template/Template/Template.html.twig');
    }
}