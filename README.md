# Skylark Docker
Docker deployment of [Skylark](https://skylarkly.com).

# Installation

## Install Docker / Git
```bash
wget -qO- https://get.docker.com/ | sh
```
This command installs the latest versions of Docker and Git on your server. Alternately, you can manually [install Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git) and the [Docker package for your OS](https://docs.docker.com/installation/).

Change docker mirror to docker-cn, add the below json into `/etc/docker/daemon.json`, and run `sudo service docker restart` to restart docker.
```json
{
  "registry-mirrors": ["https://registry.docker-cn.com"]
}
```

## Install Docker Compose
```bash
curl -L https://github.com/docker/compose/releases/download/1.16.1/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
```

Alternately, download manually at https://github.com/docker/compose/releases.

## Install Skylark

Create a /var/skylark folder, clone the Official Skylark Docker Image into it:

```bash
mkdir /var/skylark
git clone https://github.com/GreenNerd/skylark-docker.git /var/skylark
cd /var/skylark
```

Build it manually
```bash
cd /var/skylark/images/base && ./build
cd /var/skylark/images/production && ./build
```

Run!

```bash
cd /var/skylark
docker-compose up -d
```

Before run you might run below:
```bash
cd /var/skylark
./script/install # First install, precompile assets
./script/start # Run server
./script/update # Update container
```

## RabbitMQ
Some extra works need to be done for RabbitMQ.Follow steps below:

1. First run installation scipt `./scripts/install`
2. Start up RabbitMQ service by `docker-compose run rabbitmq` and keep it alive
3. Get ID of the container by `docker ps | grep rabbitmq`
4. Set up a new user by `docker exec -it ID_of_container_got_above rabbitmqctl add_user username password`
5. Set permissions for the user by `docker exec -it ID_of_container_got_above rabbitmqctl set_permissions -p / username '.*' '.*' '.*'`
