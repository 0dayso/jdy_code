#!/bin/bash
set -e

prj_path=$(cd $(dirname $0); pwd -P)
apuppy_dir="$prj_path/apuppy"
devops_prj_path="$apuppy_dir/devops" # should be defined to this name


load_init_module=1
php_image=php:5.6.8-fpm
src_path_in_docker="/opt/src"

function init_config_by_developer_name(){
    app="qiniu-api-container"
    php_container="$app-php"
    if [ "$(uname)" == "Darwin" ]; then
        app_storage_path="~/opt/data/$app"
    else
        app_storage_path="/opt/data/$app"
    fi
    logs_dir="$app_storage_path/logs"
    git_log_path="$apuppy_dir/api/tmp"
}

function load_config_for_deploy(){
    # There is nothing to do.
    return;
}

function do_init_for_deploy(){
    # There is nothing to do.
    return;
}

function pull() {
    pull_image $php_image
}

source $devops_prj_path/base.sh


run_php() {
    local api_path="$apuppy_dir/api"
    local args="--restart always"
    args="$args --cap-add SYS_PTRACE"

    args="$args -v $logs_dir:/var/log/php"
    args="$args -v $apuppy_dir/config/php/conf/php-base.ini:/usr/local/etc/php/php.ini"
    args="$args -v $apuppy_dir/config/php/conf/php-fpm.conf:/usr/local/etc/php-fpm.conf"

    args="$args -v $api_path:$src_path_in_docker"
    args="$args -w $src_path_in_docker"

    local cmd='/usr/local/sbin/php-fpm -R'
    run_cmd "docker run -d $args --name $php_container $php_image $cmd"
}

stop_php() {
    stop_container $php_container
}

function _send_cmd_to_php() {
    local cmd=$1
    run_cmd "docker exec -it $php_container bash -c '$cmd'"
}

function to_php() {
    local cmd='bash'
    _send_cmd_to_php "$cmd"
}



function gen_git(){
    git pull
    git diff-tree -r  --name-status  --no-commit-id ORIG_HEAD HEAD > "$git_log_path/git_tmp.log"
    awk '/M[[:space:]]+9douyu-static/{print $2}' "$git_log_path/git_tmp.log" > "$git_log_path/git_t.log"
    rm "$git_log_path/git_tmp.log"
    mv "$git_log_path/git_t.log" "$git_log_path/git.log"
}

function run(){
    gen_git
    run_php
    local cmd="php $src_path_in_docker/cdn/run.php"
    run_cmd "docker exec -i $php_container bash -c '$cmd'"
    stop_container $php_container
    #rm "$git_log_path/git.log"
}


function help() {
        cat <<-EOF

    Usage: online.sh [options]

            Valid options are:

            deploy

            pull

            run
            run_php
            stop_php
            to_php

            -h                      show this help message and exit

EOF
}

#action=${1:-help}
ALL_COMMANDS="deploy pull run run_php stop_php to_php"
list_contains ALL_COMMANDS "$action" || action=help
$action "$@"
