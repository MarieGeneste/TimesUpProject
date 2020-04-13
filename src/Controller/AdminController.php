<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\User;
use App\Service\FrontSecurityService;
use App\Form\CardType;
use App\Form\UserType;
use App\Entity\Edition;
use App\Entity\BlueCard;
use App\Entity\Category;
use App\Entity\Response;
use App\Form\EditionType;
use App\Entity\YellowCard;
use App\Form\CategoryType;
use App\Form\ResponseType;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use App\Repository\EditionRepository;
use App\Repository\BlueCardRepository;
use App\Repository\CategoryRepository;
use App\Repository\ResponseRepository;
use App\Repository\YellowCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
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
                                CardRepository $cardRepo, 
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
     * @Route("/", name="dashboard")
     */
    public function index()
    {
        $pageModTitle = "dashboard";

        return $this->render('admin/index.html.twig', [
            'pageModTitle' => $pageModTitle,
        ]);
    }

    /**
     * @Route("/Utilisateurs", name="show_users")
     */
    public function showUsers()
    {
        $pageModTitle = "Utilisateurs";

        $allUsers = $this->userRepo->findAll();

        return $this->render('admin/showUsers.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allUsers' => $allUsers
        ]);
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
     * @Route("/Categories", name="show_categories")
     */
    public function showCategories()
    {
        $pageModTitle = "Catégories";

        $allCategories = $this->categoryRepo->findAll();

        return $this->render('admin/showCategories.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allCategories' => $allCategories
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
     * @Route("/contenu", name="show_content")
     */
    public function shohAllResponses()
    {
        $pageModTitle = "content";

        $allResponses = $this->responseRepo->findAll();

        return $this->render('admin/showContent.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allResponses' => $allResponses
        ]);
    }


    // /**
    //  * @Route("/ajout-Utilisateur", name="add_user")
    //  */
    // public function addUser(Request $request)
    // {
    //     $pageModTitle = "Création";
    //     $allUsers = $this->userRepo->findAll();

    //     $newUser = new User();
    //     $userForm = $this->createForm(UserType::class, $newUser);

    //     $userForm->handleRequest($request);

    //     if ($userForm->isSubmitted() and $userForm->isValid()) {
    //         $this->em->flush();
    //         return $this->redirectToRoute('admin_show_users');
    //     }

    //     return $this->render('admin/editUser.html.twig', [
    //         'pageModTitle' => $pageModTitle,
    //         'allUsers' => $allUsers,
    //         'userForm' => $userForm->createView(),
    //     ]);
    // }

    /**
     * @Route("/edition-Utilisateur/{user}", name="edit_user")
     * @param User $user
     */
    public function editUser (User $user, Request $request)
    {
        $pageModTitle = "Edition";

        $userForm = $this->createForm(UserType::class, $user);
        $user->setPlainPassword($user->getPassword());

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() and $userForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin_show_users');
        }

        return $this->render('admin/editUser.html.twig', [
            'pageModTitle' => $pageModTitle,
            'user' => $user, 
            'userForm' => $userForm->createView(),
        ]);
    }

    /**
     * @Route("/suppression-Utilisateur/{user}", name="delete_user")
     * @param User $user
     */
    public function deleteUser(User $user, Request $request)
    {
        $deleteName = $user->getUsername();

        $this->em->remove($user);
        $this->em->flush();

        $this->addFlash('success', 'L\'Utilisateur "' . $deleteName .'" a bien été supprimée.');

        return $this->redirectToRoute('admin_show_users');
    }


    /**
     * @Route("/ajout-Contenu", name="add_content")
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
            return $this->redirectToRoute('admin_show_content');
        }

        return $this->render('admin/editContent.html.twig', [
            'pageModTitle' => $pageModTitle,
            'responseForm' => $responseForm->createView(),
        ]);
    }

    /**
     * @Route("/edition-Contenu/{content}", name="edit_content")
     * @param Response $content
     */
    public function editContent (Response $content, Request $request)
    {
        $pageModTitle = "Edition";

        $responseForm = $this->createForm(ResponseType::class, $content);

        $responseForm->handleRequest($request);

        if ($responseForm->isSubmitted() and $responseForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin_show_content');
        }

        return $this->render('admin/editContent.html.twig', [
            'pageModTitle' => $pageModTitle,
            'content' => $content, 
            'responseForm' => $responseForm->createView(),
        ]);
    }

    /**
     * @Route("/suppression-Contenu/{content}", name="delete_content")
     * @param Response $content
     */
    public function deleteContent (Response $content, Request $request)
    {
        $deleteName = $content->getName();

        $foundYellowCard = $this->yellowCardRepo->findOneBy(['content' => $content]);
        $foundBlueCard = $this->blueCardRepo->findOneBy(['content' => $content]);

        $isYellowCard = ($this->cardRepo->findBy(["yellowContent" => $foundYellowCard])) ? $this->cardRepo->findBy(["yellowContent" => $foundYellowCard]) : null;
        $isBlueCard = ($this->cardRepo->findBy(["blueContent" => $foundBlueCard])) ? $this->cardRepo->findBy(["blueContent" => $foundBlueCard]) : null;

        if (!empty($isYellowCard) || !empty($isBlueCard)) {
            $this->addFlash('error', 'La ligne "' . $deleteName .'" ne peut pas être supprimée car elle est rattachée à une carte.');
        } else {
            
            if(!empty($foundYellowCard) && empty($isYellowCard)){
                $content->setYellowCard($foundYellowCard, "remove");
                $this->em->remove($foundYellowCard);
            } elseif(!empty($foundBlueCard) && empty($isBlueCard)){
                $content->setBlueCard($foundBlueCard, "remove");
                $this->em->remove($foundBlueCard);
            }

            $this->em->remove($content);
            $this->em->flush();

            $this->addFlash('success', 'La ligne "' . $deleteName .'" a bien été supprimée.');
        }

        return $this->redirectToRoute('admin_show_content');
    }


    /**
     * @Route("/ajout-Carte", name="add_card")
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
     * @param Card $card
     */
    public function editCard(Card $card, Request $request)
    {
        $pageModTitle = "Edition";
        $allCategories = $this->categoryRepo->findAll();
        $allResponses = $this->responseRepo->findAll();

        $cardForm = $this->createForm(CardType::class, $card);

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
     * @Route("/ajout-Categorie", name="add_category")
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
            return $this->redirectToRoute('admin_show_categories');
        }

        return $this->render('admin/editCategory.html.twig', [
            'pageModTitle' => $pageModTitle,
            'categoryForm' => $categoryForm->createView(),
        ]);
    }

    /**
     * @Route("/edition-Categorie/{category}", name="edit_category")
     * @param Category $category
     */
    public function editCategory(Category $category, Request $request)
    {
        $pageModTitle = "Edition";

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() and $categoryForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin_show_categories');
        }

        return $this->render('admin/editCategory.html.twig', [
            'pageModTitle' => $pageModTitle,
            'category' => $category, 
            'categoryForm' => $categoryForm->createView(),
        ]);
    }

    /**
     * @Route("/suppression-Categorie/{category}", name="delete_category")
     * @param Category $category
     */
    public function deleteCategory(Category $category, Request $request)
    {
        $deleteName = $category->getTitle();

        $categoryResponseFound = $this->responseRepo->findByCategory($category);

        if (!empty($categoryResponseFound)) {
            $this->addFlash('error', 'La Categorie "' . $deleteName .'" ne peut pas être supprimée car au moins une carte y est rattachée.');
        } else {

            $this->em->remove($category);
            $this->em->flush();

            $this->addFlash('success', 'La Categorie "' . $deleteName .'" a bien été supprimée.');
        }

        return $this->redirectToRoute('admin_show_categories');
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
        dump($request);
        $yellowResonseId = $this->securityService->cleanPostData($request->request->get('card')['yellowResponse']['id']);
        if (!empty($yellowResonseId)) {
            $yellowResp = $this->responseRepo->find($yellowResonseId);
        }
        $blueResonseId = $this->securityService->cleanPostData($request->request->get('card')['blueResponse']['id']);
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
}
