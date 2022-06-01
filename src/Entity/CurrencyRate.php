<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CurrencyRate
 *
 * @ORM\Table(name="currency_rate", uniqueConstraints={@ORM\UniqueConstraint(name="key_name", columns={"date", "currency"})}, indexes={@ORM\Index(name="foreign_key_name", columns={"currency"})})
 * @ORM\Entity
 */
class CurrencyRate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private \DateTime $date;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=20, scale=10, nullable=false)
     */
    private string $value;

    /**
     * @var \Currency
     *
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="currency", referencedColumnName="id")
     * })
     */
    private \Currency $currency;



    public function getId(): ?int
    {
        return $this->id;
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

    public function getCurrency(): ?int
    {
        return $this->currency;
    }

    public function setCurrency(int $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
