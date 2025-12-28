<?php 

require_once 'Moderator.php';

class Admin extends Moderator
{
    private bool $isSuperAdmin;
    public  function __construct(string $username, string $email, string $password, bool $isSuperAdmin) 
    {
        User::__construct($username, $email, $password);
        $this->isSuperAdmin = $isSuperAdmin;
    }

}

?>