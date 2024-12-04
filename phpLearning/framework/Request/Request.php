<?php

namespace Jia\Framework\Request;

class Request
{
    protected $query;
    protected $post;
    protected $files;
    protected $server;
    protected $header;
    protected $system;
    protected $json;

    protected function __construct(
        array $get,
        array $post,
        array $files,
        array $server
    ) {
        $this->query = $get;
        $this->post = $post;
        $this->files = $files;
        $this->server = $server;
        $this->header = [];
        $this->system = [];
        $this->json = [];

        $this->extractHttpHeader();
    }

    public static function capture(): static
    {
        return new static($_GET, $_POST, $_FILES, $_SERVER);
    }

    public function query(string|int|null $key = null, mixed $default = null) : mixed 
    {
        return data_get($this->query, $key, $default);
    }

    public function post(string|int|null $key = null, mixed $default = null) : mixed 
    {
        return data_get($this->isJson() ? $this->json() : $this->payload(), $key, $default);
    }

    public function input(string|int|null $key = null, mixed $default = null) : mixed 
    {
        return data_get($this->post() + $this->query(), $key, $default);
    }

    public function files(string|int|null $key = null, mixed $default = null) : mixed 
    {
        return data_get($this->files, $key, $default);
    }
  
    public function all(string|int|null $key = null, mixed $default = null) : mixed 
    {
        return data_get($this->input() + $this->files, $key, $default);
    }
    
    public function header(string|int|null $key = null, mixed $default = null) : mixed 
    {
        return data_get($this->header, $key, $default);
    }

    public function system(string|int|null $key = null, mixed $default = null) : mixed 
    {
        return data_get($this->system, $key, $default);
    }

    public function server(string|int|null $key = null, mixed $default = null) : mixed 
    {
        return data_get($this->server, $key, $default);
    }

    public function isJson(): bool
    {
        $contentType = $this->header('CONTENT_TYPE');

        if (is_null($contentType)){
            return false;
        }
        
        return str_contains($contentType, 'json');
    }

    public function requestMethod($lower = true): string
    {
        $method = $this->system('REQUEST_METHOD', '');

        return $lower ? mb_strtolower($method) : $method;
    }

    public function isGet(): bool
    {
        return $this->requestMethod() === 'get';
    }

    public function isPost(): bool
    {
        return $this->requestMethod() === 'post';
    }

    public function ip(): string
    {
        return $this->system('REMOTE_ADDR', '');
    }

    public function scheme($lower = true): string
    {
        $scheme = $this->system('REQUEST_SCHEME', '');

        return $lower ? mb_strtolower($scheme) : $scheme;
    }

    public function isHttp(): bool
    {
        return $this->scheme() === 'http';
    }

    public function isHttps(): bool
    {
        return $this->scheme() === 'https';
    }

    // public function url(): string
    // {
    //     return ;
    // }
    
    protected function payload(string|int|null $key = null, mixed $default = null) : mixed 
    {
        return data_get($this->post, $key, $default);
    }

    protected function json(string|int|null $key = null, mixed $default = null) : mixed 
    {
        $this->json = json_decode(file_get_contents('php://input'), true);

        return data_get($this->json, $key, $default);
    }

    protected function extractHttpHeader()
    {
        foreach ($this->server as $key => $value){
            if (//純正則寫法-> preg_match('/^(?:http|content)/i', $key)
                preg_match('/http/i', $key) ||
                preg_match('/content/i', $key)
                ){
                $this->header[$key] = $value;

                continue;
            }

            $this->system[$key] = $value;
        }
    }
}
