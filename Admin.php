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

    private function getNewUserRole() : ?string {
        echo "Select new User Role: 
            -admin.
            -visitor.
            -author.
            -edtior.\n";
        $role = readline(' --> role : ');
        if (!in_array($role, ['admin', 'visitor', 'author', 'editor'])) {
            echo "invalid role. try again please.\n";
            $role = null;
        } 
        return $role;
    }

    private function getnewUserData() : ?User {
        $role = $this->getNewUserRole();
        if (!$role) return null;
        $username = readline('username : ');
        $email = readline('email : ');
        $passwd = readline('password : ');

        $user = null;
        switch ($role) {
            case 'visitor' :
                $user = new User($username, $email, $passwd); 
                break;
            case 'admin' :
                $isSuperAdmin = (bool)readline("is super admin (0 / 1) ?: ");
                $user = new Admin($username, $email, $passwd, $isSuperAdmin); 
                break;
            case 'editor' : 
                $moderationLevel = readline("moderation level(junior/senior/cheif): ");
                $user = new Editor($username, $email, $passwd, $moderationLevel); 
                break;
            case 'author' :
                $bio = readline("bio: ");
                $user = new Author($username, $email, $passwd, $bio); 
                break ;
        };
        return $user;
    }

    // role / username / passwd / 
    public  function addNewUser(&$users) : void {
        $newUser = $this->getnewUserData();
        print_r($users);
        if ($newUser)
            $users[] = $newUser;
    }
}
?>