<?php


namespace App\Service;


class Cors
{
    /**
     * @param string $env
     */
    public function __construct(private readonly string $env)
    {
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        if ('test' !== $this->env) {
            return [
                'Access-Control-Allow-Origin' => '*',
            ];
        } else {
            return [];
        }
    }
}
