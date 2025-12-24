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

    public function getUserName() 
    {
        return $this->username;
    }
    
    public function getEmail() 
    {
        return $this->email;
    }

    protected function readArticle(Article $article) 
    {
        $article->displayInfo();
    }
}

class Author extends User 
{
    private string $bio;
    public  function __construct(int $id, string $username, string $email, string $password, string $createdAt, string $lastLogin, string $bio)
    {
        User::__construct($id, $username, $email, $password, $createdAt, $lastLogin);
        $this->bio = $bio;
    }
}

Class Moderator extends User
{
    // common methods between admin and editor
   
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
    private DateTime $createdAt;
    private DateTime $publishedAt;
    private DateTime $updatedAt;

    public  function __construct(int $id, string $title, string $content, string $excerpt, string $status,
        Author $author, string $createdAt, string $publishedAt, string $updatedAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->excerpt = $excerpt;
        $this->status = $status;
        $this->author = $author;
        $this->createdAt = new DateTime($createdAt);
        $this->publishedAt = new DateTime($publishedAt);
        $this->updatedAt = new DateTime($updatedAt);
    }

    public function displayInfo() : void  
    {
        echo "article Info :\n 
            articleId : $this->id, title : $this->title, status : this->status, author = {$this->author->getUserName()} \n
            excerpt: $this->excerpt"; 
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
$users = [new Admin(1, "admin_blog", "admin@blogcms.com","$2y$10\$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW", "2024-01-15 10:00:00", "2025-01-15 10:00:00", true), 
new Editor(2, "marie_dubois", "marie.dubois@email.com","$2y$10\$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW", "2024-02-15 09:15:00", "2025-02-15 09:15:00", "junior"),
new Author(3, "marie_dubois", "marie.dubois@email.com", "$2y$10\$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW", "2024-02-10 11:30:00", "2025-02-10 11:30:00", "biographie")
];
// categories
// articles


foreach($users as $usr) {
    print_r($usr);
}

function    displayLoginMenu() {
    echo "Welcome to BlogCms, Please login.\n";
    echo "Enter your name: ";
    $name = fgets(STDIN, 100);
    echo "Enter your password: "; 
    $password = fgets(STDIN, 100);
}

// test 

displayLoginMenu();
?>