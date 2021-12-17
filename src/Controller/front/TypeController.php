<?php 

namespace App\Controller\front;

use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{

    // fichier TypeController.php ne fonctionne pas !! 

    #[Route("/front/hello", name: "hello")]
    public function hello()
    {
        return new Response("Hello World");
    }


    #[Route("/front/types", name: "TypeList")]
    public function typesList(TypeRepository $typeRepository)
    {
        $types = $typeRepository->findAll();
        return $this->render('front/types.html.twig', ['types' => $types]);
    }



    // Système des wildcards
  #[Route("/front/type/{id}", name: "TypeShow")]
  public function typeShow($id, TypeRepository $typeRepository)
  {

      $type = $typeRepository->find($id);
          return $this->render('front/type.html.twig', ['type' => $type]);
  }
} 