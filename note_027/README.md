# ssh命令登录远程主机
```
# ssh [-l login_name] [-p port] [user@]hostname
ssh root@106.75.117.140
```
如果报错如下
```
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@    WARNING: REMOTE HOST IDENTIFICATION HAS CHANGED!     @
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
IT IS POSSIBLE THAT SOMEONE IS DOING SOMETHING NASTY!
Someone could be eavesdropping on you right now (man-in-the-middle attack)!
It is also possible that a host key has just been changed.
The fingerprint for the RSA key sent by the remote host is
SHA256:I5IhvWPVw6DBSYKXQQYC0uJ8K1/KJtEjQ1PtQi11UKU.
Please contact your system administrator.
Add correct host key in /Users/wyx/.ssh/known_hosts to get rid of this message.
Offending ECDSA key in /Users/wyx/.ssh/known_hosts:4
RSA host key for 106.75.117.140 has changed and you have requested strict checking.
Host key verification failed.
```
解决方案是
```
vim /Users/hoolai/.ssh/known_hosts
删除对应的远程地址信息行
```