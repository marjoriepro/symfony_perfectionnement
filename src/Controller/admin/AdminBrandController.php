<?php

namespace App\Controller\admin;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBrandController extends AbstractController
{

    #[Route("/admin/brands", name: "admin_brand_list")]
    public function listBrand(BrandRepository $brandRepository)
    {
        $brands = $brandRepository->findAll();

        return $this->render("admin/brands.html.twig", ['brands' => $brands]);
    }

    #[Route("/admin/brand/{id}", name: "admin_brand_show")]
    public function showBrand(BrandRepository $brandRepository, $id)
    {
        $brand= $brandRepository->find($id);

        return $this->render("admin/brand.html.twig", ['brand' => $brand]);
    }


    #[Route("/admin/create/brand/", name:"admin_brand_create")]

    public function adminCreateBrand(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $brand = new Brand();

        $brandForm = $this->createForm(BrandType::class, $brand);

        $brandForm->handleRequest($request);

        if ($brandForm->isSubmitted() && $brandForm->isValid()) {

            $entityManagerInterface->persist($brand);
            $entityManagerInterface->flush();
            $this->addFlash('notice', 'La marque a été créé.');

            return $this->redirectToRoute('admin_brand_list');
        }

        return $this->render('admin/brand_create.html.twig', ['brandForm' => $brandForm->createView()]);
    }


     #[Route("/admin/update/brand/{id}", name: "admin_brand_update")]

    public function adminUpdateBrand(
        $id,
        EntityManagerInterface $entityManagerInterface,
        Request $request,
        BrandRepository $brandRepository
    ) {

        $brand = $brandRepository->find($id);

        $brandForm = $this->createForm(BrandType::class, $brand);

        $brandForm->handleRequest($request);

        if ($brandForm->isSubmitted() && $brandForm->isValid()) {

            $entityManagerInterface->persist($brand);
            $entityManagerInterface->flush();
            $this->addFlash('notice', 'La marque a été modifié.');

            return $this->redirectToRoute('admin_brand_list');
        }

        return $this->render('admin/brand_create.html.twig', ['brandForm' => $brandForm->createView()]);
    }


    #[Route("/admin/delete/brand/{id}", name: "admin_brand_delete")]

    public function adminDeleteBrand($id, BrandRepository $brandRepository, EntityManagerInterface $entityManagerInterface)
    {
        $brand = $brandRepository->find($id);

        $entityManagerInterface->remove($brand);
        $entityManagerInterface->flush();
        $this->addFlash('notice', 'La marque a été supprimé');

        return $this->redirectToRoute('admin_brand_list');
    }


}