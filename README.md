<div align=center>
<img src="https://s2.loli.net/2025/02/28/xMY7QVAfa4sIyrJ.jpg" style="width:100px;" width="100"/>
<h2>简小工具</h2>
</div>

### 一、产品概述

- PHP 通用简单小工具（SSU），一套跑在 PHP 云主机上的轻量后端服务。
- 内置时间戳、云变量、图片访问、文件下载、版本信息管理、迷你云阶六大能力。
- 云变量可用于发卡、懒后端、慢消息队列、区块链等场景；迷你云阶支持负载均衡、动静态随机、无感更新、静态云变量。
- 任何操作均需携带密钥，参数错误或操作无效时无感返回，安全防盗用。
- 基于 ThinkPHP，环境要求 PHP 8 以上，存储容量与吞吐量可随设备自行调整。

核心价值：一个 PHP 空间就能拥有「时间、变量、文件、图片、版本、分流」六件套，写小后端不必再搭全套服务。

### 二、功能说明

- 时间戳：提供格式化当前时间与 Unix 时间戳。
- 云变量：带过期时间、一次性变量的键值存储，支持动态增删改查与自动清理。
- 图片访问：携带密码访问 `runtime/picture` 下的图片。
- 文件下载：携带密码下载 `runtime/file` 下的文件。
- 版本信息管理：XML 描述产品版本信息，供客户端动态查询最新版本。
- 迷你云阶：五种调度模式（random / hash / static / static_cloud_var）的数据分发，详见接口章节。

### 三、安装与下载

从 [Releases](https://github.com/Mutantcat-Working-Group/SimpleSmallUtil/releases) 下载 `simplesmallutil-1.0.20260920.tar.gz`（另附 `checksums.txt` 供校验），按以下流程部署到支持 PHP 的云主机、云服务器或云虚拟机：

1. 解压到服务器目录（下载完整包可跳过 `composer install`）。
2. 执行 `composer install`（如未打完整包）。
3. 执行根目录下的 `local_tp.sql` 创建数据表。
4. 修改配置：公钥在 `app/common.php`（每个功能独立配置），数据库在 `config/database.php`，文件、图片、云阶、版本信息在 `runtime` 文件夹下对应文件中。
+ 注意：请勿删除 `example` 文件，它们同时是混淆用的默认信息；拓展时按 `example` 提供的格式修改。
5. 将 `public` 目录映射到域名或公网端口（也可使用 TP 提供的直接运行方式）。

版本号使用纯日期递增（如 `1.0.20260920`），推送同族标签（`v` 前缀可选）后，GitHub Actions 会自动打包并发布 Release。

### 四、快速上手

1. 部署完成后，先用浏览器访问 `/time` 并带上任一功能公钥，返回当前时间即服务可用。
2. 在 `runtime/version` 下按 `example` 格式放入产品的版本信息 XML。
3. 调用 `/version/lastest` 让客户端拿到最新版本号，实现动态更新检测。
4. 用 `/variable/add` 写入一个云变量，再用 `/variable/get` 读回，验证持久化。
5. 把图片或文件放进 `runtime/picture`、`runtime/file`，用对应接口带密码取用。

### 五、接口说明

所有接口请求均需携带对应功能的公钥（`key`），以下路径相对于 `public` 目录。

#### 时间戳接口

> `/time`

- 接口说明：获取当前时间
- 请求方式：get
- 请求参数：`key`（公钥字符串）
- 返回类型：string
- 返回示例：`2025-02-28 20:29:12`

> `/time/timestamp`

- 接口说明：获取当前时间戳
- 请求方式：get
- 请求参数：`key`（公钥字符串）
- 返回类型：string
- 返回示例：`1740745886`

#### 云变量接口

> `/variable/add`

- 接口说明：添加或覆盖修改云变量
- 请求方式：get
- 请求参数（所有参数必传）：

```text
public_key: 公钥字符串
private_key: 私钥字符串(默认最长255)
key: 云变量变量名(默认最长60)
value: 云变量值(默认最长5000)
expiration_date: 过期时间(单位毫秒,创建时代表多久后过期,修改时代表从现在到之后多久过期,传0则不修改)
once: 是否为一次性变量(0为否,1为是,设为1则第一次get时无视过期时间自动销毁)
```

- 返回类型：string
- 返回示例：`1`

> `/variable/get`

- 接口说明：获取云变量
- 请求方式：get
- 请求参数（所有参数必传）：

```text
public_key: 公钥字符串
private_key: 私钥字符串
key: 云变量变量名(默认最长60)
destory: 是否直接销毁(0为否,1为是,设为1则本次获得后直接销毁)
```

- 返回类型：json
- 返回示例：

```json
{"id":13,"t_key":"a","t_value":"888","expiration_date":"2025-02-28 20:55:46","private_key":"777","once":0}
```

> `/variable/clean`

- 接口说明：清理过期云变量（增删改时默认也会执行）
- 请求方式：get
- 返回类型：string
- 返回示例：`1`

#### 图片访问接口

> `/picture/get`

- 接口说明：携带密码访问图片
- 请求方式：get
- 请求参数（所有参数必传）：`key`（图片功能公钥）、`name`（图片文件全名，带后缀名，目标文件在 `runtime/picture` 文件夹）
- 返回类型：file
- 返回示例：直接显示图片

#### 文件下载接口

> `/file/get`

- 接口说明：携带密码下载文件
- 请求方式：get
- 请求参数（所有参数必传）：`key`（文件功能公钥）、`name`（文件全名，带后缀名，目标文件在 `runtime/file` 文件夹）
- 返回类型：file
- 返回示例：直接下载文件

#### 版本信息管理接口

> `/version/all`

- 接口说明：获取指定产品的版本信息
- 请求方式：get
- 请求参数（所有参数必传）：`key`（版本功能公钥）、`name`（版本信息名，不带后缀名，信息存放在 `runtime/version` 文件夹）
- 返回类型：json
- 返回示例（节选）：

```json
{
    "versionInfo": {
        "name": { "zh-cn": "简单小软件", "en": "SimpleSmallUtil" },
        "lastest": "1.0.20260803",
        "versions": {
            "version": [
                {
                    "number": "1.0.20250228",
                    "releaseDate": "2025-02-28",
                    "features": { "feature": ["初始版本发布", "基本功能实现"] },
                    "Links": { "Link": ["https://www.mutantcat.org/software/simplesmallutil"] }
                }
            ]
        }
    }
}
```

> `/version/lastest`

- 接口说明：获取指定产品的最新版本号
- 请求方式：get
- 请求参数（所有参数必传）：`key`（版本功能公钥）、`name`（版本信息名，不带后缀名）
- 返回类型：string
- 返回示例：`1.0.20260803`

#### 迷你云阶接口

> `/cloudstep/get`

- 接口说明：以云阶的方式获取数据，支持多种调度模式
- 请求方式：get
- 请求参数（所有参数必传）：`key`（迷你云阶功能公钥）、`name`（云阶名称，不带后缀名，信息存放在 `runtime/cloudstep` 文件夹）
- 返回类型：string
- 返回示例：`https://www.mutantcat.org/`

云阶 XML 通过 `<mode>` 字段选择调度模式，共五种：

| mode | 说明 | 典型用途 |
| --- | --- | --- |
| `random` | 每次请求随机返回一个 target | 动静态随机、简单负载均衡 |
| `hash` | 基于客户端 IP（或请求 key 参数）的一致性哈希，同一客户端始终命中同一 target | 负载均衡、会话保持、无感更新 |
| `static` | 直接返回 XML 中 `<value>` 的固定值 | 静态云变量（最简单的形式） |
| `static_cloud_var` | 从云变量（temp_value 表）中取值返回，值通过 /variable/add 管理 | 静态云变量（动态管理） |

random 模式示例：

```xml
<?xml version="1.0" encoding="UTF-8"?>
<cloudstep>
    <mode>random</mode>
    <targets>
        <target>https://www.mutantcat.org/</target>
        <target>https://www.mutantcat.top/</target>
        <target>https://github.com/Mutantcat-Working-Group</target>
    </targets>
</cloudstep>
```

hash 模式示例（负载均衡 + 无感更新）：

```xml
<?xml version="1.0" encoding="UTF-8"?>
<cloudstep>
    <mode>hash</mode>
    <targets>
        <target>https://node1.mutantcat.org/</target>
        <target>https://node2.mutantcat.org/</target>
        <target>https://node3.mutantcat.org/</target>
    </targets>
</cloudstep>
```

> 相同客户端（同 IP，或请求携带相同 `key` 参数）始终命中同一台 target；增减 target 时只有少量客户端迁移，实现无感更新。

static 模式示例（静态）：

```xml
<?xml version="1.0" encoding="UTF-8"?>
<cloudstep>
    <mode>static</mode>
    <value>https://www.mutantcat.org/static-resource</value>
</cloudstep>
```

static_cloud_var 模式示例（静态云变量，值通过云变量接口管理）：

```xml
<?xml version="1.0" encoding="UTF-8"?>
<cloudstep>
    <mode>static_cloud_var</mode>
    <cloudvar>
        <private_key>my_private_key</private_key>
        <key>my_var</key>
    </cloudvar>
</cloudstep>
```

> 返回值为 `temp_value` 表中 `t_key=my_var` 且 `private_key=my_private_key` 的记录值；可通过 `/variable/add` 动态更新该值，云阶侧无需改动。

### 六、错误代码

- `-2`：事务执行失败
- `-1`：参数有误或请求非法
- `0`：失败
- `1`：成功

### 七、其他说明

- 数据库应指定为您自己的数据库，项目中所带的无法直接使用；若使用 `.env`，请注意 `.env` 配置。
- 云阶、版本信息等 XML 功能传参无需携带文件后缀名，文件名就是 `name`。
- PHP 项目可能存在漏洞，公益项目还请手下留情（不要攻击我，可以提漏洞）。

本项目基于 Apache-2.0 协议开源。
- 注意：请勿删除 `example` 文件，它们同时是混淆用的默认信息；拓展时按 `example` 提供的格式修改。
    - 注意：请勿删除 `example` 文件，它们同时是混淆用的默认信息；拓展时按 `example` 提供的格式修改。
