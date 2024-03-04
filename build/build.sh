#! /usr/bin/bash
if [ `id -u` = 0]; then
    echo "Please run this script with sudo permissions! This script can not run without it.";
    exit 1;

sudo apt update && sudo apt upgrade -y

# changing permissions for the settings file
chmod 777 ../src/configuration/settings.ini

# checking if docker is installed
docker -v
if [ $? -ne 0 ]; then
    echo "Docker is not currently installed on your system! Please install docker to continue.";
    exit 1;
fi

# running the compose script
docker compose up -d
docker ps

echo "Everything is up and running"
exit 0