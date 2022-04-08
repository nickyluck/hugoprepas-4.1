<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategoryRepository $repo): Response
    {
        $category = $repo->find(85);
        $ancestors = $category->getAncestors();
        $offsprings = $category->getOffsprings();
        dump($category);
        echo "Ancestors";
        foreach ($ancestors as $ancestor) {
            dump($ancestor);
        }
        echo "Offsprings";
        foreach ($offsprings as $offspring) {
            dump($offspring);
        }
        die();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'selected_category_id' => 75
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(): Response
    {
        return $this->render('home/test.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/question", name="question")
     */
    public function question(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/forum", name="forum")
     */
    public function forum(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/hugocolle", name="hugocolle")
     */
    public function hugocolle(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
