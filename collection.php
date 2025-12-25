<?php 

require_once 'classes.php';

class BlogCms 
{
    private array $users;
    private array $categories;
    private User|Moderator|Author|null $connectedUser;
    
    
    public function __construct() {
        // echo "blogCms constrcutor called\n";
        $this->users = [new Admin("admin_blog", "admin@blogcms.com","admin123", "2024-01-15 10:00:00", "2025-01-15 10:00:00", true),
            new Admin("admin", "admin@blogcms.com","admin", "2024-01-15 10:00:00", "2025-01-15 10:00:00", true),
            new Editor("marie_dubois", "marie.dubois@email.com","admin123", "2024-02-15 09:15:00", "2025-02-15 09:15:00", "junior"),
            new Editor("editor", "marie.dubois@email.com","editor", "2024-02-15 09:15:00", "2025-02-15 09:15:00", "junior"),
            new Author("marie_dubois", "marie.dubois@email.com", "admin123", "2024-02-10 11:30:00", "2025-02-10 11:30:00", "biographie")
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
        echo $name . "  " . $passwd . "\n";
    } 
    
    private function displayEditorMenu() : void  {
        echo " Please select an option :\n";
        echo " 1- Read articles.\n";
        echo " 2- Create article.\n";
        echo " 3- Modify article.\n";
        echo " 4- Delete article.\n";
        echo " 5- Publish article.\n";
        // ... 
    }
    public function    checkEditorOptions() : void  
    {
        $this->displayEditorMenu();
        
        $choice = (int)readline("--> option: ");
        switch ($choice) {
            case 1:
                $this->connectedUser->readArticles($this->getAuthors());
                break;
            case 2:
                $this->connectedUser->createAndAssignArticle($this->getAuthors(), $this->getCategories());
                break;
            case 4:
                $this->connectedUser->deleteArticle($this->getAvailableArticles(), $this->getAuthors());
                break ;
            case 5:
                $this->connectedUser->publishArticle($this->getArticlesInDraft());
                break;
        }
    }
}

// main
$blogCmsApp = new BlogCms();

// foreach($users as $usr) {
//     print_r($usr);
// }

while (true) {
    $connectedUser = $blogCmsApp->getConnectedUser(); 
    // var_dump($connectedUser);
    if (!$connectedUser)
        $blogCmsApp->displayLoginMenu();
    else {
        if ($connectedUser instanceof Editor) {
            $blogCmsApp->checkEditorOptions();
        }
        // 
    }
}

?>