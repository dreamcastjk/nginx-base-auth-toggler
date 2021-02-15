<?php

return [
    'error' => [
        'domainNotFound' => 'Config File :file for given domain not found.',
        'restrictedOrUserFile' => 'Can\'t find Restricted or User File string in config.',
        'commentedLines' => 'Some lines already commented.',
        'enabledAuthInConfig' => 'Base auth for :domain already enabled or one of the lines commented in config. Config path: :configPath',
        'fileNotWritten' => 'Something went wrong, check logs.',
        'configCheckFailure' => 'Nginx configs not ok. Config for domain :domain was restored.',
        'unknownConfigFailure' => 'Some configs are\'t ok.' . PHP_EOL . ':error',
        'existingRecordInDb' => 'We have existing record for :domain. Lines might be already commented. Check configs.',
        'nginxBaseCheckFailure' => 'Something bad with configs or with Nginx server.',
    ],
    'success' => [
        'fileWritten' => 'Config file for :domain successfully rewritten and nginx successfully reloaded.',
        'turningOnAll' => 'Base auth for all websites was successfully restored.',
        'enableBaseAuthByDomain' => 'Enabled base auth on :domain.',
    ],
    'noRecords' => 'Nothing to toggle. All good.',
    'noEnvConfigPath' => 'Specify your nginx configs path in env file.',
];
