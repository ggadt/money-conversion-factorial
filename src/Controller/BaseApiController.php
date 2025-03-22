<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
abstract class BaseApiController extends AbstractController
{

}