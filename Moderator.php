<?php 

require_once 'User.php';

Class Moderator extends User
{
    // common methods between admin and editor
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

    public function readArticles(array $authors) : void {
        // todo : list by author/category ..
        echo "Available Articles:\n";
        foreach($authors as $author) {
            $author->displayArticles();
        }
    }

    public function publishArticle(array $articles) {
        if (!count($articles)) {
            echo "No artilce in draft has been found";
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