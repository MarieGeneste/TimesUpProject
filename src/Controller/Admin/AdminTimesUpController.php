<?php

namespace App\Controller\Admin;

use App\Entity\Edition;
use App\Entity\BlueCard;
use App\Entity\Category;
use App\Entity\Response;
use App\Form\EditionType;
use App\Entity\YellowCard;
use App\Entity\TimesUpCard;
use App\Form\TimesUpCardType;
use App\Service\RandomService;
use App\Repository\UserRepository;
use App\Repository\EditionRepository;
use App\Service\FrontSecurityService;
use App\Repository\BlueCardRepository;
use App\Repository\CategoryRepository;
use App\Repository\ResponseRepository;
use App\Repository\YellowCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TimesUpCardRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin", name="admin_")
 */
class AdminTimesUpController extends AbstractController
{
    private $em;
    private $userRepo;
    private $responseRepo;
    private $cardRepo;
    private $editionRepo;
    private $categoryRepo;
    private $yellowCardRepo;
    private $blueCardRepo;
    private $securityService;

    public function __construct(ResponseRepository $responseRepo, 
                                UserRepository $userRepo, 
                                TimesUpCardRepository $cardRepo, 
                                EditionRepository $editionRepo, 
                                CategoryRepository $categoryRepo, 
                                BlueCardRepository $blueCardRepo, 
                                YellowCardRepository $yellowCardRepo, 
                                EntityManagerInterface $em, 
                                FrontSecurityService $securityService)
    {
        $this->em = $em;
        $this->userRepo = $userRepo;
        $this->responseRepo = $responseRepo;
        $this->cardRepo = $cardRepo;
        $this->editionRepo = $editionRepo;
        $this->categoryRepo = $categoryRepo;
        $this->yellowCardRepo = $yellowCardRepo;
        $this->blueCardRepo = $blueCardRepo;
        $this->securityService = $securityService;
    }

    /**
     * @Route("/Cartes", name="show_cards")
     */
    public function showCards()
    {
        $pageModTitle = "Cartes";

        $allCards = $this->cardRepo->findAll();

        return $this->render('admin/showCards.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allCards' => $allCards
        ]);
    }

    /**
     * @Route("/Editions", name="show_editions")
     */
    public function showEditions()
    {
        $pageModTitle = "Editions";
;
        $allEditions = $this->editionRepo->findAll();

        return $this->render('admin/showEditions.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allEditions' => $allEditions,
        ]);
    }

    /**
     * @Route("/ajout-Carte", name="add_card")
     */
    public function addCard(Request $request)
    {
        $pageModTitle = "Création";
        $allCategories = $this->categoryRepo->findAll();
        $allResponses = $this->responseRepo->findAll();

        $newCard = new TimesUpCard();
        $cardForm = $this->createForm(TimesUpCardType::class, $newCard);

        $cardForm->handleRequest($request);

        if ($cardForm->isSubmitted()) {

            $this->updateCard($request, $newCard, "crée");

            return $this->redirectToRoute('admin_show_cards');

        }

        return $this->render('admin/editCard.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allCategories' => $allCategories,
            'allResponses' => $allResponses,
            'cardForm' => $cardForm->createView(),
        ]);
    }

    /**
     * @Route("/edition-Carte/{id}", name="edit_card")
     * @param TimesUpCard $card
     */
    public function editCard(TimesUpCard $card, Request $request)
    {
        $pageModTitle = "Edition";
        $allCategories = $this->categoryRepo->findAll();
        $allResponses = $this->responseRepo->findAll();

        $cardForm = $this->createForm(TimesUpCardType::class, $card);

        $cardForm->handleRequest($request);

        if ($cardForm->isSubmitted()) {

            if ($cardForm->isSubmitted()) {
    
                $this->updateCard($request, $card, "modifiée");

                return $this->redirectToRoute('admin_show_cards');
    
            }
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
     * @Route("/ajout-Edition", name="add_edition")
     */
    public function addEdition(Request $request)
    {
        $pageModTitle = "Création";

        $newEdition = new Edition();
        $editionForm = $this->createForm(EditionType::class, $newEdition);

        $editionForm->handleRequest($request);

        if ($editionForm->isSubmitted() and $editionForm->isValid()) {
            $this->em->persist($newEdition);
            $this->em->flush();
            return $this->redirectToRoute('admin_show_editions');
        }

        return $this->render('admin/editEdition.html.twig', [
            'pageModTitle' => $pageModTitle,
            'editionForm' => $editionForm->createView(),
        ]);
    }

    /**
     * @Route("/edition-Edition/{edition}", name="edit_edition")
     * @param Edition $edition
     */
    public function editEdition(Edition $edition, Request $request)
    {
        $pageModTitle = "Edition";

        $editionForm = $this->createForm(EditionType::class, $edition);

        $editionForm->handleRequest($request);

        if ($editionForm->isSubmitted() and $editionForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin_show_editions');
        }

        return $this->render('admin/editEdition.html.twig', [
            'pageModTitle' => $pageModTitle,
            'edition' => $edition, 
            'editionForm' => $editionForm->createView(),
        ]);
    }

    /**
     * @Route("/suppression-Edition/{edition}", name="delete_edition")
     * @param Edition $edition
     */
    public function deleteEdition(Edition $edition, Request $request)
    {
        $deleteName = $edition->getTitle();

        $editionCardFound = $this->cardRepo->findBy(["edition" => $edition]);

        if (!empty($editionCardFound)) {
            $this->addFlash('error', 'L\'Edition "' . $deleteName .'" ne peut pas être supprimée car au moins une carte y est rattachée.');
        } else {

            $this->em->remove($edition);
            $this->em->flush();

            $this->addFlash('success', 'L\'Edition "' . $deleteName .'" a bien été supprimée.');
        }

        return $this->redirectToRoute('admin_show_editions');
    }


    /**
     * @Route("/update-Carte", name="update_card")
     */
    public function updateCard(Request $request, $card, $flashAction)
    {
        $yellowResonseId = $this->securityService->cleanTrimPostData($request->request->get('card')['yellowResponse']['id']);
        if (!empty($yellowResonseId)) {
            $yellowResp = $this->responseRepo->find($yellowResonseId);
        }
        $blueResonseId = $this->securityService->cleanTrimPostData($request->request->get('card')['blueResponse']['id']);
        if (!empty($blueResonseId)) {
            $blueResp = $this->responseRepo->find($blueResonseId);
        }

        $yellowContent = $this->securityService->cleanPostData($request->request->get('card')['yellowContent']['name']);
        $yellowDesc = $this->securityService->cleanPostData($request->request->get('card')['yellowContent']['description']);

        $blueContent = $this->securityService->cleanPostData($request->request->get('card')['blueContent']['name']);
        $blueDesc = $this->securityService->cleanPostData($request->request->get('card')['blueContent']['description']);

        if (!empty($card->getEdition()) && (!empty($yellowResp) || !empty($yellowContent)) && (!empty($yellowResp) || !empty($blueContent)) ) {
            $yellowCard = new YellowCard();
            $blueCard = new BlueCard();

            $fondYellowResponse = $this->responseRepo->findOneBy(['name' => $yellowContent]);
            if (!empty($fondYellowResponse)) {
                $yellowResp = $fondYellowResponse;
            }

            $fondBlueResponse = $this->responseRepo->findOneBy(['name' => $blueContent]);
            if (!empty($fondBlueResponse)) {
                $blueResp = $fondBlueResponse;
            }

            if (!empty($yellowResp)) {
                $fondYellowCard = $this->yellowCardRepo->findOneBy(['content' => $yellowResp]);
                if (!empty($fondYellowCard)) {
                    $yellowCard = $fondYellowCard;
                }
                $yellowCard->setContent($yellowResp);
            } else {
                $newYellowResp = new Response;
                $newYellowResp->setName($yellowContent)
                                ->setDescription($yellowDesc);
                
                $yellowCats = !empty($request->request->get('card')['yellowContent']['category']) ? $request->request->get('card')['yellowContent']['category'] : null;
                if (!empty($yellowCats)) {
                    foreach ($yellowCats as $yellowCat) {
                        $yellowCat = $this->categoryRepo->find($yellowCat);
                        $newYellowResp->addCategory($yellowCat);
                    }
                }
                
                $this->em->persist($newYellowResp);
                $this->em->flush();

                $newYellowResp->setYellowCard($yellowCard);
            }

            if (!empty($blueResp)) {
                $fondBlueCard = $this->blueCardRepo->findOneBy(['content' => $blueResp]);
                if (!empty($fondBlueCard)) {
                    $blueCard = $fondBlueCard;
                }
                $blueCard->setContent($blueResp);
            } else {
                $newBlueResp = new Response;
                $newBlueResp->setName($blueContent)
                                ->setDescription($blueDesc);
                
                $blueCats = !empty($request->request->get('card')['blueContent']['category']) ? $request->request->get('card')['blueContent']['category'] : null;
                if (!empty($blueCats)) {
                    foreach ($blueCats as $blueCat) {
                        $blueCat = $this->categoryRepo->find($blueCat);
                        $newBlueResp->addCategory($blueCat);
                    }
                }
                
                $this->em->persist($newBlueResp);
                $this->em->flush();

                $newBlueResp->setBlueCard($blueCard);
            }

            $card->setYellowContent($yellowCard)
                    ->setBlueContent($blueCard);
    
            $this->em->persist($card);
            $this->em->flush();

            $this->addFlash('success', "La carte a bien été " . $flashAction ." !");

        } else {
            $this->addFlash('error', "L'édition, ainsi que les réponses jaune et bleue sont des obligatoires");
        }

        return $this->redirectToRoute('admin_show_cards');
    }

    /**
     * @Route("/creation-carte-category", name="add_card_category")
     */
    public function addCardCategory(Request $request, CategoryRepository $catRep, RandomService $randomServ)
    {
        $catTitles = $request->request->get('add-category');
        $newYellowCardCatTitle = $this->securityService->cleanPostData($catTitles[0]);
        $newBlueCardCatTitle = $this->securityService->cleanPostData($catTitles[1]);

        $data["newCategoryTitle"] = null;
        $data["newCategoryId"] = null;
        $data["newCategoryColor"] = null;
        $data["newCategoryFound"] = false;
        $data["isNewCat"] = false;
        $data["isYellowCard"] = true;
        
        if (!empty($newYellowCardCatTitle) || !empty($newBlueCardCatTitle)) {

            // S'il s'agit d'une catégorie a rattacher à la réponse bleue
            if (!empty($newBlueCardCatTitle)) {
                $data["isYellowCard"] = false;

                $newCatTitle = $newBlueCardCatTitle;
            } else {

                $newCatTitle = $newYellowCardCatTitle;
            }

            $data["isNewCat"] = true;
            $catExist = $catRep->findOneBy(['title' => $newCatTitle]);

            if (!empty($catExist)) {
                $data["newCategoryFound"] = true;
                $data["newCategoryTitle"] = $catExist->getTitle();
                $data["newCategoryId"] = $catExist->getId();
            } else {
                $newCategory = new Category();
                $newCategory->setTitle($newCatTitle);
                
                $randomColor = $randomServ->getRandomColor();
                $newCategory->setColor($randomColor);
    
                $this->em->persist($newCategory);
                $this->em->flush();
                dump($newCategory);

                $data["newCategoryTitle"] = $newCategory->getTitle();
                $data["newCategoryId"] = $newCategory->getId();
                $data["newCategoryColor"] = $newCategory->getColor();
            }
        }

        dump($data);
        // exit;

        return $this->json($data);
    }
}
