<?php 

namespace App\Controller\front;

use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
{
    #[Route("/front/brands", name: "BrandList")]
    public function brandsList(BrandRepository $brandRepository)
    {
        $brands = $brandRepository->findAll();
        return $this->render('front/brands.html.twig', ['brands' => $brands]);
    }


    // Système des wildcards {id}
  #[Route("/front/brand/{id}", name: "BrandShow")]
  public function brandShow($id, BrandRepository $brandRepository)
  {

      $brand = $brandRepository->find($id); // find() permet de sélectionner qu'un seul élément
      if(isset($brand)){
          return $this->render('front/brand.html.twig', ['brand' => $brand]);
      }else{
          throw  new NotFoundHttpException("Erreur 404. La page que vous cherchez n'a pas été trouvée");
      }
  }
}