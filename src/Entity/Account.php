<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $Account_number = null;

    #[ORM\OneToMany(mappedBy: 'history_id', targetEntity: History::class)]
    private Collection $history;

    #[ORM\OneToMany(mappedBy: 'wallet_id', targetEntity: Wallet::class)]
    private Collection $wallet;



    public function __construct()
    {
        $this->history = new ArrayCollection();
        $this->wallet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountNumber(): ?string
    {
        return $this->Account_number;
    }

    public function setAccountNumber(string $Account_number): static
    {
        $this->Account_number = $Account_number;

        return $this;
    }


    /**
     * @return Collection<int, Wallet>
     */
    public function getWallet(): Collection
    {
        return $this->wallet;
    }

    public function addWallet(Wallet $wallet): static
    {
        if (!$this->wallet->contains($wallet)) {
            $this->wallet->add($wallet);
            $wallet->setAccountId($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): static
    {
        if ($this->wallet->removeElement($wallet)) {
            // set the owning side to null (unless already changed)
            if ($wallet->getAccountId() === $this) {
                $wallet->setAccountId(null);
            }
        }

        return $this;
    }
}
