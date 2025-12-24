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
}

Class Moderator extends User
{
    // common methods between admin and editor
   
    // create, modify, delete, publish article
    public function createAndAssignArticle() {
        global $articles;
        $newArticle = new Article();
        $newArticle->readData();
        // assing to the author with minimum articles
        $author = getAuthorWithMinArticles();
        $newArticle->setAuthor($author);
        if ($author) {
            // add article to articles
            $articles[] = $newArticle;
        }
        foreach($articles as $art) {
            print_r($art);
        }
    }

    public function readArticles()
    {
        echo "readArticles:\n";
        global $users;      
        foreach($users as $usr) {
            if ($usr instanceof Author) {
                print_r($usr);
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
    private Author $author;
    private array $categories;
    private DateTime $createdAt;
    private ?DateTime $publishedAt;
    private ?DateTime $updatedAt;

    public  function __construct(int $id = -1, string $title = "", string $content = "", string $status = "draft",
        ?Author $author = null, array $categories = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->excerpt = substr($content, 0, 150); // part of content;
        $this->status = $status;
        $this->author = $author;
        $this->createdAt = new DateTime();
        $this->categories = $categories;
        $this->publishedAt = null;
        $this->updatedAt = null;
    }

    public function readData()
    {
        $this->title = readline("Enter article title: ");
        echo "Enter article content: ";
        $this->content = fgets(STDIN, 2000);
        echo "Enter article excerpt: ";
        $this->excerpt = fgets(STDIN, 2000);
        $this->createdAt = new DateTime();
        // to add id,
    }
    
    public function setAuthor(Author $author) 
    {
        $this->author = $author;
    } 

    public function displayInfo() : void  
    {
        echo "article Info :\n 
            articleId : $this->id, title : $this->title, status : this->status, author = {$this->author->getUserName()} \n
            excerpt: $this->excerpt \n"; 
    }
}

class Category
{
    private int $id;
    private string $name;
    private string $description;
    private DateTime $createdAt;
    private ?Category $parent;

    public function __construct(int $id, string $name, string $description, string $createdAt, Category $parent)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = new DateTime($createdAt);
        $this->parent = $parent;
    }
}

// users 
$users = [new Admin(1, "admin_blog", "admin@blogcms.com","admin123", "2024-01-15 10:00:00", "2025-01-15 10:00:00", true),
    new Admin(5, "admin", "admin@blogcms.com","admin", "2024-01-15 10:00:00", "2025-01-15 10:00:00", true),
    new Editor(2, "marie_dubois", "marie.dubois@email.com","admin123", "2024-02-15 09:15:00", "2025-02-15 09:15:00", "junior"),
    new Editor(6, "editor", "marie.dubois@email.com","editor", "2024-02-15 09:15:00", "2025-02-15 09:15:00", "junior"),
    new Author(3, "marie_dubois", "marie.dubois@email.com", "admin123", "2024-02-10 11:30:00", "2025-02-10 11:30:00", "biographie")
];

$articles = []; // array of article objects
// categories
// articles

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

foreach($users as $usr) {
    print_r($usr);
}

class connectionHandler
{
    private ?User $connectedUser;

    public function __construct() {
        $this->connectedUser = null;
    }

    public function connectUser(User $user) 
    {
        $this->connectedUser = $user;
    }

    public function getConnectedUser() : ?User 
    {
        return $this->connectedUser;
    }
}

$userConnection = new connectionHandler();

function    checkUserCredentials($name, $passwd)
{
    global $users;
    global $userConnection;
    
    foreach($users as $user) {
        if ($user->getUserName() === $name && $user->getPassword() === $passwd) {
            $userConnection->connectUser($user);
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
    global $userConnection;
    $connectedUser = $userConnection->getConnectedUser();
    // print_r($connectedUser);

    switch ($choice) {
        case 1:
            $connectedUser->readArticles();
            break;
        case 2:
    }
}

while (true) 
{
    $connectedUser = $userConnection->getConnectedUser();
    var_dump($connectedUser);
    if (!$connectedUser)
        displayLoginMenu();
    else {
        echo "--------------\n";
        print_r($connectedUser);
        echo "------------\n";
        if ($connectedUser instanceof Editor) {
            echo "yes\n";
            checkEditorOptions();
        }
        break;
        // else if ()
        // else if ()  author / 
    }
    // break;
}

?>