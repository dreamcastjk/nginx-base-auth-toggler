<?php


namespace App\Traits;

use App\Models\NginxServer;

trait Nginx
{
    /**
     * Checks status codes from execution commands for nginx.
     *
     * @return bool
     */
    public function isNginxOk(): bool
    {
        [$configsCheckCode, $reloadNginxCode] = $this->checkConfigsAndReloadNginx();

        if ($configsCheckCode > NginxServer::CODE_SUCCESS_EXEC_CHECK
            || $reloadNginxCode > NginxServer::CODE_SUCCESS_EXEC_CHECK
        ) {
            return false;
        }

        return true;
    }

    /**
     * Checks for correctness configs and try to reload nginx,
     * returning codes of execution. Codes greater then 0 equals error.
     *
     * @return array
     */
    public function checkConfigsAndReloadNginx(): array
    {
        exec(
            NginxServer::BASH_COMMAND_CHECK_NGINX_CONFIGS,
            $checkConfigsOutput,
            $configsCheckCode
        );

        exec(NginxServer::BASH_COMMAND_RELOAD_NGINX, $op, $reloadNginxCode);

        return [$configsCheckCode, $reloadNginxCode, $checkConfigsOutput];
    }

    /**
     * Getting error output from nginx.
     *
     * @return mixed
     */
    public function getNginxConfigError(): string
    {
        exec(
            NginxServer::BASH_COMMAND_CHECK_NGINX_CONFIGS,
            $checkConfigsOutput,
            $configsCheckCode
        );

        return $checkConfigsOutput[0];
    }
}
