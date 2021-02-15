<?php


namespace App\Traits;


use App\Models\Bash;
use App\Models\Domain;
use App\Models\NginxServer;
use File;

trait ConfigFile
{
    /**
     * Searching domain in files at nginx directory, receiving path to file for website or null.
     *
     * @param string $domain
     * @return string
     */
    private function getConfigFilePath(string $domain): string
    {
        return trim(shell_exec(strtr(Bash::BASH_COMMAND_TO_FIND, [
            Domain::PLACEHOLDER_DOMAIN => $domain,
            NginxServer::PLACEHOLDER_CONFIG_PATH => config('nginx.config_path')
        ])));
    }

    /**
     * Receiving starting positions of searched string in config file string.
     *
     * @param string $configFileText
     * @return array
     */
    private function getStartStringPositions(string $configFileText): array
    {
        return [
            strpos($configFileText, NginxServer::AUTH_BASIC_RESTRICTED_STRING),
            strpos($configFileText, NginxServer::AUTH_BASIC_USER_FILE_STRING)
        ];
    }

    /**
     * Marks us about already commented lines in config string.
     *
     * @param string $configFileText
     * @return bool
     */
    private function isCommented(string $configFileText): bool
    {
        [$this->positionRestrictStarts, $this->positionAuthBasicUserStarts] = $this->getStartStringPositions($configFileText);

        $this->symbolBeforeRestrict = substr($configFileText, $this->positionRestrictStarts - 1, 1);
        $this->symbolBeforeAuthBasicUser = substr($configFileText, $this->positionAuthBasicUserStarts - 1, 1);

        if ($this->symbolBeforeRestrict === NginxServer::SYMBOL_TO_REPLACE || $this->symbolBeforeAuthBasicUser === NginxServer::SYMBOL_TO_REPLACE) {
            return true;
        }

        return false;
    }

    /**
     * Comments specific lines and creates domain object.
     *
     * @param string $configFileText
     * @param string $configFilePath
     * @return Domain|\Illuminate\Database\Eloquent\Model
     */
    private function replaceAndWriteFile(string $configFileText, string $configFilePath): Domain
    {
        [$this->positionRestrictStarts, $this->positionAuthBasicUserStarts] = $this->getStartStringPositions($configFileText);

        $newString = substr_replace($configFileText, NginxServer::SYMBOL_TO_REPLACE, $this->positionRestrictStarts, 0);
        $newString = substr_replace($newString, NginxServer::SYMBOL_TO_REPLACE, $this->positionAuthBasicUserStarts + 1, 0);

        file_put_contents($configFilePath, $newString);

        return Domain::create([
            'domain' => $this->domain,
            'file_path' => $configFilePath,
            'old' => $configFileText,
            'new' => $newString,
            'status' => Domain::STATUS_DISABLED
        ]);
    }
}
