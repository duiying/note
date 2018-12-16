# JS脚本模拟阿里程序员抢月饼
### 效果预览
![qiangyuebing](https://raw.githubusercontent.com/duiying/note/master/img/qiangyuebing.gif)

### 准备抢月饼活动页面 index.html
```
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>抢月饼活动页面</title>
</head>
<body>
	<center>
		<div> 
			<p>
				<span id="timeMsg">活动倒计时</span>
				<strong id="endtime"></strong>
			</p> 
			<input type="button" id="btn" value="立即购买" disabled="disabled" onClick="buy()"/>
		</div>
	</center>
</body>

<script>
	// 定时倒计时时间为8s
	var i = 8 + 1;

	// 倒计时函数
	function remainTime() {
		if (i > 0) { 
			document.getElementById("endtime").innerHTML = --i; 
			setTimeout("remainTime()", 1000); 
		}

		if (i == 0) {
			document.getElementById("timeMsg").innerHTML = '活动开始'; 
			document.getElementById("endtime").innerHTML = ''; 
			document.getElementById("btn").disabled = false; 
		}

	}

	// 执行倒计时
	remainTime();

	// 购买函数
	function buy() {
		alert('抢到一个月饼');
	}
</script>

</html>
```
### 准备JS脚本, 内容如下
```
console.log('auto script running...');

// 定时入口
var interval = setInterval(function(){
	timingHandle();
}, 1000);

// 定时事件
function timingHandle() {
	// 目标DOM
	var targetDom = document.getElementById('btn');
	// 监听按钮的状态
	if (targetDom.disabled == false) {
        targetDom.click();
        // 停止定时
        clearInterval(interval);
    }
}
```
### 如何执行请参考GIF