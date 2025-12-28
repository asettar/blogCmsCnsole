<?php

require_once 'Collection.php';

$blogCmsApp = new BlogCms();

while (true) {
    $connectedUser = $blogCmsApp->getConnectedUser(); 
    // var_dump($connectedUser);
    if (!$connectedUser)
        $blogCmsApp->displayLoginMenu();
    else {
        if ($connectedUser instanceof Editor) {
            $blogCmsApp->checkModeratorOptions(false);
        }
        else if ($connectedUser instanceof Admin) {
            $blogCmsApp->checkModeratorOptions(true);
        }
        // 
    }
}

?>