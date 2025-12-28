<?php 

require_once 'User.php';

Class Moderator extends User
{
     private function    getChosenAuthor(array $authors) : ?Author {
        if (!count($authors)) {
            echo "no authors found.\n";
            return null;
        }
        echo "Please select an id from the availables authors ids: \n";
        foreach ($authors as $author) {
            echo " {$author->getId()}- {$author->getUserName()}\n";
        }
        $chosenId = (int)readline("--> id :");
        foreach($authors as $author) {
            if ($author->getId() == $chosenId) return $author;
        }
        echo "selected id is invalid, try again please.\n";
        return null ;
    }

    public function createAndAssignArticle(array $authors, array $categories) : void {
        $author = $this->getChosenAuthor($authors);
        if (!$author) return ;
        $newArticle = new Article();
        $newArticle->setArticleData($categories);
        echo "the article will be set to the author having username {$author->getUserName()}\n";
        $author->addArticle($newArticle);
    }


    public function publishArticle(array $articles) {
        if (!count($articles)) {
            echo "No artilce in draft has been found.\n";
            return ;
        }
        echo "Available article ids in draft :\n";
        foreach($articles as $articleId => $article) echo "id = $articleId,  title = {$article->getTitle()} \n";
        $chosenId = (int)readline("Please select one of the ids above :");
        if (isset($articles[$chosenId])) {
            $articles[$chosenId]->setStatus('published');
            echo "article status has succesfully changed to published\n";
        }
        else echo "id chosen not found, please try again.\n";
    }

    private function deleteArticleById(int $chosenId, array $authors) {
        foreach($authors as $author) {
            $articles = $author->getArticles();
            foreach($articles as $idx => $article) {
                if ($article->getId() !== $chosenId) continue;
                $author->deleteAuthorArticle($idx);
                return ;
            }
        }
    }

    private function    getChosenArticleId(array $articles) : int {
        if (!count($articles)) {
            echo "No artilces has been found.\n";
            return -1;
        } 
        echo "All available article ids :\n";
        foreach($articles as $articleId => $article) echo "id = $articleId,  title = {$article->getTitle()} \n";
        $chosenId = (int)readline("Please select one of the ids above :");
        if (!isset($articles[$chosenId])) {
            echo "id chosen not found, please try again\n";
            return -1;
        }
        return $chosenId;
    }

    public function deleteArticle(array $articles, array $authors) : void {
        $chosenId = $this->getChosenArticleId($articles);
        if ($chosenId == -1) return;
        $confirm = readline("Are you sure (Y / N) ? :");
        if ($confirm === 'Y') {
            $this->deleteArticleById($chosenId, $authors);
            echo "article has been succesfully deleted\n";
        }
    }
    
    private function    checkModificationOptions() : int  {
        echo "Please section one of these modification options possibles:\n";
        echo "  1-Modify title.\n";
        echo "  2-Modify status.\n";
        echo "  3-Modify content.\n";
        
        $choice = (int)readline("  --> option: ");
        return $choice;
    }

    public function modifyArticle(array $articles) {
        $chosenId = $this->getChosenArticleId($articles);
        if ($chosenId == -1) return ;
        $option = $this->checkModificationOptions();
        $article = $articles[$chosenId];
        switch ($option) {
            case 1:
                $article->readTitleInput();
                break;
            case 2:
                $article->readStatusInput();
                break;
            case 3:
                $article->readContentInput();
                break;
            default :
                echo "invalid option, try again please.\n";
        }
    }
}

?>