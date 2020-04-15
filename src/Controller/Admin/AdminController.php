<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Category;
use App\Entity\GameMode;
use App\Entity\Response;
use App\Form\CategoryType;
use App\Form\GameModeType;
use App\Form\ResponseType;
use App\Service\RandomService;
use App\Repository\UserRepository;
use App\Repository\EditionRepository;
use App\Service\FrontSecurityService;
use App\Repository\BlueCardRepository;
use App\Repository\CategoryRepository;
use App\Repository\GameModeRepository;
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
class AdminController extends AbstractController
{
    private $em;
    private $userRepo;
    private $gameModesRepo;
    private $responseRepo;
    private $cardRepo;
    private $categoryRepo;
    private $yellowCardRepo;
    private $blueCardRepo;

    public function __construct(ResponseRepository $responseRepo, 
                                GameModeRepository $gameModesRepo, 
                                UserRepository $userRepo, 
                                TimesUpCardRepository $cardRepo, 
                                CategoryRepository $categoryRepo, 
                                BlueCardRepository $blueCardRepo, 
                                YellowCardRepository $yellowCardRepo, 
                                EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->userRepo = $userRepo;
        $this->gameModesRepo = $gameModesRepo;
        $this->responseRepo = $responseRepo;
        $this->cardRepo = $cardRepo;
        $this->categoryRepo = $categoryRepo;
        $this->yellowCardRepo = $yellowCardRepo;
        $this->blueCardRepo = $blueCardRepo;
    }

    /**
     * @Route("/", name="dashboard")
     */
    public function index()
    {
        $pageModTitle = "Tableau de bord";

        return $this->render('admin/index.html.twig', [
            'pageModTitle' => $pageModTitle,
        ]);
    }

    /**
     * @Route("/Modes-de-Jeux", name="show_game_modes")
     */
    public function showGamesMode()
    {
        $pageModTitle = "Modes de Jeux";

        $allGames = $this->gameModesRepo->findAll();

        return $this->render('admin/showGamesMode.html.twig', [
            'pageModTitle' => $pageModTitle,
            'allGames' => $allGames
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

        $this->addFlash('success', 'L\'Utilisateur "' . $deleteName .'" a bien été supprimé.');

        return $this->redirectToRoute('admin_show_users');
    }

    /**
     * @Route("/edition-Mode-de-Jeu/{gameMode}", name="edit_game_mode")
     * @param GameMode $gameMode
     */
    public function editGameMode (GameMode $gameMode, Request $request)
    {
        $pageModTitle = "Edition";

        $gameModeForm = $this->createForm(GameModeType::class, $gameMode);

        $gameModeForm->handleRequest($request);

        if ($gameModeForm->isSubmitted() and $gameModeForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin_show_game_modes');
        }

        return $this->render('admin/editGameMode.html.twig', [
            'pageModTitle' => $pageModTitle,
            'gameMode' => $gameMode, 
            'gameModeForm' => $gameModeForm->createView(),
        ]);
    }

    /**
     * @Route("/suppression-Mode-de-Jeu/{gameMode}", name="delete_game_mode")
     * @param GameMode $gameMode
     */
    public function deleteGameMode(GameMode $gameMode, Request $request)
    {
        $deleteName = $gameMode->getName();

        $this->em->remove($gameMode);
        $this->em->flush();

        $this->addFlash('success', 'Le Mode de Jeu "' . $deleteName .'" a bien été supprimé.');

        return $this->redirectToRoute('admin_show_game_modes');
    }


    /**
     * @Route("/ajout-Mode-de-Jeu", name="add_game_mode")
     */
    public function addGameMode(Request $request)
    {
        $pageModTitle = "Création";

        $newGameMode = new GameMode();

        $gameModeForm = $this->createForm(GameModeType::class, $newGameMode);

        $gameModeForm->handleRequest($request);

        if ($gameModeForm->isSubmitted() and $gameModeForm->isValid()) {
            $this->em->persist($newGameMode);
            $this->em->flush();
            return $this->redirectToRoute('admin_show_game_modes');
        }

        return $this->render('admin/editGameMode.html.twig', [
            'pageModTitle' => $pageModTitle,
            'gameModeForm' => $gameModeForm->createView(),
        ]);
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
     * @Route("/ajout-Categorie", name="add_category")
     */
    public function addCategory(Request $request, RandomService $randomServ)
    {
        $pageModTitle = "Création";

        $newCategory = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $newCategory);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() and $categoryForm->isValid()) {
            if ($newCategory->getColor() == "#000000") {
                $randomColor = $randomServ->getRandomColor();
                $newCategory->setColor($randomColor);
            }

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

}
