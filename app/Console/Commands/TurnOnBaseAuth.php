<?php

namespace App\Console\Commands;

use App\Helpers\MattermostHelper;
use App\Models\Domain;
use App\Models\NginxServer;
use App\Services\DomainService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class TurnOnBaseAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'base-auth:turn-on';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enabling on base auth on given domains';

    /**
     * @var array
     */
    private array $enabledDomains = [];

    /**
     * Execute the console command.
     *
     * @param DomainService $domainService
     * @return array|void
     */
    public function handle(DomainService $domainService)
    {
        if (!$domainService->isNginxOk()) {

            $errorMessage = $domainService->getNginxConfigError();

            MattermostHelper::sendMessage(
                MattermostHelper::NOTIFY_TYPE_CHANNEL,
                trans('nginx.error.unknownConfigFailure', [
                    'error' => ''
                ]),
                $errorMessage,
            );

            return $this->dumpMessage(trans('nginx.error.unknownConfigFailure', [
                'error' => $errorMessage
            ]));
        }

        if (!Domain::whereStatus(Domain::STATUS_DISABLED)->count()) {
            return $this->dumpMessage(trans('nginx.noRecords'));
        }

        $this->switchBaseAuth($domainService, DomainService::TOGGLE_TYPE_ON);

        [
            $configsCheckCode,
            $reloadNginxCode,
            $checkConfigsOutput,
        ] = $domainService->checkConfigsAndReloadNginx();

        if (
            $configsCheckCode > NginxServer::CODE_SUCCESS_EXEC_CHECK
            || $reloadNginxCode > NginxServer::CODE_SUCCESS_EXEC_CHECK
        ) {
            $this->switchBaseAuth($domainService, DomainService::TOGGLE_TYPE_OFF);

            $errorMessage = isset($checkConfigsOutput[0]) ? $checkConfigsOutput[0] : '';

            MattermostHelper::sendMessage(
                MattermostHelper::NOTIFY_TYPE_CHANNEL,
                trans('nginx.error.unknownConfigFailure', [
                    'error' => ''
                ]),
                $errorMessage
            );

            return $this->dumpMessage(trans('nginx.error.unknownConfigFailure', [
                'error' => $errorMessage
            ]));
        }

        $this->dumpMessage(trans('nginx.success.turningOnAll'));

        MattermostHelper::sendMessage(
            MattermostHelper::NOTIFY_TYPE_CHANNEL,
            trans('nginx.success.turningOnAll')
        );
    }

    /**
     * @param DomainService $domainService
     * @param string $toggleType
     */
    private function switchBaseAuth(DomainService $domainService, string $toggleType)
    {
        if ($toggleType == DomainService::TOGGLE_TYPE_ON) {
            Domain::whereStatus(Domain::STATUS_DISABLED)
                ->each(function (Domain $domain) use ($domainService, $toggleType){
                try {
                    $domainService->toggleBaseAuth($domain, $toggleType);
                    $this->enabledDomains[] = $domain;
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    dump(Carbon::now() . PHP_EOL . $e->getMessage());
                }
            });
        } else {
            collect($this->enabledDomains)->each(function (Domain $domain) use ($domainService, $toggleType) {
                $domainService->toggleBaseAuth($domain, $toggleType);
            });
        }
    }

    /**
     * @param string $message
     */
    private function dumpMessage(string $message)
    {
        dump(Carbon::now() . ' ' . $message);
    }
}
