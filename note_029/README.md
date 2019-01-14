# error集合

**Fatal error: Allowed memory size of 1610612736 bytes exhausted (tried to allocate 67108864 bytes)**
```
原因: 
PHP分配内存不足     
解决: 
修改php.ini, 默认值为 memory_limit = 128M , 修改为 memory_limit = 4096M
```