# TCP三次握手和四次挥手
### 基础概念
```
TCP: 面向连接的/可靠的传输层通信协议(Transmission Control Protocal 传输控制协议)
6种TCP标志位(位码): 
    SYN: synchronization, 置1表示建立连接
    ACK: acknowledgement, 置1表示确认确认序号有效
    FIN: finish, 置1表示数据发送完毕, 希望释放连接
    RST: reset, 置1表示出现严重错误, 必须释放连接, 然后再重新建立
    PSH: push, 传送
    URG: urgent, 紧急
Sequence number: 序列号
Acknowledgement number: 确认号
```

### 三次握手
![三次握手](https://raw.githubusercontent.com/duiying/img/master/三次握手.png)  
```
# 第一次握手
客户端将SYN位置1, 随机生成int数值sequence number(假设为200), 将数据包发送给服务端
客户端进入SYN_SENT状态, 等待服务端确认

# 第二次握手
服务端收到数据包后根据标志位SNY=1得知客户端请求建立连接, 设置acknowledgement number为sequence number加1(即201)
服务端随机生成int数值sequence number(假设为500), 服务端将SYN和ACK位都置1
服务端将数据包发送给客户端, 服务端进入SYN_RCVD状态, 等待客户端确认

# 第三次握手
客户端收到数据包后需要检查(1)ACK标志位是否为1 (2)acknowledgement number是否正确(即第一次发送的sequence number加1)
客户端检查完成后发送 标志位ACK=1, sequence number=第二次握手的acknowledgement number, acknowledgement number=第二次握手的sequence number+1的数据包给服务端
客户端进入ESTABLISHED状态
服务端收到数据包后需要检查(1)ACK标志位是否为1 (2)acknowledgement number是否正确(即第二次发送的sequence number加1)
服务端检查完成后进入ESTABLISHED状态

完成三次握手后, 客户端和服务端开始进行传送数据
```