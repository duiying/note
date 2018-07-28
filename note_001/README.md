# 如何提升github的访问速度
```
修改hosts文件(C:\Windows\System32\drivers\etc)
在最后添加两行
151.101.72.249 http://global-ssl.fastly.Net
192.30.253.112 http://github.com
然后刷新DNS解析缓存,在cmd下执行ipconfig/flushdns
```