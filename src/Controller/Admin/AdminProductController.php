<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminProductController extends AbstractController
{
    /**
     * @Route("admin/listproducts", name="admin_list_product")
     */
    public function list_products(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render("admin/list_products.html.twig", ["products" => $products]);
    }

    /**
     * @Route("admin/createproduct", name="admin_create_product")
     */
    public function createProduct(Request $request, EntityManagerInterface $entityManagerInterface, SluggerInterface $sluggerInterface)
    {
        $product = new Product();

        $productForm = $this->createForm(ProductType::class, $product);

        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            $productFile = $productForm->get('img')->getData();

            if ($productFile) {
                $originaleFileName = pathinfo($productFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $sluggerInterface->slug($originaleFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $productFile->guessExtension();

                $productFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $product->setImg($newFileName);
            }
            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_product");
        }

        return $this->render("admin/form_product.html.twig", ["productForm" => $productForm->createView()]);
    }
    /**
     * @Route("/admin/updateproduct/{id}", name="admin_update_product")
     */
    public function updateProduct($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, SluggerInterface $sluggerInterface, Request $request)
    {
        $product = $productRepository->find($id);

        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            $productFile = $productForm->get('img')->getData();

            if ($productFile) {
                $originaleFileName = pathinfo($productFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $sluggerInterface->slug($originaleFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $productFile->guessExtension();

                $productFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $product->setImg($newFileName);
            }
            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_product");
        }

        return $this->render("admin/form_product.html.twig", ["productForm" => $productForm->createView()]);
    }

    /**
     * @Route("/admin/deleteproduct/{id}", name="admin_delete_product")
     */
    public function deleteProduct($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface)
    {
        $product = $productRepository->find($id);
        $entityManagerInterface->remove($product);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_list_product");
    }
}
