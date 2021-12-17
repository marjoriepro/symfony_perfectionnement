<?php

namespace App\Controller\front;

use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController

{


    // Créer les deux fonctions qui affichent la liste des produits avec leur nom, prix, type, brand,

    #[Route("/front/products", name: "ProductList")]
    public function productsList(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        return $this->render('front/products.html.twig', ['products' => $products]);
    }



    // Système des wildcards
    #[Route("/front/product/{id}", name: "ProductShow")]
    public function productShow($id, ProductRepository $productRepository)
    {

        $product = $productRepository->find($id);
        if (isset($product)) {
            return $this->render('front/product.html.twig', ['product' => $product]);
        } else {
            throw  new NotFoundHttpException("Erreur 404. La page que vous cherchez n'a pas été trouvée");
        }
    }

    #[Route('/front/search', name: 'front_search')]
    public function frontsearch(ProductRepository $productRepository, Request $request)
    {

        // récupérer les données rentrées dans le formulaire 
        $term = $request->query->get('term');

        $products = $productRepository->searchByTerm($term);

        return $this->render('front/search.html.twig', ['products' => $products]);
    }

    // on créer une fonction qui lorsque l'user clique sur le like d'un pdt cela rajotue ou enleve son like sur le pdt
    #[Route("/front/like/product/{id}", name:"product_like")]
    public function likeProduct($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, LikeRepository $likeRepository)
    {
        $product = $productRepository->find($id);
        $user = $this->getUser();


        // si l'utilisateur n'est pas connecté
        if(!$user) 
        {
            return $this->json(
                ['code'=> 403,
                'message' => "Vous devez vous connecter"], 403);

        }

        // si l'utilisateur connecté clique sur un pdt déjà liké
        if($product->isLikedByUser($user))
        {
            $like = $likeRepository->findOneBy(
                ['product' => $product, 
                'user' => $user]
            );

            $entityManagerInterface->remove($like);
            $entityManagerInterface->flush();

            return $this->json(
                ['code' => 200,
                'message' => "Le like a été supprimé",
                'likes' => $likeRepository->count( 
                    ['product' => $product]
                )], 200);
        }

        $like = new Like();
        $like->setProduct($product);
        $like->setUser($user);

        $entityManagerInterface->persist($like);
        $entityManagerInterface->flush();

            return $this->json(
                ['code' => 200,
                'message' => "Le like a été enregistré",
                'likes' => $likeRepository->count( 
                    ['product' => $product]
                )], 200);

    }


}
