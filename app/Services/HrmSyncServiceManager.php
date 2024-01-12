<?php

namespace App\Services;

use App\Services\Contracts\AccountManagementService;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HrmSyncServiceManager
{
    public const HRM_NUEIP_COMPANY_ID = 121;

    public const ADMIN_ACCOUNT_STATUS = true;

    public const DEFAULT_ACCOUNT_STATUS = false;

    public const ACCOUNT_FIELDS = [
        'c_name',
        'last_name',
        'first_name',
        'phone1',
        'ext_no',
        'mobile1',
        'email',
    ];

    public function __construct(
        protected AccountManagementService $accountManagementService,
        protected RoleBasedAccessControlService $roleBasedAccessControlService,
    ) {}

    /**
     * Sync accounts from HRM
     *
     * @return void
     */
    public function syncAccount()
    {
        $hrmAccounts = HrmApiService::getUserList(self::HRM_NUEIP_COMPANY_ID, self::ACCOUNT_FIELDS);

        $hrmAccounts = Arr::keyBy($hrmAccounts, 'code');

        $existingAccounts = array_column($this->accountManagementService->allUsers(), 'id', 'user_no');

        $this->batchInsertAccounts($hrmAccounts, $existingAccounts);

        $this->batchUpdateAccounts($hrmAccounts, $existingAccounts);

        $this->initAdminRole();
    }

    /**
     * Sync account by HRM ID
     *
     * @param int $hrmId
     * @return mixed
     */
    public function syncAccountByHrmId(int $hrmId)
    {
        $datetime = Carbon::now()->toDateTimeString();

        $hrmAccounts = HrmApiService::getUserList(self::HRM_NUEIP_COMPANY_ID, self::ACCOUNT_FIELDS);

        $accountToBeSynced = $hrmAccounts[$hrmId] ?? null;

        if ($accountToBeSynced === null) {
            return null;
        }

        return $this->accountManagementService->create([
            'hrm_id' => $accountToBeSynced['s_sn'],
            'user_no' => $accountToBeSynced['code'],
            'chinese_name' => $accountToBeSynced['c_name'],
            'english_name' => $accountToBeSynced['first_name'] . ' ' . $accountToBeSynced['last_name'],
            'phone_number' => isset($accountToBeSynced['phone1'])
                ? $accountToBeSynced['phone1'] . (isset($accountToBeSynced['ext_no']) ? '#' . $accountToBeSynced['ext_no'] : '')
                : null,
            'mobile_number' => $accountToBeSynced['mobile1'] ?? null,
            'email' => $accountToBeSynced['email'] ?? null,
            'created_at' => $datetime,
            'created_by' => 'system',
            'updated_at' => $datetime,
            'updated_by' => 'system',
            'is_valid' => 1,
        ]);
    }

    /**
     * Batch insert accounts
     *
     * @param array<string,array<mixed>> $hrmAccounts
     * @param array<string> $existingAccounts
     * @return void
     */
    private function batchInsertAccounts(array $hrmAccounts, array $existingAccounts)
    {
        $datetime = Carbon::now()->toDateTimeString();

        $insertData = array_map(function ($hrmData) use ($datetime) {
            return [
                'hrm_id' => $hrmData['s_sn'],
                'user_no' => $hrmData['code'],
                'chinese_name' => $hrmData['c_name'],
                'english_name' => $hrmData['first_name'] . ' ' . $hrmData['last_name'],
                'phone_number' => isset($hrmData['phone1'])
                    ? $hrmData['phone1'] . (isset($hrmData['ext_no']) ? '#' . $hrmData['ext_no'] : '')
                    : null,
                'mobile_number' => $hrmData['mobile1'] ?? null,
                'email' => $hrmData['email'] ?? null ,
                'created_at' => $datetime,
                'created_by' => 'system',
                'updated_at' => $datetime,
                'updated_by' => 'system',
                'is_valid' => $hrmData['code'] === 'admin' ? self::ADMIN_ACCOUNT_STATUS : self::DEFAULT_ACCOUNT_STATUS,
            ];
        }, array_diff_key($hrmAccounts, $existingAccounts));

        $this->accountManagementService->insert($insertData);
    }

    /**
     * Batch update accounts
     *
     * @param array<string,array<mixed>> $hrmAccounts
     * @param array<string> $existingAccounts
     * @return void
     */
    private function batchUpdateAccounts(array $hrmAccounts, array $existingAccounts)
    {
        $datetime = Carbon::now()->toDateTimeString();

        $updateAccounts = $this->accountManagementService->getUserByIds($existingAccounts);

        DB::beginTransaction();

        try {
            foreach ($updateAccounts as $account) {
                $hrmData = $hrmAccounts[$account->user_no] ?? null;

                if (!$hrmData) {
                    continue;
                }

                $updateAttributes = [
                    'chinese_name' => $hrmData['c_name'],
                    'english_name' => $hrmData['first_name'] . ' ' . $hrmData['last_name'],
                    'phone_number' => isset($hrmData['phone1'])
                        ? $hrmData['phone1'] . (isset($hrmData['ext_no']) ? '#' . $hrmData['ext_no'] : '')
                        : null,
                    'mobile_number' => $hrmData['mobile1'] ?? null,
                    'email' => $hrmData['email'] ?? null,
                    'updated_at' => $datetime,
                    'updated_by' => 'system',
                ];

                $this->accountManagementService->update($account->id, $updateAttributes);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Batch update failed',
            ], 400);
        }
    }

    /**
     * Initialize admin user's role
     *
     * @return void
     */
    private function initAdminRole()
    {
        $this->roleBasedAccessControlService->initializeAdminUserRole();
    }
}
