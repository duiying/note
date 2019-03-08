# shell

### shell特性
> 第一个shell   

vim ping.sh
```
# Feat: ping baidu.com, 如果ping成功执行&&之后的语句, 如果ping失败执行||之后的语句
# Note: -c1表示ping一次; &> /dev/null表示把标准输出和标准错误输出重定向到/dev/null;
ping -c1 baidu.com &> /dev/null && echo "baidu.com is up" || echo "baidu.com is down"
```
如何执行
```
# 使用bash命令执行
bash ping.sh
# 使用sh命令执行
sh ping.sh
# 直接执行(需要加可执行权限)
chmod +x ping.sh
./ping.sh
```
指定解释器为/usr/bin/bash
```
#!/usr/bin/bash
ping -c1 baidu.com &> /dev/null && echo "baidu.com is up" || echo "baidu.com is down"
```
> 如何在解释器为/usr/bin/bash的脚本中嵌套python脚本  

首先查看python命令位置
```
[root@10-9-50-240 scripts]# which python
/usr/bin/python
```
<<EOF不支持tab缩进(比如最后的EOF只能顶头), <<-EOF支持tab缩进(比如最后的EOF可以tab缩进)
```
#!/usr/bin/bash
/usr/bin/python <<-EOF
print "hello"
print "python"
EOF
echo "hello bash"
```
EOF也可以换成其他任意字母
```
#!/usr/bin/bash
/usr/bin/python <<@
print "hello"
print "python"
@
echo "hello bash"
```
> 在当前shell执行和sub shell执行的区别  
 
vim ping.sh
```
#!/usr/bin/bash
cd /home
ls
```
```
# .和source方式执行是在当前shell执行(目录切换到了/home)
[root@10-9-50-240 scripts]# . ping.sh 
[root@10-9-50-240 home]#
[root@10-9-50-240 scripts]# source ping.sh 
[root@10-9-50-240 home]#

# bash和./方式执行是在sub shell执行(目录不会切换)
```