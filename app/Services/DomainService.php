<?php


namespace App\Services;

use App\Models\Domain;
use App\Models\NginxServer;
use App\Traits\BaseResponse;
use App\Traits\ConfigFile;
use App\Traits\Logger;
use App\Traits\Nginx;
use Illuminate\Support\Facades\File;

class DomainService
{
    use BaseResponse, Logger, ConfigFile, Nginx;

    const FLASH_ERROR = 'error';

    const FLASH_SUCCESS = 'success';

    const TOGGLE_TYPE_ON = 'on';

    const TOGGLE_TYPE_OFF = 'off';

    protected $domain;

    protected $configFilePath;

    protected $configFileText;

    protected $positionRestrictStarts;

    protected $positionAuthBasicUserStarts;

    protected $symbolBeforeRestrict;

    protected $symbolBeforeAuthBasicUser;

    /**
     * @param string $domain
     * @return DomainService|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string[]
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function disable(string $domain)
    {
        $this->domain = $domain;

        if (!$this->isNginxOk()) {
            return $this->response(
                static::FLASH_ERROR,
                trans('nginx.error.nginxBaseCheckFailure')
            );
        }

        if ($existingDomain = Domain::whereDomain($this->domain)
            ->whereStatus(Domain::STATUS_DISABLED)
            ->first()
        ) {
            return $this->response(
                static::FLASH_ERROR,
                trans('nginx.error.existingRecordInDb', [
                    'domain' => $existingDomain->domain
                ]));
        }

        $this->configFilePath = $this->getConfigFilePath($this->domain);

        if (!$this->configFilePath) {
            return $this->response(static::FLASH_ERROR, trans('nginx.error.domainNotFound', ['file' => '']));
        }

        $this->configFileText = File::get($this->configFilePath);

        [$this->positionRestrictStarts, $this->positionAuthBasicUserStarts] = $this->getStartStringPositions($this->configFileText);

        if (!$this->positionRestrictStarts || !$this->positionAuthBasicUserStarts) {
            return $this->response(static::FLASH_ERROR, trans('nginx.error.restrictedOrUserFile'));
        }

        if ($this->isCommented($this->configFileText)) {
            return $this->response(static::FLASH_ERROR, trans('nginx.error.commentedLines'));
        }

        try {
           $domain = $this->replaceAndWriteFile($this->configFileText, $this->configFilePath);

           if ($this->isNginxOk()) {
               return $this->response(static::FLASH_SUCCESS, trans('nginx.success.fileWritten', ['domain' => $domain->domain]));
           } else {
               $this->toggleBaseAuth($domain, DomainService::TOGGLE_TYPE_ON);
               return $this->response(static::FLASH_ERROR, trans('nginx.error.configCheckFailure', ['domain' => $this->domain]));
           }
        } catch (\Exception $e) {
            $this->logException($e);
            $this->toggleBaseAuth($domain, DomainService::TOGGLE_TYPE_ON);

            if ($domain && $domain instanceof Domain) {
                $domain->delete();
            }

            return $this->response(static::FLASH_ERROR, trans('nginx.error.fileNotWritten'));
        }
    }

    /**
     * Enable base auth for specific domain by a button.
     *
     * @param Domain $domain
     * @return string[]
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function enable(Domain $domain)
    {
        if (!$this->isNginxOk()) {
            return $this->response(
                static::FLASH_ERROR,
                trans('nginx.error.nginxBaseCheckFailure')
            );
        }

        if (!file_exists($domain->file_path)) {
            return $this->response(
                static::FLASH_ERROR,
                trans('nginx.error.domainNotFound', [
                    'file' => $domain->file_path]
                )
            );
        }

        $this->configFileText = File::get($domain->file_path);

        if (!$this->isCommented($this->configFileText)) {
            return $this->response(
                static::FLASH_ERROR,
                trans('nginx.error.enabledAuthInConfig', [
                    'domain' => $domain->domain,
                    'configPath' => $domain->file_path
                ])
            );
        }

        try {
            $this->toggleBaseAuth($domain, DomainService::TOGGLE_TYPE_ON);

            if ($this->isNginxOk()) {
                return $this->response(
                    static::FLASH_SUCCESS,
                    trans('nginx.success.fileWritten', ['domain' => $domain->domain])
                );
            } else {
                $this->toggleBaseAuth($domain, DomainService::TOGGLE_TYPE_OFF);

                [$configsCheckCode, $reloadNginxCode, $checkConfigsOutput] = $this->checkConfigsAndReloadNginx();

                return $this->response(
                    static::FLASH_ERROR,
                    trans('nginx.error.unknownConfigFailure', [
                    'error' => isset($checkConfigsOutput[0]) ? $checkConfigsOutput[0] : ''
                    ])
                );
            }
        } catch (\Exception $e) {
            $this->logException($e);
            $this->restoreConfig($this->configFileText, $domain->file_path);
            return $this->response(static::FLASH_ERROR, trans('nginx.error.fileNotWritten'));
        }
    }

    /**
     * Toggle base auth by type of toggling.
     *
     * @param Domain $domain
     * @param string $toggleType
     * @param bool $deleteModel
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function toggleBaseAuth(Domain $domain, $toggleType = 'off')
    {
        $configFileText = File::get($domain->file_path);

        if ($toggleType == static::TOGGLE_TYPE_OFF) {
            $replacedConfig = strtr($configFileText, [
                NginxServer::AUTH_BASIC_RESTRICTED_STRING => NginxServer::AUTH_BASIC_RESTRICTED_STRING_COMMENTED,
                NginxServer::AUTH_BASIC_USER_FILE_STRING => NginxServer::AUTH_BASIC_USER_FILE_STRING_COMMENTED,
            ]);
        } else {
            $replacedConfig = strtr($configFileText, [
                NginxServer::AUTH_BASIC_RESTRICTED_STRING_COMMENTED => NginxServer::AUTH_BASIC_RESTRICTED_STRING,
                NginxServer::AUTH_BASIC_USER_FILE_STRING_COMMENTED => NginxServer::AUTH_BASIC_USER_FILE_STRING,
            ]);
        }

        file_put_contents($domain->file_path, $replacedConfig);

        if ($toggleType == static::TOGGLE_TYPE_ON) {
            $domain->update([Domain::COLUMN_STATUS => Domain::STATUS_ENABLED]);
        } else {
            $domain->update([Domain::COLUMN_STATUS => Domain::STATUS_DISABLED]);
        }
    }
}
