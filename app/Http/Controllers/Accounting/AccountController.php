<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Core\Application\Services\AccountService;
use App\Core\Application\DTOs\AccountDTO;
use App\Http\Requests\AccountRequest;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index()
    {
        return response()->json([
            'موفقیت' => true,
            'داده' => Account::with('والد')->get()
        ]);
    }

    public function store(AccountRequest $request)
    {
        $dto = new AccountDTO($request->validated());
        $account = $this->accountService->ایجاد_حساب($dto);

        return response()->json([
            'موفقیت' => true,
            'پیام' => 'حساب با موفقیت ایجاد شد',
            'داده' => $account
        ], Response::HTTP_CREATED);
    }

    public function update(AccountRequest $request, int $id)
    {
        $dto = new AccountDTO($request->validated());
        $account = $this->accountService->بروزرسانی_حساب($id, $dto);

        return response()->json([
            'موفقیت' => true,
            'پیام' => 'حساب با موفقیت بروزرسانی شد',
            'داده' => $account
        ]);
    }
}