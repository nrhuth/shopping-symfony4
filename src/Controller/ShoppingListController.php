<?php

namespace App\Controller;

use App\Entity\ShoppingList;
use App\Form\ShoppingListType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingListController extends AbstractController
{
    /**
     * @Route("/shopping-list/", name="shopping_list")
     */
    public function index()
    {
        $shoppingLists = $this->getDoctrine()
                        ->getRepository(ShoppingList::class)
                        ->findAll();

        return $this->render('shopping_list/index.html.twig', [
            'controller_name' => 'ShoppingListController',
            'shoppingLists' => $shoppingLists,
        ]);
    }

    /**
     * @Route("/shopping-list/new/", name="shopping_list_new")
     */
    public function new(Request $request)
    {
        $shoppingList = new ShoppingList();
        $shoppingList->setCreatedAt(new \DateTime());

        $form = $this->createForm(ShoppingListType::class, $shoppingList);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $shoppingList = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($shoppingList);
            $entityManager->flush();

            return $this->redirectToRoute('shopping_list');
        }

        return $this->render('shopping_list/new/index.html.twig', [
            'controller_name' => 'ShoppingListController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/shopping-list/{id}/", name="shopping_list_single")
     */
    public function single($id)
    {
        /** @var ShoppingList $shoppingList */
        $shoppingList = $this->getDoctrine()->getRepository(ShoppingList::class)->findOneById($id);

        $items = $shoppingList->getItem();

        return $this->render('shopping_list/single/index.html.twig', [
            'controller_name' => 'ShoppingListController',
            'items' => $items,
            'shoppingList' => $shoppingList
        ]);
    }
}
