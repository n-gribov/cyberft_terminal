#!/bin/bash
if [ -n "$(type -t ask)" ] && [ "$(type -t ask)" = function ]; then
    : 
else
    ask()
    {
        local answer
        color=$(tput setf 3)
        reset=$(tput sgr0)

        while true; do
            if [ -n "$1" ] ; then
                read -n 1 -p "${color}${1} (y/n):${reset}" answer
            else
                read -n 1 answer
            fi
            echo "" 1>&2
            case $answer in
                [Yy]* ) return 0;;
                [Nn]* ) return 1;;
            esac
        done
    }

    notice()
    {
        yellow=$(tput setf 6)
        red=$(tput setf 4)
        green=$(tput setf 2)
        reset=$(tput sgr0)
        toend=$(tput hpa $(tput cols))$(tput cub 6)

        case "$2" in
            "success")
                echo -e -n "${green}${1}"
                    ;;
            "warning")
                echo -e -n "${red}${1}"
                    ;;
            *)
            echo -e -n "${yellow}${1}"
            ;;

        esac

        echo -n "${reset}"
        echo
    }

    error()
    {
        notice "$1" warning
    }

    checkroot()
    {
        if [ "$EUID" -ne 0 ]
            then echo "Please run as root"
            exit
        fi
    }
fi