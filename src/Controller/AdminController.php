<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Entity\Edition;
use App\Entity\BlueCard;
use App\Entity\Category;
use App\Entity\Response;
use App\Form\EditionType;
use App\Entity\YellowCard;
use App\Form\CategoryType;
use App\Form\ResponseType;
use App\Repository\CardRepository;
use App\Repository\EditionRepository;
use App\Repository\CategoryRepository;
use App\Repository\ResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $em;
    private $responseRepo;
    private $cardRepo;
    private $editionRepo;
    private $categoryRepo;

    public function __construct(ResponseRepository $responseRepo, CardRepository $cardRepo, EditionRepository $editionRepo, CategoryRepository $categoryRepo, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->responseRepo = $responseRepo;
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
    public function shohAllResponses()
    {
        $pageModTitle = "content";

        $allResponses = $this->responseRepo->findAll();

        return $this->render('admin/content.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allResponses' => $allResponses
        ]);
    }


    /**
     * @Route("/admin/ajout-Contenu", name="add_content")
     */
    public function addContent(Request $request)
    {
        $pageModTitle = "Création";

        $newContent = new Response();
        $responseForm = $this->createForm(ResponseType::class, $newContent);

        $responseForm->handleRequest($request);

        if ($responseForm->isSubmitted() and $responseForm->isValid()) {
            $this->em->persist($newContent);
            $this->em->flush();
            return $this->redirectToRoute('admin_content');
        }

        return $this->render('admin/editContent.html.twig', [
            'pageModTitle' => $pageModTitle,
            'responseForm' => $responseForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/edition-Contenu/{content}", name="edit_content")
     * @param Response $content
     */
    public function editContent (Response $content, Request $request)
    {
        $pageModTitle = "Edition";

        $responseForm = $this->createForm(ResponseType::class, $content);

        $responseForm->handleRequest($request);

        if ($responseForm->isSubmitted() and $responseForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin_content');
        }

        return $this->render('admin/editContent.html.twig', [
            'pageModTitle' => $pageModTitle,
            'content' => $content, 
            'responseForm' => $responseForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/suppression-Contenu/{content}", name="delete_content")
     * @param Response $content
     */
    public function deleteContent (Response $content, Request $request)
    {
        $deleteName = $content->getName();
        dump($content);
        if (!empty($content->getBlueCard()) || !empty($content->getYellowCard())) {
            $this->addFlash('error', 'La ligne "' . $deleteName .'" ne peut pas être supprimée car elle est rattachée à une carte.');
        } else {
            $this->em->remove($content);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin_content');
    }


    /**
     * @Route("/admin/ajout-Carte", name="add_card")
     */
    public function addCard(Request $request)
    {
        $pageModTitle = "Création";
        $allCategories = $this->categoryRepo->findAll();
        $allResponses = $this->responseRepo->findAll();

        $newCard = new Card();
        $cardForm = $this->createForm(CardType::class, $newCard);

        $cardForm->handleRequest($request);

        if ($cardForm->isSubmitted()) {

            // dump($request);
            // exit;
            

            $yellowResonseId = $this->cleanDataPost($request->request->get('card')['yellowResponse']['id']);
            if (!empty($yellowResonseId)) {
                $yellowResp = $this->responseRepo->find($yellowResonseId);
            }
            $blueResonseId = $this->cleanDataPost($request->request->get('card')['blueResponse']['id']);
            if (!empty($blueResonseId)) {
                $blueResp = $this->responseRepo->find($blueResonseId);
            }

            $yellowContent = $this->cleanDataPost($request->request->get('card')['yellowContent']['name']);
            $yellowDesc = $this->cleanDataPost($request->request->get('card')['yellowContent']['description']);

            $blueContent = $this->cleanDataPost($request->request->get('card')['blueContent']['name']);
            $blueDesc = $this->cleanDataPost($request->request->get('card')['blueContent']['description']);

            if (!empty($newCard->getEdition()) && (!empty($yellowResp) || !empty($yellowContent)) && (!empty($yellowResp) || !empty($blueContent)) ) {
                $yellowCard = new YellowCard();
                $blueCard = new BlueCard();

                if (!empty($yellowResp)) {
                    $yellowCard->setContent($yellowResp);
                } else {
                    $newYellowResp = new Response;
                    $newYellowResp->setName($yellowContent)
                                    ->setDescription($yellowDesc);
                    
                    $yellowCatsId = !empty($request->request->get('card')['yellowContent']['category']) ? $request->request->get('card')['yellowContent']['category'] : null;
                    if (!empty($yellowCats)) {
                        foreach ($yellowCatsId as $yellowCatId) {
                            $yellowCat = $this->categoryRepo->find($yellowCatId);
                            $newYellowResp->addCategory($yellowCat);
                        }
                    }
                    
                    $this->em->persist($newYellowResp);
                    $this->em->flush();

                    $newYellowResp->setYellowCard($yellowCard);
                }

                if (!empty($blueResp)) {
                    $blueCard->setContent($blueResp);
                } else {
                    $newBlueResp = new Response;
                    $newBlueResp->setName($blueContent)
                                    ->setDescription($blueDesc);
                    
                    $blueCatsId = !empty($request->request->get('card')['blueContent']['category']) ? $request->request->get('card')['blueContent']['category'] : null;
                    if (!empty($blueCats)) {
                        foreach ($blueCatsId as $blueCatId) {
                            $blueCat = $this->categoryRepo->find($blueCatId);
                            $newBlueResp->addCategory($blueCat);
                        }
                    }
                    
                    $this->em->persist($newBlueResp);
                    $this->em->flush();

                    $newBlueResp->setBlueCard($blueCard);
                }

                $newCard->setYellowContent($yellowCard)
                        ->setBlueContent($blueCard);
     
                $this->em->persist($newCard);
                $this->em->flush();

                $this->addFlash('success', "La nouvelle carte a bien été crée");

            } else {
                $this->addFlash('error', "L'édition, ainsi que les réponses jaune et bleue sont des obligatoires");
            }

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/editCard.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allCategories' => $allCategories,
            'allResponses' => $allResponses,
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
        $allCategories = $this->categoryRepo->findAll();
        $allResponses = $this->responseRepo->findAll();

        $cardForm = $this->createForm(CardType::class, $card);

        $cardForm->handleRequest($request);

        if ($cardForm->isSubmitted() and $cardForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/editCard.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allCategories' => $allCategories,
            'allResponses' => $allResponses,
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

    // /**
    //  * @Route("/admin/ajout-Category", name="card_category_add")
    //  */
    // public function addCardCategory(Request $request)
    // {
    //     $yellowCat = $request->request->get('card')['yellowContent']['category']['title'];
    //     $yellowCatCol = $request->request->get('card')['yellowContent']['category']['color'];

    //     $blueCat = $request->request->get('card')['blueContent']['category']['title'];
    //     $blueCatCol = $request->request->get('card')['blueContent']['category']['color'];


    //     $selectedCat = array();

    //     if (!empty($yellowCat)) {
    //         $yellowCat = $this->cleanDataPost($yellowCat);
    //         $catFound = $this->categoryRepo->findOneBy(['title' => $yellowCat]);

    //         if (empty($catFound)) {
    //             $newCat = new Category;
    //             $newCat->setTitle($yellowCat);

    //             $this->em->persist($newCat);

    //             $electedCat['yellowCat'] = $newCat;
    //         } else {
    //             $electedCat['yellowCat'] = $catFound;
    //         }

    //         if (!empty($yellowCatCol)) {
    //             $yellowCatCol = $this->cleanDataPost($yellowCatCol);
    //             $electedCat['yellowCat']->setColor($yellowCatCol);
    //         }

    //         $this->em->flush();

    //     } elseif (!empty($blueCat)){
    //         $blueCat = $this->cleanDataPost($blueCat);
    //         $catFound = $this->categoryRepo->findOneBy(['title' => $blueCat]);

    //         if (empty($catFound)) {
    //             $newCat = new Category;
    //             $newCat->setTitle($blueCat);
                
    //             $this->em->persist($newCat);
    //             $this->em->flush();

    //             $electedCat['blueCat'] = $newCat;
    //         } else {
    //             $electedCat['blueCat'] = $catFound;
    //         }

    //         if (!empty($blueCatCol)) {
    //             $blueCatCol = $this->cleanDataPost($blueCatCol);
    //             $electedCat['blueCat']->setColor($blueCatCol);
    //         }

    //         $this->em->flush();
    //     }
    // }

    private function cleanDataPost($data){
        // $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
