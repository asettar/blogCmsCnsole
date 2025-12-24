<?php

class User
{
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private DateTime $createdAt;
    private DateTime $lastLogin;


    public function __construct(int $id, string $username, string $email, string $password, string $createdAt, string $lastLogin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = new DateTime($createdAt);
        $this->lastLogin = new DateTime($lastLogin);
    }
}

class Author extends User 
{
    private string $bio;
    public  function __construct(int $id, string $username, string $email, string $password, string $createdAt, string $lastLogin, string $bio)
    {
        User::__construct($id, $username, $email, $password, $createdAt, $lastLogin);
        $this->bio = $bio;
    }
}

Class Moderator extends User
{
    // common methods between admin and editor
   
}

class Editor extends Moderator 
{
    private string $moderationLevel;
    public  function __construct(int $id, string $username, string $email, string $password, string $createdAt, string $lastLogin, string $moderationLevel) 
    {
        User::__construct($id, $username, $email, $password, $createdAt, $lastLogin);
        $this->moderationLevel = $moderationLevel;
    }
}

class Admin extends Moderator
{
    private bool $isSuperAdmin;
    private string $moderationLevel;
    public  function __construct(int $id, string $username, string $email, string $password, string $createdAt, string $lastLogin, bool $isSuperAdmin) 
    {
        User::__construct($id, $username, $email, $password, $createdAt, $lastLogin);
        $this->isSuperAdmin = $isSuperAdmin;
    }
}

class Article 
{
    private int $id;
    private string $title;
    private string $content;
    private string $excerpt;
    private string $status;
    private Author $author;
    private DateTime $createdAt;
    private DateTime $publishedAt;
    private DateTime $updatedAt;
}

class Category
{
    private int $id;
    private string $name;
    private string $description;
    private Category $parent;
    private DateTime $createdAt;
}


function    displayLoginMenu() {
    echo "Welcome to BlogCms, Please login.\n";
    echo "Enter your name: ";
    $name = fgets(STDIN, 100);
    echo "Enter your password: "; 
    $password = fgets(STDIN, 100);
}

// test 

displayLoginMenu();
?>