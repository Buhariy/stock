<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class FrontProductController extends AbstractController
{
    /**
     * @Route("front/listproducts", name="front_list_product")
     */
    public function list_products(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render("front/list_products.html.twig", ["products" => $products]);
    }
}
