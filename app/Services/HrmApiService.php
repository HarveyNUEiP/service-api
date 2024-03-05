<?php

namespace App\Services;

use App\Services\Http\AbstractHttpRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class HrmApiService extends AbstractHttpRequest
{
    /**
     * The cache time-to-live.
     *
     * @var int
     */
    protected static $cache_ttl = 60 * 60;

    /**
     * Get company list.
     *
     * @param  array  $companies  company system numbers from HRM
     * @param  array  $fields     fields to be fetched
     * @return array
     */
    public static function getCompanyList(array $companies = [], array $fields = []): array
    {
        return self::send('GET', '/organization/company/api', [
            'query' => [
                'tar_sn' => implode(',', $companies),
                'field' => implode(',', $fields),
            ],
        ]);
    }

    /**
     * Get a single company.
     *
     * @param  string  $companyId  company system number from HRM
     * @param  array   $fields     fields to be fetched
     */
    public static function getCompany(string $companyId, array $fields = []): array
    {
        return self::send('GET', "/organization/company/api/{$companyId}", [
            'query' => [
                'field' => implode(',', $fields),
            ],
        ]);
    }

    /**
     * Get a single company from cache.
     * If it doesn't exist in the cache, fetch it from the server then cache it.
     *
     * @param  string  $companyId  company system number from HRM
     * @param  bool    $flush      flush the cache
     */
    public static function getCachedCompany(string $companyId, bool $flush = false): array
    {
        if ($flush) {
            Cache::forget("HrmApi:companyInfo:{$companyId}");
        };

        return Cache::remember("HrmApi:companyInfo:{$companyId}", self::$cache_ttl, function () use ($companyId) {
            return self::getCompany($companyId);
        });
    }

    /**
     * Get user list from a sepcific company.
     *
     * @param  string  $companyId  company system number from HRM
     * @param  array   $fields     fields to be fetched
     */
    public static function getUserList(string $companyId, array $fields = []): array
    {
        return self::send('GET', "/organization/users/api", [
            'query' => [
                'c_sn' => $companyId,
                'field' => implode(',', $fields),
            ],
        ]);
    }

    /**
     * Get user list from a sepcific company from cache.
     * If it doesn't exist in the cache, fetch it from the server then cache it.
     *
     * @param  string  $companyId  company system number from HRM
     * @param  bool    $flush      flush the cache
     */
    public static function getCachedUserList(string $companyId, bool $flush = false): array
    {
        if ($flush) {
            Cache::forget("HrmApi:userList:{$companyId}");
        };

        return Cache::remember("HrmApi:userList:{$companyId}", self::$cache_ttl, function () use ($companyId) {
            return self::getUserList($companyId, [
                'code', 'c_name', 'first_name', 'last_name',
                'email', 'phone1', 'ext_no',
                'account_status',
            ]);
        });
    }
}
