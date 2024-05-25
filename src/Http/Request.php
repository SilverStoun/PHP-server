<?php

declare(strict_types=1);

namespace App\Http;

final class Request
{
    private string $method;
    private string $uri;
    private array $headers = [];
    private array $parameters = [];

    public function __construct(
        string $method,
        string $uri,
        array $headers,
    ) {
        $this->method = strtoupper($method);
        $this->headers = $headers;

        $explodedUri = explode('?', $uri);
        $this->uri = $explodedUri[0];
        parse_str($explodedUri[1] ?? '', $this->parameters);
    }

    public static function tryFromHeaderString(string $headerString): self
    {
        $lines = explode('\n', $headerString);
        [$method, $uri] = explode(' ', array_shift($lines));

        $headers = [];
        foreach ($lines as $line) {
            $line = trim($line);

            if (strpos($line, ': ') !== false) {
                [$key, $value] = explode(': ', $line);
                $headers[$key] = $value;
            }
        }

        return new self($method, $uri, $headers);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHeaderByKey(string $key, ?string $default = null): ?string
    {
        return $this->headers[$key] ?? $default;
    }

    public function getParamByKey(string $key, ?string $default = null): ?string
    {
        return $this->parameters[$key] ?? $default;
    }
}
