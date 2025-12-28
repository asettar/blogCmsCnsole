<?php 

require_once 'Admin.php';
require_once 'Moderator.php';
require_once 'User.php';
require_once 'Author.php';
require_once 'Editor.php';
require_once 'Category.php';
require_once 'Article.php';

class BlogCms 
{
    private array $users;
    private array $categories;
    private User|Moderator|Author|null $connectedUser;
    
    
    public function __construct() {
        // echo "blogCms constrcutor called\n";
        $this->users = [new Admin("admin_blog", "admin@blogcms.com","admin123", true),
            new Admin("admin", "admin@blogcms.com","admin", true),
            new Editor("marie_dubois", "marie.dubois@email.com","admin123", "junior"),
            new Editor("editor", "marie.dubois@email.com","editor", "junior"),
            new Author("marie_dubois", "marie.dubois@email.com", "admin123", "biographie")
        ]; 
        $this->categories = [
            new Category("Tech", "Technology", "2024-01-01", null),
            new Category("AI", "Artificial Intelligence", "2024-01-01", null),
            new Category("Web", "Web Development", "2024-01-01", null),
        ];
        $this->connectedUser = null;
    }

    public function getConnectedUser() :?User { 
        return $this->connectedUser;
    }
    
    public function getUsers() : array {
        return $this->users;
    }
    
    public function getCategories() : array {
        return $this->categories;
    }
    
    public function getAuthors() : array {
        $authors = [];
        foreach($this->users as $user) {
            if ($user instanceof Author) 
                $authors[] = $user;
        }
        return $authors;
    }
    
    private function    getAvailableArticles() : array {
        $articles = [];
        $authors = $this->getAuthors();
        foreach($authors as $author) {
            foreach($author->getArticles() as $article) {
                $articles[$article->getId()] = $article;
            }
        }
        return $articles;
    }

    private function getArticlesInDraft() : array  {
        $articlesInDraft = [];
        $authors = $this->getAuthors();
        foreach ($authors as $author) {
            foreach($author->getArticles() as $article) {
                if (!$article->isDraft()) continue ;
                $articlesInDraft[$article->getId()] = $article;
            }
        }
        return $articlesInDraft;
    }
    
    private function    checkUserCredentials($name, $passwd) : void 
    {
        foreach($this->users as $user) {
            if ($user->getUserName() === $name && $user->getPassword() === $passwd) {
                $this->connectedUser = $user;
                break;
            }
        }
    }
    
    public function    displayLoginMenu() : void {
        echo "Welcome to BlogCms, Please login.\n";
        $name = readline("Enter your name: ");
        $passwd = readline("Enter your password: ");
        $this->checkUserCredentials($name, $passwd);
    } 
    
    private function displayEditorMenu() : void  {
        echo "====================================================\n";
        echo " Please select an option :\n";
        echo " 1- list articles.\n";
        echo " 2- Create and assign article.\n";
        echo " 3- Modify article.\n";
        echo " 4- Delete article.\n";
        echo " 5- Publish article.\n";
        // ... 
    }

    public function    checkModeratorOptions(bool $isAdmin) : void  
    {
        $this->displayEditorMenu();
        if ($isAdmin) {
            echo " 6- add User.\n";
            echo " 7- Delete User.\n";
        } 

        $choice = (int)readline("--> option: ");
        if (!$isAdmin && ($choice == 6 || $choice == 7)) $choice = -1;
        switch ($choice) {
            case 1:
                $this->connectedUser->listArticles($this->getAuthors(), $this->getAvailableArticles());
                break;
            case 2:
                $this->connectedUser->createAndAssignArticle($this->getAuthors(), $this->getCategories());
                break;
            case 3:
                $this->connectedUser->modifyArticle($this->getAvailableArticles());
                break ;
            case 4:
                $this->connectedUser->deleteArticle($this->getAvailableArticles(), $this->getAuthors());
                break ;
            case 5:
                $this->connectedUser->publishArticle($this->getArticlesInDraft());
                break;
            case 6:
                // add User
                break;
            case 7: 
                // delete User
            default :
                echo "invalid option, try again please.\n";
        }
    }
}

?>