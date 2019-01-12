# Skylark Docker
Docker deployment of [Skylark](https://github.com/GreenNerd/skylark).

## Installation
  - git
  - [docker](https://docs.docker.com/install)
  - [docker-compose](https://docs.docker.com/compose/install)

## 设置 Docker

#### 1、Change docker mirror
add the below json into `/etc/docker/daemon.json`, and run `sudo service docker restart` to restart docker.
```json
  {
    "registry-mirrors": ["https://registry.docker-cn.com"]
  }
```

#### 2、自启动
[文档](https://docs.docker.com/install/linux/linux-postinstall/#configure-docker-to-start-on-boot)
`sudo systemctl enable docker`


## Start Deploying

#### 1、Clone repo
Create a `/var/skylark` folder, clone the Official Skylark Docker Image into it:

```bash
mkdir /var/skylark
git clone https://github.com/GreenNerd/skylark-docker.git /var/skylark
cd /var/skylark
```

#### 2、Configuration
- `touch app.local.env`
- 根据`app.default.env`，在`app.local.env`文件里配置相关的设置
- 在`.env`文件里设置部署版本号，例：`SLP_VERSION=2.10.4-76802f2`

#### 3、Login aliyun docker repo
```bash
./scripts/login
```

#### 4、Initialization
`./scripts/install`，it will create db & precompile assets...


#### 5、Start server
`./scripts/start`

#### 6、创建一个`Namespace`
- `docker-compose exec app bundle exec rails console`，进入Rails console
- `Namespace.create name: '空间名字'`


#### 7、Logrotate
`./scripts/logrotate`，日志切割，旧日志存放在`./old_log`，可以把`old_log`软连接到另一个磁盘
