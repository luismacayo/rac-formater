<?php

namespace luismacayo\RacFormater\common\infrastructure;

interface RequestInterface
{
    public function getMethod(): string;

    public function getUrl(): string;

    public function getHeaders(): array;

    public function getBody(): ?string;

    public function getOptions(): array;
}