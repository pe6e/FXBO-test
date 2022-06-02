<?php

namespace App\Entity;

use DateTime;
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
     * @var DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private DateTime $date;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=20, scale=10, nullable=false)
     */
    private string $value;

    /**
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="Currency",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="currency", referencedColumnName="id")
     * })
     */
    private Currency $currency;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return CurrencyRate
     */
    public function setDate(DateTime $date): CurrencyRate
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return CurrencyRate
     */
    public function setValue(string $value): CurrencyRate
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     * @return CurrencyRate
     */
    public function setCurrency(Currency $currency): CurrencyRate
    {
        $this->currency = $currency;
        return $this;
    }
}
