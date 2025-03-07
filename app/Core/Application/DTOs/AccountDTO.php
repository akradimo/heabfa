<?php

namespace App\Core\Application\DTOs;

class AccountDTO
{
    public string $کد;
    public string $عنوان;
    public string $نوع_حساب;
    public ?int $حساب_والد;
    public ?string $توضیحات;
    public bool $فعال;
    public int $شرکت_id;

    public function __construct(array $data)
    {
        $this->کد = $data['کد'];
        $this->عنوان = $data['عنوان'];
        $this->نوع_حساب = $data['نوع_حساب'];
        $this->حساب_والد = $data['حساب_والد'] ?? null;
        $this->توضیحات = $data['توضیحات'] ?? null;
        $this->فعال = $data['فعال'] ?? true;
        $this->شرکت_id = $data['شرکت_id'];
    }
}