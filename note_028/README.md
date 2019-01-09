# Git多分支协作
### 情景描述
```
远程Project现存在分支: master
master是项目上线分支

要创建testing分支作为项目测试分支
新人wangyaxian要创建自己的分支feature-wangyaxian进行开发
不同分支之间该如何协作?
```

### 准备
```
目录结构
* master
    README.md
    
分支结构
* master

两个用户, 一个macUser(管理testing分支), 一个linuxUser(管理feature-wangyaxian分支)
```

### macUser
```
* 克隆master分支到本地
    git clone git@github.com:duiying/demo.git
* 新建testing分支并切换到testing分支
    git checkout -b testing
* 新建文件并写入内容
    echo testing > testing.txt
* 添加和提交
    git add testing.txt
    git commit -m "feat:testing init"
* 将当前testing分支推送到远程
    git push origin testing
```
### 远程Project状态
```
此时远程Project下有两个分支: master和testing
* master
    README.md
* testing 
    README.md
    testing.txt
    
testing.txt内容:
testing
```
### linuxUser
```
* 克隆master分支到本地
    git clone git@github.com:duiying/demo.git
* 新建feature-wangyaxian分支并切换到feature-wangyaxian分支
    git checkout -b feature-wangyaxian
* 从testing分支拉下最新代码
    git pull origin testing
* 进行代码开发
    echo wangyaxian >> testing.txt
* 添加和提交
    git add testing.txt
    git commit -m "feat:追加wangyaxian到testing.txt"
* 将当前feature-wangyaxian分支推送到远程
    git push origin feature-wangyaxian
```
### 远程Project状态
```
此时远程Project下有三个分支: master/testing/feature-wangyaxian
* master
    README.md
* testing
    README.md
    testing.txt
    (
    testing.txt内容:
    testing
    )

* feature-wangyaxian
    README.md
    testing.txt
    (
    testing.txt的内容:
    testing
    wangyaxian
    )
```
### 解决代码冲突问题
```
此时如果macUser在testing远程分支上推送了代码
比如testing分支上testing.txt内容变为如下
testing 
testing111

那么, wangyaxian-feature分支要merge到testing分支时, 为了避免覆盖testing分支别人提交的代码
应该, 先 pull testing分支的代码
git pull origin testing

会提示,
[root@10-9-50-240 demo]# git pull origin testing
remote: Enumerating objects: 5, done.
remote: Counting objects: 100% (5/5), done.
remote: Compressing objects: 100% (2/2), done.
remote: Total 3 (delta 0), reused 3 (delta 0), pack-reused 0
Unpacking objects: 100% (3/3), done.
来自 github.com:duiying/demo
 * branch            testing    -> FETCH_HEAD
自动合并 testing.txt
冲突（内容）：合并冲突于 testing.txt
自动合并失败，修正冲突然后提交修正的结果

查看一下冲突文件的内容
[root@10-9-50-240 demo]# cat testing.txt 
testing
<<<<<<< HEAD
wangyaxian
=======
testing111
>>>>>>> 65ae1d2d7dc019ecf0b29e791153155291f31fe3

删除  
<<<<<<< HEAD
=======
>>>>>>> 65ae1d2d7dc019ecf0b29e791153155291f31fe3

此时冲突已经解决, feature-wangyaxian分支 add commit push 到远程
然后, feature-wangyaxian分支merge到testing分支上, 这时, 就不用担心把别人辛辛苦苦写好的代码给覆盖掉了
```