<div align=center>
<img src="https://s2.loli.net/2025/02/28/xMY7QVAfa4sIyrJ.jpg" style="width:100px;" width="100"/>
<h2>简小工具</h2>
</div>

### 一、产品概述

- PHP通用简单小工具，简称SSU
- 时间戳、云变量、图片访问、文件下载、版本信息管理、迷你云阶
- 可用于支持PHP的云主机、云服务器、云虚拟机
- 云变量可用于发卡、懒后端、慢消息队列、区块链
- 支持密码访问图片、密码下载文件、版本信息动态更新
- 迷你云阶支持负载均衡、动静态随机、无感更新、静态云变量
- 任何操作均需携带密钥操作，安全防盗用
- 参数错误或操作无效时进行无感返回
- PHP项目可能存在漏洞，公益项目还请手下留情（不要攻击我，可以提漏洞）
- 此项目基于ThinkPHP，环境要求PHP8以上
- 存储最大容量和最大吞吐量可根据自己的设备自行进行调整

### 二、部署流程

- 您可以直接下载release中的压缩包解压到服务器目录
- 执行composer install（下载完整包则可以跳过此步骤）
- 您需要执行根目录下的local_tp.sql到您的数据库来创建表
- 之后您需要去指定文件对项目进行配置
- 之后您可以将public目录映射到域名或公网端口
- 当然您可以通过TP提供的直接运行方式运行

### 二、公钥配置

- 每个功能具有单独公钥，独立配置
- 公钥修改位置在app/common.php

### 三、数据配置

- 数据库应指定为您自己的数据库，项目中所带的无法直接使用
- 数据库配置位于config/database.php
- 若您使用了.env，请注意您的.env配置
- 文件、图片、云阶、版本信息请修改runtime文件夹下对应文件
- 请勿删除example文件，它们同时也是混淆使用的默认信息
- 若需要拓展则需要按照example中提供的格式修改
- 云阶、版本信息等xml信息功能传参无需携带文件后缀名，文件名就是name

### 四、时间戳接口

 > /time

   - 接口说明：获取当前时间

   - 请求方式：get

   - 请求参数
     ```
     key: 公钥字符串
     ```


   - 返回类型：string

   - 返回示例：
     ```
     2025-02-28 20:29:12
     ```


   > /time/timestamp

   - 接口说明：获取当前时间戳
   
   - 请求方式：get
   - 请求参数
     ```
     key: 公钥字符串
     ```


   - 返回类型：string

   - 返回示例：
     ```
     1740745886
     ```

### 五、云变量接口

   > /variable/add

   - 接口说明：添加或覆盖修改云变量
   
   - 请求方式：get
   - 请求参数
     ```
     注意! 所有参数必传
     public_key: 公钥字符串
     private_key: 私钥字符串(默认最长255)
     key: 云变量变量名(默认最长60)
     value: 云变量值(默认最长5000)
     expiration_date: 过期时间(单位毫秒,若为创建则代表创建后多久后过期,若为修改则代表将时间修改为从现在到之后多久过期,若修改时传0则不修改过期时间)
     once: 是否为一次性变量(0为否,1为是,若设置为1则第一次get这个变量时将无视过期时间自动销毁)
     ```


   - 返回类型：string

   - 返回示例：
     ```
     1
     ```

> /variable/get

   - 接口说明：获取云变量
   
   - 请求方式：get
   - 请求参数
     ```
     注意! 所有参数必传
     public_key: 公钥字符串
     private_key: 私钥字符串(默认最长255)
     key: 云变量变量名(默认最长60)
     destory: 是否直接销毁(0为否,1为是,若设置为1则本次获得完变量后直接销毁)
     ```


   - 返回类型：json

   - 返回示例：
     ```
     {"id":13,"t_key":"a","t_value":"888","expiration_date":"2025-02-28 20:55:46","private_key":"777","once":0}
     ```
     
> /variable/clean

   - 接口说明：清理过期云变量（默认执行增删改的时候也会执行）
   - 请求方式：get


   - 返回类型：string

   - 返回示例：
     ```
     1
     ```

### 六、图片访问接口

> /picture/get

   - 接口说明：携带密码访问图片
   - 请求方式：get
- 请求参数
     ```
     注意! 所有参数必传
     key: 图片功能公钥
     name: 图片文件全名(带后缀名,目标文件在runtime/picture文件夹)
     ```
- 返回类型：file

- 返回示例：
     ```
     直接显示图片
     ```

### 七、文件下载接口

> /file/get

   - 接口说明：携带密码下载文件
   - 请求方式：get
- 请求参数
     ```
     注意! 所有参数必传
     key: 文件功能公钥
     name: 文件全名称(带后缀名,目标文件在runtime/file文件夹)
     ```
- 返回类型：file

- 返回示例：
     ```
     直接下载文件
     ```

### 八、版本信息管理接口

> /version/all

   - 接口说明：获取指定产品的版本信息
   - 请求方式：get
- 请求参数
     ```
     注意! 所有参数必传
     key: 版本功能公钥
     name: 要获得的版本信息(不带后缀名,信息存放在runtime/version文件夹)
     ```
- 返回类型：json

- 返回示例：
     ```
     {
         "versionInfo": {
             "name": {
                 "zh-cn": "简单小软件",
                 "en": "SimpleSmallUtil"
             },
             "description": {
                 "zh-cn": "PHP通用简单小工具",
                 "en": "PHP universal simple gadget."
             },
             "icon": "https://s2.loli.net/2025/02/28/xMY7QVAfa4sIyrJ.jpg",
             "lastest": "1.0.20250309",
             "tags": {
                 "tag": [
                     "办公软件",
                     "开发者工具"
                 ]
             },
             "platforms": {
                 "platform": "全平台"
             },
             "architectures": {
                 "architecture": [
                     "x86",
                     "x64"
                 ]
             },
             "extends": {},
             "versions": {
                 "version": [
                     {
                         "number": "1.0.20250228",
                         "releaseDate": "2025-02-28",
                         "features": {
                             "feature": [
                                 "初始版本发布",
                                 "基本功能实现"
                             ]
                         },
                         "Links": {
                             "Link": [
                                 "https://www.mutantcat.org/software/simplesmallutil",
                                 "https://pan.baidu.com/share/init?surl=2DCvhlz5NhQpWiZS0Z3nBA&pwd=nu2w",
                                 "https://shuntaoyuan.lanzout.com/ioCrb2p54hdc",
                                 "https://github.com/Mutantcat-Working-Group/SimpleSmallUtil/releases/tag/v1.0.20250228"
                             ]
                         },
                         "extends": {}
                     },
                     {
                         "number": "1.0.20250309",
                         "releaseDate": "2025-08-09",
                         "features": {
                             "feature": "新增图片、版本、文件、云阶"
                         },
                         "Links": {
                             "Link": "https://www.mutantcat.org/software/simplesmallutil"
                         },
                         "extends": {}
                     }
                 ]
             }
         }
     }
     ```
     
> /version/lastest

   - 接口说明：获取指定产品的最新版本号
   - 请求方式：get
- 请求参数
     ```
     注意! 所有参数必传
     key: 版本功能公钥
     name: 要获得的版本信息(不带后缀名,信息存放在runtime/version文件夹)
     ```
- 返回类型：string

- 返回示例：
     ```
     1.0.20250309
     ```

### 九、迷你云阶接口

> /cloudstep/get

   - 接口说明：以云阶的方式获取数据
   - 请求方式：get
- 请求参数
     ```
     注意! 所有参数必传
     key: 迷你云阶功能公钥
     name: 要获得的云阶名称(不带后缀名,信息存放在runtime/cloudstep文件夹)
     ```
- 返回类型：string

- 返回示例：
     ```
     mutantcat
     ```

### 十、错误代码

- `-2`: 事务执行失败

- `-1`: 参数有误或请求非法

- `0`  : 失败
- `1`  : 成功
