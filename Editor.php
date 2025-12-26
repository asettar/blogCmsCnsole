<?php 

require_once 'Moderator.php';

class Editor extends Moderator 
{
    private string $moderationLevel;
    public  function __construct(string $username, string $email, string $password, string $moderationLevel) 
    {
        User::__construct($username, $email, $password);
        $this->moderationLevel = $moderationLevel;
    }
}

?>