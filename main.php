<?php

class User
{
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private DateTime $createdAt;
    private DateTime $lastLogin;


    public function __construct(int $id, string $username, string $email, string $password, string $createdAt, string $lastLogin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = new DateTime($createdAt);
        $this->lastLogin = new DateTime($lastLogin);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserName() 
    {
        return $this->username;
    }
    
    public function getEmail() 
    {
        return $this->email;
    }

    public function getPassword() 
    {
        return $this->password;
    }

    protected function readArticle(Article $article) 
    {
        $article->displayInfo();
    }
}

class Author extends User 
{
    private string $bio;
    private array $articles;
    public  function __construct(int $id, string $username, string $email, string $password, string $createdAt, string $lastLogin, string $bio)
    {
        User::__construct($id, $username, $email, $password, $createdAt, $lastLogin);
        $this->bio = $bio;
        $this->articles = [];
    }
    function    displayArticles() 
    {
        foreach($this->articles as $article) {
            $article->displayInfo();
        }
    }
    function    addArticle(Article $newArticle) 
    {
        $this->articles[] = $newArticle; 
    }
}

Class Moderator extends User
{
    // common methods between admin and editor
   
    // create, modify, delete, publish article
    public function createAndAssignArticle() {
        $newArticle = new Article();
        $newArticle->setArticleData();
        // assign to the author with minimum articles
        $author = getAuthorWithMinArticles();
        echo "the article will be set to the author having username {$author->getUserName()}\n";
        $author->addArticle($newArticle);
    }

    public function readArticles()
    {
        // todo : list by author/category ..
        echo "Available Articles:\n";
        global $users;      
        foreach($users as $usr) {
            if ($usr instanceof Author) {
                $usr->displayArticles();
            }
        }
    }
}

class Editor extends Moderator 
{
    private string $moderationLevel;
    public  function __construct(int $id, string $username, string $email, string $password, string $createdAt, string $lastLogin, string $moderationLevel) 
    {
        User::__construct($id, $username, $email, $password, $createdAt, $lastLogin);
        $this->moderationLevel = $moderationLevel;
    }
}

class Admin extends Moderator
{
    private bool $isSuperAdmin;
    public  function __construct(int $id, string $username, string $email, string $password, string $createdAt, string $lastLogin, bool $isSuperAdmin) 
    {
        User::__construct($id, $username, $email, $password, $createdAt, $lastLogin);
        $this->isSuperAdmin = $isSuperAdmin;
    }
}

class Article 
{
    private int $id;
    private string $title;
    private string $content;
    private string $excerpt;
    private string $status;  // draft - published
    private array $categories;
    private DateTime $createdAt;
    private ?DateTime $publishedAt;
    private ?DateTime $updatedAt;

    private function chooseCategories()
    {
        global $categories;
        echo "Available categories:\n";
        foreach ($categories as $category) {
            echo "{$category->getId()} - {$category->getName()}\n";
        }

        $input = readline("Enter category IDs belonging to the article separted by '-': ");
        $ids = explode('-', $input);  // split to array of ids

        foreach ($categories as $category) {
            if (in_array($category->getId(), $ids)) {
                $this->categories[] = $category;
            }
        }
    }

    public  function __construct(int $id = -1, string $title = "", string $content = "", string $status = "draft", array $categories = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->excerpt = substr($content, 0, 150); // part of content;
        $this->status = $status;
        $this->createdAt = new DateTime();
        $this->categories = $categories;
        $this->publishedAt = null;
        $this->updatedAt = null;
    }

    public function setArticleData() : void
    {
        $this->title = readline("Enter article title: ");
        $this->content = readline("Enter article content: ");
        $this->createdAt = new DateTime();
        $this->publishedAt = null; 
        $this->updatedAt = null;
        // to add id,
        $chosenCategories = $this->chooseCategories();
        $chosenCategories = ($chosenCategories);
    }
    
    public function displayInfo() : void  
    {
        echo "article Info : 
            articleId : $this->id, title : $this->title, status : $this->status.
            content: $this->content 
            categories: ";
        foreach($this->categories as $category) echo $category->getName() . ", ";
        echo "\n";
    }
}

class Category
{
    private int $id;
    private string $name;
    private string $description;
    private DateTime $createdAt;
    private ?Category $parent;

    public function __construct(int $id, string $name, string $description, string $createdAt, ?Category $parent)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = new DateTime($createdAt);
        $this->parent = $parent;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}

// users 
$users = [new Admin(1, "admin_blog", "admin@blogcms.com","admin123", "2024-01-15 10:00:00", "2025-01-15 10:00:00", true),
    new Admin(5, "admin", "admin@blogcms.com","admin", "2024-01-15 10:00:00", "2025-01-15 10:00:00", true),
    new Editor(2, "marie_dubois", "marie.dubois@email.com","admin123", "2024-02-15 09:15:00", "2025-02-15 09:15:00", "junior"),
    new Editor(6, "editor", "marie.dubois@email.com","editor", "2024-02-15 09:15:00", "2025-02-15 09:15:00", "junior"),
    new Author(3, "marie_dubois", "marie.dubois@email.com", "admin123", "2024-02-10 11:30:00", "2025-02-10 11:30:00", "biographie")
];

$categories = [
    new Category(1, "Tech", "Technology", "2024-01-01", null),
    new Category(2, "AI", "Artificial Intelligence", "2024-01-01", null),
    new Category(3, "Web", "Web Development", "2024-01-01", null),
];

function    getAuthorWithMinArticles() 
{
    
    global $users;

    foreach($users as $user) {
        if ($user instanceof Author) {
            return $user;  // return first author for now, to fix later
        }
    }
    return null;
}

// foreach($users as $usr) {
//     print_r($usr);
// }

/** @var ?User|Moderator|Author $connectedUser */
$connectedUser = null;

function    checkUserCredentials($name, $passwd)
{
    global $users, $connectedUser;
    
    foreach($users as $user) {
        if ($user->getUserName() === $name && $user->getPassword() === $passwd) {
            $connectedUser = $user;
            break;
        }
    }
}

function    displayLoginMenu() {
    echo "Welcome to BlogCms, Please login.\n";
    $name = readline("Enter your name: ");
    $passwd = readline("Enter your password: ");
    checkUserCredentials($name, $passwd);
    echo $name . "  " . $passwd . "\n";
} 

function    displayEditorMenu() 
{
    echo " Please select an option :\n";
    echo " 1- Read articles.\n";
    echo " 2- Create article.\n";
    echo " 3- Modify article.\n";
    echo " 4- Delete article.\n";
    echo " 5- Publish article.\n";
    // ... 
}

function    checkEditorOptions() 
{
    displayEditorMenu();
    
    $choice = (int)readline("--> option: ");
    global $connectedUser;

    switch ($choice) {
        case 1:
            $connectedUser->readArticles();
            break;
        case 2:
            $connectedUser->createAndAssignArticle();
            break;
    }
}

while (true) 
{
    // var_dump($connectedUser);
    if (!$connectedUser)
        displayLoginMenu();
    else {
        if ($connectedUser instanceof Editor) {
            checkEditorOptions();
        }
    }
}

?>