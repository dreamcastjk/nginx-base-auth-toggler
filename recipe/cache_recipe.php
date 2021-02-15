<?php

namespace Deployer;
set('cachetool', '{{socket}}');
set('cachetool_args', '');
set('bin/cachetool', function(){
    return run("{{bin/php}} -r \"echo version_compare(phpversion(), '7.1') == -1 ? 'cachetool-3.2.1.phar' : 'cachetool.phar';\"");
});
/**
 * Clear opcache cache
 */
desc('Clearing OPcode cache');
task('cachetool:clear:opcache', function () {
    $options = get('cachetool');
    if (!empty($options)) {
        $releasePath = get('release_path');

        $fullOptions = get('cachetool_args');

        if (strlen($fullOptions) > 0) {
            $options = "{$fullOptions}";
        } elseif (strlen($options) > 0) {
            $options = "--fcgi={$options}";
        }

        cd($releasePath);
        $hasCachetool = run("if [ -e $releasePath/{{bin/cachetool}} ]; then echo 'true'; fi");

        if ('true' !== $hasCachetool) {
            run("curl -sO https://gordalina.github.io/cachetool/downloads/{{bin/cachetool}}");
        }

        run("{{bin/php}} {{bin/cachetool}} opcache:reset {$options}");
    }
});
