sudo apt update && sudo apt upgrade -y

# moving files into the directory for nginx
mkdir ../www/html/
cp -a ../src/. ../www/html/

# install docker
# sudo apt install apt-transport-https ca-certificates curl software-properties-common
# curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
# sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu focal stable"
# apt-cache policy docker-ce
# sudo apt install docker-ce

# check docker status
sudo systemctl status docker

# running the compose script
docker compose up -d
docker ps

echo "Everything is up and running"