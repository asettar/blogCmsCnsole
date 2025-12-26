<?php 

require_once 'User.php';
require_once 'Article.php';

class Author extends User 
{
    private string $bio;
    private array $articles;
    public  function __construct(string $username, string $email, string $password, string $bio) {
        User::__construct($username, $email, $password);
        $this->bio = $bio;
        $this->articles = [];
    }

    public function getArticles(){
        return $this->articles;
    }

    public function    displayArticles() : void  {
        foreach($this->articles as $article) {
            $article->displayInfo();
        }
    }
    public function    addArticle(Article $newArticle) : void {
        $this->articles[] = $newArticle; 
    }

    public function deleteAuthorArticle(int $articleIdx) : void {
        unset($this->articles[$articleIdx]);
    }
}

?>