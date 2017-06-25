<?php

namespace RadnoK\PayUBundle\Model;

interface PlanInterface
{
    public function getId(): int;

    public function getCode(): ?string;

    public function getName(): ?string;

    public function getPrice(): ?float;

    public function getDescription(): ?string;

    public function getType(): ?string;

    public function isActive(): bool;
}
