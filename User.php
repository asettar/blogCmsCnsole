<?php

require_once 'Article.php';

class User
{
    private static int $nextId = 0;
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private DateTime $createdAt;
    private DateTime $lastLogin;


    public function __construct(string $username, string $email, string $password) {
        self::$nextId++;
        $this->id = self::$nextId;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = new DateTime();
        // $this->lastLogin = new DateTime($lastLogin);
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


    private function displayListArticlesMenu() {
        // echo "Select one of the option below: "
        echo "List articles by :
                1- Author
                2- Status
                3- Date
                4- categories
            ";
    }

    private function    listArticlesByAuthors(array $authors) {
        echo "List of articles by authors:\n";
        foreach ($authors as $author) {
            $articles = $author->getArticles();
            $articlesCnt = count($articles);
            if (!$articlesCnt) continue;
            echo "Author : {$author->getUserName()} , num of Articles = {$articlesCnt}\n";
            foreach($articles as $article)
                $article->displayShortInfo();
        }
    }

    private function    listArticlesByStatus(array $articles) : void {
        echo "List of articles by status:\n";
        $statusToArticles = [];   // {status => [articleObject]} 
        foreach ($articles as $id => $article) {
            $status = $article->getStatus(); 
            if (!isset($statusToArticles[$status])) $statusToArticles[$status] = [];
            array_push($statusToArticles[$status], $article);  
        }
        foreach ($statusToArticles as $status => $articleObjects) {
            echo "$status Articles : \n";
            foreach($articleObjects as $article) $article->displayShortInfo(); 
        }
    }

    private function listArticlesByDates(array $articles) : void {
        echo "List of articles by Date:\n";
        $yearToArticles = [];  //{year => articleObject}
        foreach ($articles as $id => $article) {
            $year = $article->getCreationYear(); 
            if (!isset($yearToArticles[$year])) $yearToArticles[$year] = [];
            array_push($yearToArticles[$year], $article); 
        } 
        foreach ($yearToArticles as $year => $articleObjects) {
            echo "Articles created at $year : \n";
            foreach($articleObjects as $article) $article->displayShortInfo(); 
        }
    }

    public function listArticles(array $authors, array $articles) : void {
        $this->displayListArticlesMenu();
        $choice = (int)readline("  --> option : ");
        switch ($choice) {
            case 1 :
                $this->listArticlesByAuthors($authors);  
                break ;
            case 2 :
                $this->listArticlesByStatus($articles);
                break ;
                case 3 : 
                    $this->listArticlesByDates($articles);
                break;

            // case 4 
            default :
                echo "invalid choice, try again please.\n";
        }
    }
}

?>