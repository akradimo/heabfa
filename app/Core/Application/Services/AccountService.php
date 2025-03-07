<?php

namespace App\Core\Application\Services;

use App\Core\Domain\Models\Account;
use App\Core\Domain\Repositories\AccountRepository;
use App\Core\Application\DTOs\AccountDTO;

class AccountService
{
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function ایجاد_حساب(AccountDTO $dto)
    {
        return $this->accountRepository->create([
            'کد' => $dto->کد,
            'عنوان' => $dto->عنوان,
            'نوع_حساب' => $dto->نوع_حساب,
            'حساب_والد' => $dto->حساب_والد,
            'توضیحات' => $dto->توضیحات,
            'شرکت_id' => $dto->شرکت_id
        ]);
    }

    public function بروزرسانی_حساب(int $id, AccountDTO $dto)
    {
        $account = $this->accountRepository->findById($id);
        
        return $this->accountRepository->update($account, [
            'عنوان' => $dto->عنوان,
            'نوع_حساب' => $dto->نوع_حساب,
            'توضیحات' => $dto->توضیحات,
            'فعال' => $dto->فعال
        ]);
    }

    public function محاسبه_مانده(int $id)
    {
        $account = $this->accountRepository->findById($id);
        return $account->محاسبه‌مانده();
    }
}