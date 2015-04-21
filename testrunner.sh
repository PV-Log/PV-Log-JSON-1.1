#!/bin/bash
### --------------------------------------------------------------------------
### Run phpunit tests
###
### @author   Knut Kohl <pv@knutkohl.de>
### @license  MIT License (MIT) http://opensource.org/licenses/MIT
### @version  PVLog JSON 1.1
### --------------------------------------------------------------------------

function usage {
    echo
    echo "Run tests for PV-Log SDK generator classes"
    echo
    echo "Usage: $0 [options] [-- [phpunit options]]"
    echo
    echo "Options:"
    echo "    -f, --full    Full test with code coverage report"
    echo "    -h, --help    This help"
    echo
    exit $1
}

### --------------------------------------------------------------------------
args=$(getopt -o fh -l full,help -- "$@")
eval set -- "$args"

while true; do
    case "$1" in
        -f|--full) full=y; shift ;;
        -h|--help) usage ;;
        --) shift; break ;; ### phpunit options follows
        *) break ;;
    esac
done

p=$(dirname $0)

echo
if [ ! "$full" ]; then
    echo Run quick tests ...
    opts="--no-configuration --colors=auto tests"
else
    echo Run full tests ...
    ### Clear out code coverage
    rm -rf $p/coverage/* >/dev/null 2>&1
    echo '<h1>Just a moment please ...</h1>' >$p/coverage/index.html
fi

echo
$p/vendor/bin/phpunit "$@" $opts
