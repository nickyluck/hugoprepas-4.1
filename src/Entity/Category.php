<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * Constantes fixant les identifiants des catégories racines.
     */
    const HOME_ID = 1;      // Catégorie racine de la page d'accueil.
    const GRADES_ID = 2;    // Catégorie racine des sites des classes.
    const MISC_ID = 3;      // Catégorie racine des catégories diverses.

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent", orphanRemoval=true)
     */
    private $children;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description = "";

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVisible = true;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="category", orphanRemoval=true)
     */
    private $articles;

    /**
     * @ORM\Column(type="smallint")
     */
    private $place = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isProtected = false;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="offsprings")
     * @ORM\JoinTable(name="genealogy", joinColumns={@ORM\JoinColumn(name="offspring_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="ancestor_id", referencedColumnName="id")})
     */
    private $ancestors;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="ancestors")
     */
    private $offsprings;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->ancestors = new ArrayCollection();
        $this->offsprings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;
        while ($parent !== null) {
            $parent->addOffspring($this);
            $parent = $parent->getParent();
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(?int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getIsProtected(): ?bool
    {
        return $this->isProtected;
    }

    public function setIsProtected(bool $isProtected): self
    {
        $this->isProtected = $isProtected;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAncestors(): Collection
    {
        return $this->ancestors;
    }

    public function addAncestor(self $ancestor): self
    {
        if (!$this->ancestors->contains($ancestor)) {
            $this->ancestors[] = $ancestor;
        }

        return $this;
    }

    public function removeAncestor(self $ancestor): self
    {
        $this->ancestors->removeElement($ancestor);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getOffsprings(): Collection
    {
        return $this->offsprings;
    }

    public function addOffspring(self $offspring): self
    {
        if (!$this->offsprings->contains($offspring)) {
            $this->offsprings[] = $offspring;
            $offspring->addAncestor($this);
        }

        return $this;
    }

    public function removeOffspring(self $offspring): self
    {
        if ($this->offsprings->removeElement($offspring)) {
            $offspring->removeAncestor($this);
        }

        return $this;
    }
}
