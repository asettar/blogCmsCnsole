<?php

class User
{
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public string $role;
    public string $createdAt;
    public string $lastLogin;
}

class Author extends User 
{
    public string $bio;
}

Class Moderator extends User  
{

    // common methods between admin and editor
}

class Editor extends User 
{
    public string $moderationLevel;
}

class admin extends Moderator
{
    public bool $isSuperAdmin;
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