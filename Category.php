<?php 

class Category
{
    private static int $nextId = 0;
    private int $id;
    private string $name;
    private string $description;
    private DateTime $createdAt;
    private ?Category $parent;

    public function __construct(string $name, string $description, string $createdAt, ?Category $parent)
    {
        self::$nextId++;
        $this->id = self::$nextId;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = new DateTime($createdAt);
        $this->parent = $parent;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}

?>