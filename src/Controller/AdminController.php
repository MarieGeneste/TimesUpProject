<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Word;
use App\Form\CardType;
use App\Form\WordType;
use App\Entity\Edition;
use App\Entity\Category;
use App\Form\EditionType;
use App\Form\CategoryType;
use App\Repository\CardRepository;
use App\Repository\WordRepository;
use App\Repository\EditionRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $em;
    private $wordRepo;
    private $cardRepo;
    private $editionRepo;
    private $categoryRepo;

    public function __construct(WordRepository $wordRepo, CardRepository $cardRepo, EditionRepository $editionRepo, CategoryRepository $categoryRepo, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->wordRepo = $wordRepo;
        $this->cardRepo = $cardRepo;
        $this->editionRepo = $editionRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $pageModTitle = "dashboard";

        $allCards = $this->cardRepo->findAll();
        $allEditions = $this->editionRepo->findAll();
        $allCategories = $this->categoryRepo->findAll();

        return $this->render('admin/index.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allCards' => $allCards,
            'allEditions' => $allEditions,
            'allCategories' => $allCategories
        ]);
    }

    /**
     * @Route("/admin/contenu", name="admin_content")
     */
    public function shohAllWords()
    {
        $pageModTitle = "content";

        $allWords = $this->wordRepo->findAll();

        return $this->render('admin/content.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allWords' => $allWords
        ]);
    }


    /**
     * @Route("/admin/ajout-Contenu", name="add_content")
     */
    public function addContent(Request $request)
    {
        $pageModTitle = "Création";

        $newContent = new Word();
        $wordForm = $this->createForm(WordType::class, $newContent);

        $wordForm->handleRequest($request);

        if ($wordForm->isSubmitted() and $wordForm->isValid()) {
            $this->em->persist($newContent);
            $this->em->flush();
            return $this->redirectToRoute('admin_content');
        }

        return $this->render('admin/editContent.html.twig', [
            'pageModTitle' => $pageModTitle,
            'wordForm' => $wordForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/edition-Contenu/{id}", name="edit_content")
     * @param Word $content
     */
    public function editContent (Word $content, Request $request)
    {
        $pageModTitle = "Edition";

        $wordForm = $this->createForm(WordType::class, $content);

        $wordForm->handleRequest($request);

        if ($wordForm->isSubmitted() and $wordForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin_content');
        }

        return $this->render('admin/editContent.html.twig', [
            'pageModTitle' => $pageModTitle,
            'content' => $content, 
            'wordForm' => $wordForm->createView(),
        ]);
    }


    /**
     * @Route("/admin/ajout-Carte", name="add_card")
     */
    public function addCard(Request $request)
    {
        $pageModTitle = "Création";
        $allCategories = $this->categoryRepo->findAll();

        $newCard = new Card();
        $cardForm = $this->createForm(CardType::class, $newCard);

        $cardForm->handleRequest($request);

        if ($cardForm->isSubmitted() and $cardForm->isValid()) {
            $this->em->persist($newCard);
            $this->em->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/editCard.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allCategories' => $allCategories,
            'cardForm' => $cardForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/edition-Carte/{id}", name="edit_card")
     * @param Card $card
     */
    public function editCard(Card $card, Request $request)
    {
        $pageModTitle = "Edition";

        $cardForm = $this->createForm(CardType::class, $card);

        $cardForm->handleRequest($request);

        if ($cardForm->isSubmitted() and $cardForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/editCard.html.twig', [
            'pageModTitle' => $pageModTitle,
            'card' => $card, 
            'cardForm' => $cardForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/ajout-Categorie", name="add_category")
     */
    public function addCategory(Request $request)
    {
        $pageModTitle = "Création";

        $newCategory = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $newCategory);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() and $categoryForm->isValid()) {
            $this->em->persist($newCategory);
            $this->em->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/editCategory.html.twig', [
            'pageModTitle' => $pageModTitle,
            'categoryForm' => $categoryForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/edition-Categorie/{id}", name="edit_category")
     * @param Category $category
     */
    public function editCategory(Category $category, Request $request)
    {
        $pageModTitle = "Edition";

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() and $categoryForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/editCategory.html.twig', [
            'pageModTitle' => $pageModTitle,
            'category' => $category, 
            'categoryForm' => $categoryForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/ajout-Edition", name="add_edition")
     */
    public function editionEdition(Request $request)
    {
        $pageModTitle = "Création";

        $newEdition = new Edition();
        $editionForm = $this->createForm(EditionType::class, $newEdition);

        $editionForm->handleRequest($request);

        if ($editionForm->isSubmitted() and $editionForm->isValid()) {
            $this->em->persist($newEdition);
            $this->em->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/editEdition.html.twig', [
            'pageModTitle' => $pageModTitle,
            'editionForm' => $editionForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/edition-Edition/{id}", name="edit_edition")
     * @param Edition $edition
     */
    public function editEdition(Edition $edition, Request $request)
    {
        $pageModTitle = "Edition";

        $editionForm = $this->createForm(EditionType::class, $edition);

        $editionForm->handleRequest($request);

        if ($editionForm->isSubmitted() and $editionForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/editEdition.html.twig', [
            'pageModTitle' => $pageModTitle,
            'edition' => $edition, 
            'editionForm' => $editionForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/ajout-Category", name="card_category_add")
     */
    public function addCardCategory(Request $request)
    {
        $yellowCat = $request->request->get('card')['yellowContent']['category']['title'];
        $yellowCatCol = $request->request->get('card')['yellowContent']['category']['color'];

        $blueCat = $request->request->get('card')['blueContent']['category']['title'];
        $blueCatCol = $request->request->get('card')['blueContent']['category']['color'];


        $selectedCat = array();

        if (!empty($yellowCat)) {
            $yellowCat = $this->cleanDataPost($yellowCat);
            $catFound = $this->categoryRepo->findOneBy(['title' => $yellowCat]);

            if (empty($catFound)) {
                $newCat = new Category;
                $newCat->setTitle($yellowCat);

                $this->em->persist($newCat);

                $electedCat['yellowCat'] = $newCat;
            } else {
                $electedCat['yellowCat'] = $catFound;
            }

            if (!empty($yellowCatCol)) {
                $yellowCatCol = $this->cleanDataPost($yellowCatCol);
                $electedCat['yellowCat']->setColor($yellowCatCol);
            }

            $this->em->flush();

        } elseif (!empty($blueCat)){
            $blueCat = $this->cleanDataPost($blueCat);
            $catFound = $this->categoryRepo->findOneBy(['title' => $blueCat]);

            if (empty($catFound)) {
                $newCat = new Category;
                $newCat->setTitle($blueCat);
                
                $this->em->persist($newCat);
                $this->em->flush();

                $electedCat['blueCat'] = $newCat;
            } else {
                $electedCat['blueCat'] = $catFound;
            }

            if (!empty($blueCatCol)) {
                $blueCatCol = $this->cleanDataPost($blueCatCol);
                $electedCat['blueCat']->setColor($blueCatCol);
            }

            $this->em->flush();
        }
    }

    private function cleanDataPost($data){
        // $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
