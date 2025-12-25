<?php

class User
{
    private static int $nextId = 0;
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private DateTime $createdAt;
    private DateTime $lastLogin;


    public function __construct(string $username, string $email, string $password, string $createdAt, string $lastLogin) {
        self::$nextId++;
        $this->id = self::$nextId;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = new DateTime($createdAt);
        $this->lastLogin = new DateTime($lastLogin);
    }

    public function getId() {
        return $this->id;
    }

    public function getUserName() {
        return $this->username;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    protected function readArticle(Article $article) {
        $article->displayInfo();
    }
}

class Author extends User 
{
    private string $bio;
    private array $articles;
    public  function __construct(string $username, string $email, string $password, string $createdAt, string $lastLogin, string $bio) {
        User::__construct($username, $email, $password, $createdAt, $lastLogin);
        $this->bio = $bio;
        $this->articles = [];
    }

    function getArticles(){
        return $this->articles;
    }

    function    displayArticles() : void  {
        foreach($this->articles as $article) {
            $article->displayInfo();
        }
    }
    function    addArticle(Article $newArticle) : void {
        $this->articles[] = $newArticle; 
    }
}

Class Moderator extends User
{
    // common methods between admin and editor
   
    private function getArticlesInDraft() : Array  {
        global $users;
        $articlesInDraft = [];
        foreach ($users as $user) {
            if (!$user instanceof Author) continue ;
            foreach($user->getArticles() as $article) {
                if (!$article->isDraft()) continue ;
                $articlesInDraft[$article->getId()] = $article;
            }
        }
        return $articlesInDraft;
    }

        
    private function    getAuthorWithMinArticles(array $authors) : ?Author {
        return count($authors) ? $authors[0] : null; // to fix later
    }

    // create, modify, delete, publish article
    public function createAndAssignArticle(array $authors, array $categories) : void {
        $newArticle = new Article();
        $newArticle->setArticleData($categories);
        // assign to the author with minimum articles
        $author = $this->getAuthorWithMinArticles($authors);
        echo "the article will be set to the author having username {$author->getUserName()}\n";
        $author->addArticle($newArticle);
    }

    public function readArticles(array $users) : void {
        // todo : list by author/category ..
        echo "Available Articles:\n";
        foreach($users as $usr) {
            if ($usr instanceof Author) {
                $usr->displayArticles();
            }
        }
    }

    public function publishArticle() {
        $articles = $this->getArticlesInDraft();  //[id : object]
        if (!count($articles)) {
            echo "No artilce in draft has been found";
            return ;
        }
        do 
        {
            $badIdChosen = true; 
            echo "Available article ids in draft :\n";
            foreach($articles as $articleId => $article) echo "id = $articleId,  title = {$article->getTitle()} \n";
            $chosenId = (int)readline("Please select one of the ids above :");
            echo $chosenId . "\n"; 
            if (isset($articles[$chosenId])) {
                $articles[$chosenId]->setStatus('published');
                echo "article status has succesfully changed to published\n";
                $badIdChosen = false;
            }
            else echo "id chosen not found, please try again\n";
        } while ($badIdChosen);
    }
}

class Editor extends Moderator 
{
    private string $moderationLevel;
    public  function __construct(string $username, string $email, string $password, string $createdAt, string $lastLogin, string $moderationLevel) 
    {
        User::__construct($username, $email, $password, $createdAt, $lastLogin);
        $this->moderationLevel = $moderationLevel;
    }
}

class Admin extends Moderator
{
    private bool $isSuperAdmin;
    public  function __construct(string $username, string $email, string $password, string $createdAt, string $lastLogin, bool $isSuperAdmin) 
    {
        User::__construct($username, $email, $password, $createdAt, $lastLogin);
        $this->isSuperAdmin = $isSuperAdmin;
    }
}

class Article 
{
    private static int $nextId = 0;
    private int $id;
    private string $title;
    private string $content;
    private string $excerpt;
    private string $status;  // draft - published
    private array $categories;
    private DateTime $createdAt;
    private ?DateTime $publishedAt;
    private ?DateTime $updatedAt;

    private function chooseCategories(array $categories) {
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

    public  function __construct(string $title = "", string $content = "", string $status = "draft", array $categories = []) {
        self::$nextId++;
        $this->id = self::$nextId;
        $this->title = $title;
        $this->content = $content;
        $this->excerpt = substr($content, 0, 150); // part of content;
        $this->status = $status;
        $this->createdAt = new DateTime();
        $this->categories = $categories;
        $this->publishedAt = null;
        $this->updatedAt = null;
    }

    public function getId() : int {
        return $this->id;
    } 

    public function getTitle() : string {
        return $this->title;
    }

    public function isDraft() : bool {
        return $this->status === "draft";
    }

    public function setStatus(string $newStatus) : void {
        $this->status = $newStatus;
    }

    public function setArticleData(array $categories) : void
    {
        $this->title = readline("Enter article title: ");
        $this->content = readline("Enter article content: ");
        $this->excerpt = substr($this->content, 0, 150); // part of content;
        $this->createdAt = new DateTime();
        $this->publishedAt = null; 
        $this->updatedAt = null;
        $chosenCategories = $this->chooseCategories($categories);
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
?>