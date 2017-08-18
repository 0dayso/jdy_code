#!/bin/bash
set -e
prj_path=$(cd $(dirname $0); pwd -P)
devops_prj_path="$prj_path/devops"
docker_file_path="$devops_prj_path/docker"
load_init_module=1


app_fe_image=sunfund/9douyu-fe-new
app_fe_container=9douyu-gulp

function init() {
    local fe_dir='.'
    if [ -d "$fe_dir/node_modules" ]; then
        run_cmd "rm $fe_dir/node_modules"
    fi
    run_cmd "ln -sf /opt/node_npm_data/node_modules $fe_dir/"
}

function init_config_by_developer_name() {
    #echo "This is the $developer_name";
    return;
}

function do_init_for_dev() {
    app_fe_container="$developer_name-$app_fe_container"
    #echo $app_fe_container;
}

source $devops_prj_path/base.sh


function build_fe() {
    docker build -t $app_fe_image $docker_file_path
}

function push_fe() {
    push_image $app_fe_image
}

function pull_fe() {
    pull_image $app_fe_image
}

function run_fe_container() {
    source $manager_config_file
    if [ -z "$developer_name" ]; then
        echo 'Config file is not found, please call `sh manager.sh init_dev developer_name` first.'
        exit 1
    fi
    init
    cmd=$1
    local path='/opt/app'
    args=''
    args="$args -v $prj_path:$path"
    args="$args -w $path"
    run_cmd "docker run -it $args --rm --name $app_fe_container $app_fe_image bash -c '$cmd'"
}

function stop_gulp() {
    stop_container $app_fe_container
}

function to_gulp() {
    run_fe_container '/bin/bash'
}

function run_gulp() {
    stop_gulp
    #run_fe_container 'npm run dev'
    run_fe_container 'npm run prod'
}


function help() {
	cat <<-EOF
    
    Usage: manager [options]

	    Valid options are:


            init_dev

            build_fe
            push_fe
            pull_fe

            run_gulp
            to_gulp
            stop_gulp

            -h                      show this help message and exit

EOF
}

action=${1:-help}
ALL_COMMANDS="init_dev build_fe push_fe pull_fe run_gulp to_gulp stop_gulp"
list_contains ALL_COMMANDS "$action" || action=help
$action "$@"
