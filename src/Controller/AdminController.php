<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Edition;
use App\Entity\Category;
use App\Repository\CardRepository;
use App\Repository\EditionRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $cardRepo;
    private $editionRepo;
    private $categoryRepo;

    public function __construct(CardRepository $cardRepo, EditionRepository $editionRepo, CategoryRepository $categoryRepo)
    {
        $this->cardRepo = $cardRepo;
        $this->editionRepo = $editionRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {

        $allCards = $this->cardRepo->findAll();
        $allEditions = $this->editionRepo->findAll();
        $allCategories = $this->categoryRepo->findAll();

        return $this->render('admin/index.html.twig', [
            'allCards' => $allCards,
            'allEditions' => $allEditions,
            'allCategories' => $allCategories
        ]);
    }


    /**
     * @Route("/admin/ajout-Carte", name="add_card")
     */
    public function addCard()
    {
        $pageModTitle = "Création";

        return $this->render('admin/editCard.html.twig', [
            'pageModTitle' => $pageModTitle,
        ]);
    }

    /**
     * @Route("/admin/edition-Carte/{id}", name="edit_card")
     * @param Card $card
     */
    public function editCard(Card $card)
    {
        $pageModTitle = "Edition";

        return $this->render('admin/editCard.html.twig', [
            'pageModTitle' => $pageModTitle,
        ]);
    }

    /**
     * @Route("/admin/ajout-Categorie", name="add_category")
     */
    public function addCategory()
    {
        $pageModTitle = "Création";

        return $this->render('admin/editCategory.html.twig', [
            'pageModTitle' => $pageModTitle,
        ]);
    }

    /**
     * @Route("/admin/edition-Categorie/{id}", name="edit_category")
     * @param Category $category
     */
    public function editCategory(Category $category)
    {
        $pageModTitle = "Edition";

        return $this->render('admin/editCategory.html.twig', [
            'pageModTitle' => $pageModTitle,
        ]);
    }

    /**
     * @Route("/admin/ajout-Edition", name="add_edition")
     */
    public function editionEdition()
    {
        $pageModTitle = "Création";

        return $this->render('admin/editEdition.html.twig', [
            'pageModTitle' => $pageModTitle,
        ]);
    }

    /**
     * @Route("/admin/edition-Edition/{id}", name="edit_edition")
     * @param Edition $edition
     */
    public function editEdition(Edition $edition)
    {
        $pageModTitle = "Edition";

        return $this->render('admin/editEdition.html.twig', [
            'pageModTitle' => $pageModTitle,
        ]);
    }
}
