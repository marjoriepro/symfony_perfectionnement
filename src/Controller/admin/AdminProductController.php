<?php

namespace App\Controller\admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{

    #[Route("/admin/products", name: "admin_product_list")]
    public function listProduct(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render("admin/products.html.twig", ['products' => $products]);
    }

    #[Route("/admin/product/{id}", name: "admin_product_show")]
    public function showProduct(ProductRepository $productRepository, $id)
    {
        $product = $productRepository->find($id);

        return $this->render("admin/product.html.twig", ['product' => $product]);
    }


    #[Route("/admin/create/product/", name:"admin_product_create")]

    public function adminCreateProduct(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $product = new Product();

        $productForm = $this->createForm(ProductType::class, $product);

        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {

            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();
            $this->addFlash('notice', 'Le produit a été créé.');

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin/product_create.html.twig', ['productForm' => $productForm->createView()]);
    }


     #[Route("/admin/update/product/{id}", name: "admin_product_update")]

    public function adminUpdateProduct(
        $id,
        EntityManagerInterface $entityManagerInterface,
        Request $request,
        ProductRepository $productRepository
    ) {

        $product = $productRepository->find($id);

        $productForm = $this->createForm(ProductType::class, $product);

        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {

            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();
            $this->addFlash('notice', 'Le produit a été modifié.');

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin/product_create.html.twig', ['productForm' => $productForm->createView()]);
    }


    #[Route("/admin/delete/product/{id}", name: "admin_product_delete")]

    public function adminDeleteProduct($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface)
    {
        $product = $productRepository->find($id);

        $entityManagerInterface->remove($product);
        $entityManagerInterface->flush();
        $this->addFlash('notice', 'Votre produit a été supprimé');

        return $this->redirectToRoute('admin_product_list');
    }


}