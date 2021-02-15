<?php

return [
    'headers' => [
        'baseAuth' => 'Base Auth',
        'disabledDomainsList' => 'Disabled authorization',
    ],
    'messages' => [
        'emptyList' => 'No disabled domains at this moment.'
    ],
    'disabledList' => [
        'tableColumns' => [
            'domains' => [
                'domain' => 'Domain',
                'filePath' => 'File Path',
                'disabledBy' => 'Disabled by'
            ],
            'timestamps' => [
                'createdAt' => 'Disabled At',
                'updatedAt' => 'Updated At',
            ],
            'actions' => [
                'enable' => 'Enable',
            ],
        ],
    ],
    'tags' => [
        'baseAuth' => [
            'domainLabel' =>'Domain:',
            'domainInputPlaceholder' => 'Type domain',
            'disableButtonText' => 'Disable',
        ],
    ]
];
