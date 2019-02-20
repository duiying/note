# Gitlab Developer和Leader的协作流程

### 创建开发人员账号
![New User](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-new-user.png)  
![Account](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-account.png)  
其他的默认, 然后点击Create user  
**把dev账户加入到test工程**  
![test](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-test.png)  
![access](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-access.png)  
![members](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-members.png)  
### 创建Leader账号
![leader](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-leader.png)  
**把leader账户加入到test工程**  
![leader-members](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-leader-members.png)  
### 更改dev和leader账户的密码
![gitlab-edit](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-edit.png)  
![gitlab-password](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-password.png)  

### 开发人员进行代码编写并提交
```
# 克隆
[root@localhost ~]# git -c http.sslverify=false clone https://gitlab.example.com/root/test.git
Cloning into 'test'...
Username for 'https://gitlab.example.com': dev
Password for 'https://dev@gitlab.example.com':

# 进入仓库
[root@localhost ~]# cd test/
[root@localhost test]# ll
total 4
-rw-r--r-- 1 root root 6 Feb 21 05:02 1.txt

# 创建新分支
[root@localhost test]# git checkout -b feature-wangyaxian-post

# 代码编辑
[root@localhost test]# echo dev >> 1.txt

# 设置用户名和邮箱
[root@localhost test]# git config user.name "dev"
[root@localhost test]# git config user.email "dev@example.com"

# 代码推送
[root@localhost test]# git add 1.txt
[root@localhost test]# git commit -m "dev first commit"
[root@localhost test]# git -c http.sslverify=false push origin feature-wangyaxian-post
```
此时, test仓库下多了一个分支  
![gitlab-branch](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-branch.png)  

### 开发人员提交合并申请  
使用dev 12345678登录Gitlab, 登录之后提示设置新密码  
![gitlab-new-password](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-new-password.png)    
进入到test仓库, 提交合并申请  
![gitlab-merge-request](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-merge-request.png)    
![gitlab-submit-merge](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-submit-merge.png)    

### Leader处理开发人员提交的合并申请
使用leader 12345678登录Gitlab, 登录之后提示设置新密码87654321  

  






