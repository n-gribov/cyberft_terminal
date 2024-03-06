#!/usr/bin/env bash
function cleanup()
{
    local pids=$(ps ax | sed 1d | cut -d "?" -f 1)
    if [[ "$pids" != "" ]]; then
        kill -9 $pids >/dev/null 2 >/dev/null
    fi
}
trap cleanup EXIT
