<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TurnRepository;

/**
 * @ORM\Entity(repositoryClass=TurnRepository::class)
 */
class Turn
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $area;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $estimate;

    /**
     * @ORM\Column(type="float")
     */
    private float $accuracy;

    /**
     * @ORM\Column(type="integer")
     */
    private int $accuracyCategory;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="turns")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(?int $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getEstimate(): ?int
    {
        return $this->estimate;
    }

    public function setEstimate(?int $estimate): self
    {
        $this->estimate = $estimate;

        return $this;
    }

    public function getAccuracy(): ?float
    {
        return $this->accuracy;
    }

    public function setAccuracy(float $accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getAccuracyCategory(): ?int
    {
        return $this->accuracyCategory;
    }

    public function setAccuracyCategory(int $accuracyCategory): self
    {
        $this->accuracyCategory = $accuracyCategory;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
