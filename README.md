# iMakerKit: 物资管理系统

Forked from [withstars/Books-Mangement-System-php](https://github.com/withstars/Books-Mangement-System-php)

参考了框架实现，基本上重写了逻辑，加入了一些必要的功能与安全性提升

---

## 简介
针对西南某高校的创客空间设计的物资管理系统，采用PHP+MySQL+Bootstrap开发。该系统实现了会员刷卡登录（自制刷卡机获取8字节UID）和管理员登录，物资的增删改查，会员的增删改查，预约借还物资，密码修改，超期提醒等的功能。

## 数据库
本系统数据库共有六张数据表。admin为管理员表，item_info为物资信息表，class_info为分类信息表，lend_list为借还信息表，member_card为会员证表,member_info为会员信息表。

## 文件结构
文件中admin开头的为管理员功能，member开头的为会员功能。index.php为登录页面。mysqli_connect.php为数据库连接文件。


## 安装配置
1. 下载本系统压缩包并解压至服务器www目录下。
2. 将sql文件导入数据库。
3. 在本系统mysqli_connect.php文件中配置数据库连接。
4. 在浏览器地址栏中输入你配置的地址即可进入该系统。
5. 演示管理员账号2018141531000，密码111111，演示用户账号aaaaaaaa
6. 如需刷卡，需要接入刷卡器，本项目使用了一块Arduino nano + 一块CJMCU 32u4(虚拟键盘) ，做了一个简单的读卡器获取一卡通uid，当然也可以用别的开发板


## TODO
- 安全性提升
- Docker 部署
- 管理员之间的互相管理
- 接入标签机打印
- 定时任务：借用过期、催还提醒等
- 接入通知bot
- 导出借用记录，便于期末盘点
