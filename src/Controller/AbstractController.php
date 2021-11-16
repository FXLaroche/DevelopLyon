<?php

namespace App\Controller;

use App\Model\CategoryManager;
use App\Model\PostManager;
use App\Model\SearchManager;
use App\Model\ThemeManager;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{
    protected Environment $twig;
    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => (ENV === 'dev'),
            ]
        );
        $this->twig->addExtension(new DebugExtension());
    }

    /**
     *  Verifies whether the url contains the expression.
     */
    public function urlContains($expr)
    {
        $regex = '/\/' . $expr . '/';
        $url = $_SERVER['REQUEST_URI'];
        if (preg_match($regex, $url) === 1) {
            return true;
        } elseif (preg_match($regex, $url) === 0) {
            return false;
        }
    }

    public function getPostData(): array
    {
        $postData = [];
        $postManager = new PostManager();
        if ($this->urlContains('post\/') && !$this->urlContains('add')) {
            $postId = (int)trim($_GET['id']);
            $postData = $postManager->selectPostTreeData($postId);
        }
        return $postData;
    }

    public function getCurrentcategoryId(array $postData, string $arrayIndex): ?int
    {
        if (isset($postData[$arrayIndex])) {
            return $postData[$arrayIndex];
        }
        if (isset($_GET['theme'])) {
            $themeManager = new ThemeManager();
            $themeId = (int)trim($_GET['theme']);
            $categoryId = (int)$themeManager->selectCategoryIdFromTheme($themeId)['category_id'];
            return $categoryId;
        }
        return null;
    }

    public function getThemeId()
    {
        if (isset($_GET['theme'])) {
            $themeId = (int)trim($_GET['theme']);

            return $themeId;
        }
        return null;
    }

    public function getThemeList(?int $categoryId): array
    {
        $themeList = [];
        if (!is_null($categoryId)) {
            $themeManager = new ThemeManager();
            $themeList = $themeManager->selectThemesByCategoryId($categoryId);
        }
        return $themeList;
    }

    public function fillParams(array &$paramName, array $data): void
    {
        foreach ($data as $dataName => $dataValue) {
            $paramName[$dataName] = $dataValue;
        }
    }

    public function getCategoryList(): array
    {
        if ($_SERVER['REQUEST_URI'] !== "/") {
            $categoryManager = new CategoryManager();
            return $categoryManager->selectAll();
        }
        return [];
    }

    public function twigRender(string $template, array $params): string
    {
        $searchManager = new SearchManager();
        $searchs = $searchManager->selectAll();
        $params['searchs'] = $searchs;

        $params['postData'] = [];
        $params['themeList'] = [];
        $params['categoryList'] = [];

        $this->fillParams($params['postData'], $this->getPostData());

        $categoryId = $this->getCurrentcategoryId($params['postData'], 'category_id');
        if (!isset($params['postData']['category_id'])) {
            $params['postData']['category_id'] = $categoryId;
        }
        $themeId = $this->getThemeId();
        if (!isset($params['postData']['theme_id'])) {
            $params['postData']['theme_id'] = $themeId;
        }
        $this->fillParams($params['themeList'], $this->getThemeList($categoryId));

        $this->fillParams($params['categoryList'], $this->getCategoryList());

        if (isset($_SESSION['nickname'])) {
            foreach ($_SESSION as $key => $value) {
                $params[$key] = $value;
            }
            $params['connectionOption'] = "Se déconnecter";
            $params['connectionLink']  = "logout";
            $params['profileOption'] = "Mon profil";
            $params['profileLink'] = "show?id=" . $_SESSION['id'];
        } else {
            $params['nickname'] = "";
            $params['connectionOption'] = "Se connecter";
            $params['connectionLink']  = "login";
            $params['profileOption'] = "S'inscrire";
            $params['profileLink'] = "add";
        }
        return $this->twig->render($template, $params);
    }
}
