<?php

namespace BePaidAcquiring\Response;

class SubscriptionResponse extends BaseResponse
{
    private string $id;
    private string $token;
    private string $state;
    private string $redirect_url;
    private string $created_at;

    public function __construct(array $fields)
    {
        parent::__construct($fields);
        $this->id = (string) ($this->response['id'] ?? '');
        $this->token = (string) ($this->response['token'] ?? '');
        $this->state = (string) ($this->response['state'] ?? '');
        $this->redirect_url = (string) ($this->response['redirect_url'] ?? '');
        $this->created_at = (string) ($this->response['created_at'] ?? '');
    }

    public function isValid(): bool
    {
        return $this->id && $this->token && $this->state && $this->redirect_url && $this->created_at;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirect_url;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
