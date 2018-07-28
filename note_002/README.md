# github提交代码却不显示绿格子
在github上提交代码之后,绿格子没有点亮,并且显示No contributions
```
解决方法
打开本地的git bash
输入 git config user.email 查看本地的配置邮箱是否和github上绑定的邮箱一致
如果不一致,要把本地的配置邮箱改成github绑定的邮箱,输入git config --global user.email "xx@xx.com"来配置本地邮箱
再次输入 git config user.email 查看本地的配置邮箱,此时本地的配置邮箱和github绑定的邮箱一致
再次提交文件,此时绿格子就会显示
```