<?php

namespace App\Controller\admin;

use App\Entity\Type;
use App\Form\AdminType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminTypeController extends AbstractController
{


    public function listType(TypeRepository $typeRepository)
    {
        $types = $typeRepository->findAll();

        return $this->render("admin/types.html.twig", ['types' => $types]);
    }

  
    public function showType(TypeRepository $typeRepository, $id)
    {
        $type = $typeRepository->find($id);

        return $this->render("admin/type.html.twig", ['type' => $type]);
    }


    #[Route("/admin/create/type/", name:"admin_type_create")]

    public function adminCreateType(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $type = new Type();

        $typeForm = $this->createForm(AdminType::class, $type);

        $typeForm->handleRequest($request);

        if ($typeForm->isSubmitted() && $typeForm->isValid()) {

            $entityManagerInterface->persist($type);
            $entityManagerInterface->flush();
            $this->addFlash('notice', 'Le type de produit a été créé.');

            return $this->redirectToRoute('admin_type_list');
        }

        return $this->render('admin/type_create.html.twig', ['typeForm' => $typeForm->createView()]);
    }


     #[Route("/admin/update/type/{id}", name: "admin_type_update")]

    public function adminUpdateType(
        $id,
        EntityManagerInterface $entityManagerInterface,
        Request $request,
        TypeRepository $typeRepository
    ) {

        $type = $typeRepository->find($id);

        $typeForm = $this->createForm(AdminType::class, $type);

        $typeForm->handleRequest($request);

        if ($typeForm->isSubmitted() && $typeForm->isValid()) {

            $entityManagerInterface->persist($type);
            $entityManagerInterface->flush();
            $this->addFlash('notice', 'Le type de produit a été modifié.');

            return $this->redirectToRoute('admin_type_list');
        }

        return $this->render('admin/type_create.html.twig', ['typeForm' => $typeForm->createView()]);
    }


    #[Route("/admin/delete/type/{id}", name: "admin_type_delete")]

    public function adminDeleteType($id, TypeRepository $typeRepository, EntityManagerInterface $entityManagerInterface)
    {
        $type = $typeRepository->find($id);

        $entityManagerInterface->remove($type);
        $entityManagerInterface->flush();
        $this->addFlash('notice', 'Le type de produit a été supprimé');

        return $this->redirectToRoute('admin_type_list');
    }


}