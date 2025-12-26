<?php 

require_once 'Category.php';

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

    public function getStatus() : string {
        return $this->status;
    }

    public function getCreationYear() : string {
        return $this->createdAt->format('Y');
    }

    public function isDraft() : bool {
        return $this->status === "draft";
    }

    public function setStatus(string $newStatus) : void {
        $this->status = $newStatus;
    }

    public function setContent(string $content) : void {
        $this->content = $content;
    }

    public function setTitle(string $title) : void {
        $this->title = $title;
    }

    public  function    readTitleInput() : void {
        $this->title = readline("Enter article title: ");
    }
    
    public  function    readContentInput() : void {
        $this->content = readline("Enter article content: ");
        $this->excerpt = substr($this->content, 0, 150);
    }

    public function readStatusInput() {
        echo "Please select article status :\n";
        echo "1- draft.\n";
        echo "2- published.\n";
        $option = (int)readline(" --> option: ");
        if ($option == 1) $this->status = 'draft';
        else if ($option == 2) $this->status = 'published';
        else echo "invalid option, please try again.\n";
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
    
    public function displayShortInfo() {
        echo " title = {$this->getTitle()}, status = {$this->getStatus()} \n";
    } 
}


?>