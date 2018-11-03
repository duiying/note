# Git
### 安装与配置
Win
```
https://git-scm.com/download/win
```
Linux
```
yum -y install git
```
配置用户名和邮箱
```
* --global 参数表示所有的Git仓库都会使用这个配置
git config --global user.name "Your Name"
git config --global user.email "email@example.com"
```

### 常用命令
创建仓库
```
# 选择一个目录
$ pwd
/c/test

# 把普通目录初始化为Git仓库
$ git init
Initialized empty Git repository in C:/test/.git/

# 初始化为Git仓库后, 当前目录下多了一个 .git 的目录
$ ls -a
./  ../  .git/
```
查看当前工作区和暂存区文件的状态
```
git status
```
把文件修改添加到暂存区
```
git add
```
把暂存区所有内容提交到当前分支
```
git commit -m "说明信息"
```
撤销修改
```
场景1: 当你改乱了工作区某个文件的内容,想直接丢弃工作区的修改时,用命令 git checkout -- file

场景2: 当你不但改乱了工作区某个文件的内容,还添加到了暂存区时,想丢弃修改,分两步,第一步用命令 git reset HEAD <file> ,就回到了场景1,第二步按场景1操作
注意: 场景2中 file 参数是可选,如果不指定该参数则表示暂存区的所有文件

场景3: 已经提交了不合适的修改到版本库时,想要撤销本次提交,不过前提是没有推送到远程库
# 首先使用 git log 命令显示出从最近到最远的提交日志
$ git log
commit dd1974ab5db41736ba7a2171318bc89236427197 (HEAD -> master)
Author: wyx <1822581649@qq.com>
Date:   Thu Nov 1 11:29:11 2018 +0800

    second commit

commit f31ceb839b2d4f51b248ef0f8cb2ff06ef13e610
Author: wyx <1822581649@qq.com>
Date:   Thu Nov 1 11:18:49 2018 +0800

    first init

# 然后使用 git reset --hard commitID 回到指定版本
$ git reset --hard f31ceb839b2d4f51b248ef0f8cb2ff06ef13e610

# 此时使用 git log 发现提交日志没有 second commit ,如何回到 second commit 这个版本呢
$ git log
commit f31ceb839b2d4f51b248ef0f8cb2ff06ef13e610 (HEAD -> master)
Author: wyx <1822581649@qq.com>
Date:   Thu Nov 1 11:18:49 2018 +0800

    first init
    
# Git提供了一个命令 git reflog 用来记录你的每一次命令
$ git reflog
f31ceb8 (HEAD -> master) HEAD@{0}: reset: moving to f31ceb839b2d4f51b248ef0f8cb2ff06ef13e610
dd1974a HEAD@{1}: reset: moving to dd1974a
f31ceb8 (HEAD -> master) HEAD@{2}: reset: moving to f31ceb839b2d4f51b248ef0f8cb2ff06ef13e610
dd1974a HEAD@{3}: commit: second commit
f31ceb8 (HEAD -> master) HEAD@{4}: reset: moving to HEAD
f31ceb8 (HEAD -> master) HEAD@{5}: reset: moving to HEAD
f31ceb8 (HEAD -> master) HEAD@{6}: reset: moving to HEAD
f31ceb8 (HEAD -> master) HEAD@{7}: commit (initial): first init

# 可以找到 second commit 的commitID,然后回到该版本
$ git reset --hard dd1974a
HEAD is now at dd1974a second commit
```

### 生成 SSH KEY
```
# -t 参数表示秘钥类型是rsa, -C参数提供一个用来识别秘钥的注释(不一定填邮箱,可以是任何内容,邮箱只是识别用的key)
ssh-keygen -t rsa -C "1822581649@qq.com"

# 根据命令提示查看公钥内容并复制(公钥一般保存在 当前用户家目录/.ssh/id_rsa.pub文件)
# Your public key has been saved in /root/.ssh/id_rsa.pub
cat /root/.ssh/id_rsa.pub 

# 访问 Github -> Settings -> SSH and GPG keys -> New SSH key -> 执行添加 
```
### 克隆仓库
```
# 查看当前所在目录
[root@VM_12_22_centos git]# pwd
/git

# 克隆
[root@VM_12_22_centos git]# git clone https://github.com/duiying/demo.git

# 克隆完成之后, 当前目录下会新增一个目录
[root@VM_12_22_centos git]# ls
demo

# 新增文件并推送到远程
[root@VM_12_22_centos demo]# echo "hello" >> hello.txt
[root@VM_12_22_centos demo]# git add hello.txt 
[root@VM_12_22_centos demo]# git commit -m "add a file"
# git push 远程主机名 本地分支名:远程分支名
[root@VM_12_22_centos demo]# git push origin master:master
```
### 标签管理
```
# 打一个新标签 git tag <name>
[root@VM_12_22_centos demo]# git tag v1.0

# 查看所有标签 git tag
[root@VM_12_22_centos demo]# git tag
v1.0

# 推送某个标签到远程 git push origin <tagname>
[root@VM_12_22_centos demo]# git push origin v1.0

# 打一个新标签并指定标签信息 git tag -a <tagname> -m "comment"
[root@VM_12_22_centos demo]# git tag -a v1.1 -m "second tag comment"

# 查看标签信息 git show <tagname>
[root@VM_12_22_centos demo]# git show v1.1

# 删除标签
# 先删除本地标签 git tag -d <tagname>
[root@VM_12_22_centos demo]# git tag -d v1.0
# 再删除远程标签 git push origin :refs/tags/<tagname>
[root@VM_12_22_centos demo]# git push origin :refs/tags/v1.0
```
### 分支管理
```
# 首先,创建dev分支并切换到dev分支
# -b参数表示创建并切换, 相当于 git branch dev 和 git checkout dev 两条命令
[root@VM_12_22_centos demo]# git checkout -b dev

# 然后用 git branch 命令查看当前分支,当前分支前面会有 * 号
[root@VM_12_22_centos demo]# git branch
* dev
  master
  
# 然后再dev分支上创建文件并提交
[root@VM_12_22_centos demo]# echo branch >> branch.txt
[root@VM_12_22_centos demo]# git add branch.txt 
[root@VM_12_22_centos demo]# git commit -m "branch"

# dev分支的工作完成,切换到master分支
[root@VM_12_22_centos demo]# git checkout master

# 把dev分支的工作合并到master分支上
[root@VM_12_22_centos demo]# git merge dev
Updating 5bd82bd..2f2ea87
Fast-forward
 branch.txt | 1 +
 1 file changed, 1 insertion(+)
 create mode 100644 branch.txt
# 上面的 Fast-forward 信息告诉我们这次合并是"快进模式",也就是直接把master指向dev的当前提交,所以合并速度非常快

# 合并完成后,可以放心地删除dev分支
[root@VM_12_22_centos demo]# git branch -d dev

# 删除后,查看当前分支,只剩下master分支
[root@VM_12_22_centos demo]# git branch
* master
```
分支命令总结
```
查看分支：git branch
创建分支：git branch <name>
切换分支：git checkout <name>
创建+切换分支：git checkout -b <name>
合并某分支到当前分支：git merge <name>
删除分支：git branch -d <name>
```

