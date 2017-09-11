# Skylark Docker
Docker deployment of [Skylark](https://skylarkly.com).

# Installation

## Install Docker & Git
```bash
wget -qO- https://get.docker.com/ | sh
```
This command installs the latest versions of Docker and Git on your server. Alternately, you can manually [install Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git) and the [Docker package for your OS](https://docs.docker.com/installation/).

## Install Docker Compose
```bash
curl -L https://github.com/docker/compose/releases/download/1.16.1/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
```

Alternately, download manually at https://github.com/docker/compose/releases.

## Install Skylark

1. Create a /var/skylark folder, clone the official Skylark Docker repository into it:

```bash
mkdir /var/skylark
git clone https://github.com/GreenNerd/skylark-docker.git /var/skylark
cd /var/skylark
```

2. Build it manually
```bash
cd /var/skylark/images/base && ./build
cd /var/skylark/images/production && ./build
```

3. Copy the env file and fill out it
```bash
cd /var/skylark
sudo -s
cp app.default.env app.local.env
# just fill out app.local.env with proper env parameters
```

4. Set up database and precompile assets
```bash
cd /var/skylark
./scripts/install
sudo -s
docker-compose up -d
```

5. Run Skylark!
```bash
cd /var/skylark
sudo -s
docker-compose up -d
```
or
```bash
./scripts/start
```

6. Update Skylark
```bash
./script/update
```
