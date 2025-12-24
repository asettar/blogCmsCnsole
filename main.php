<?php

class User
{
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private DateTime $createdAt;
    private DateTime $lastLogin;


    public function __construct(int $id, string $username, string $email, string $password, DateTime $createdAt, DateTime $lastLogin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->lastLogin = $lastLogin;
    }
}

class Author extends User 
{
    private string $bio;
}

Class Moderator extends User  
{

    // common methods between admin and editor
}

class Editor extends User 
{
    private string $moderationLevel;
}

class Admin extends Moderator
{
    private bool $isSuperAdmin;
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

displayLoginMenu();
?>