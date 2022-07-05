<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProductController extends AbstractController
{
    /**
     * @Route("/admin/listproducts", name="admin_list_product")
     */
    public function list_products(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render("admin/list_products.html.twig", ["products" => $products]);
    }
}
