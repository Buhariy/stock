<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/listcategory", name="admin_list_category")
     */
    public function listcategory(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render("/admin/list_category.html.twig", ["categories" => $categories]);
    }

    /**
     * @Route("/admin/createcategory", name="admin_create_category")
     */
    public function createcategory(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $category = new Category;

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_category");
        }
        return $this->render("admin/form_category.html.twig", ["categoryForm" => $categoryForm->createView()]);
    }
    /**
     * @Route("/admin/updatecategory/{id}", name="admin_update_category")
     */
    public function updateCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $category = $categoryRepository->find($id);

        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_category");
        }

        return $this->render("admin/form_category.html.twig", ["categoryForm" => $categoryForm->createView()]);
    }

    /**
     * @Route("/admin/deletecategory/{id}", name="admin_delete_category")
     */
    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface)
    {
        $category = $categoryRepository->find($id);
        $entityManagerInterface->remove($category);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_list_category");
    }
}
