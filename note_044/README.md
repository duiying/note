# Vue基础

### hello world
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>hello</title>
    <script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
</head>
<body>
    <!-- app 包含的区域, 代表 View -->
    <div id="app">
        <p>{{ msg }}</p>
    </div>
</body>
<script>
    // vm 代表 ViewModel
    var vm = new Vue({
        el: '#app',
        // data 代表 Model
        data: {
            msg: 'hello'
        }
    });
</script>
</html>
```
### vue内置指令
#### v-cloak
普通渲染会产生插值闪烁, 比如下面代码, 页面先显示 {{ msg }} , 等vue.js加载完成之后再显示 hello , 产生一个插值闪烁的效果
```
<div id="app">
    <p>{{ msg }}</p>
</div>
<script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            msg: 'hello'
        }
    });
</script>
```
用 v-cloak 和 CSS规则 [v-cloak] {display: none;} 配合, 可以做到在插值编译之后才显示
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>v-cloak</title>
    <style>
        [v-cloak] {display: none;}
    </style>
</head>
<body>
<div id="app">
    <p v-cloak>---{{ msg }}</p>
</div>
<script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            msg: 'hello'
        }
    });
</script>
</body>
</html>
```
v-cloak不会覆盖元素中的内容, 只是将占位符替换掉, 所以上面代码显示 ---hello
#### v-text
v-text不会产生插值闪烁问题, 但是v-text会将元素中的内容覆盖, 也就是下面代码的 --- 内容不会显示
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>v-text</title>
</head>
<body>
<div id="app">
    <p v-text="msg">---</p>
</div>
<script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            msg: 'hello'
        }
    });
</script>
</body>
</html>
```
#### v-html
v-html可以渲染HTML, 会将元素中的内容覆盖
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>v-html</title>
</head>
<body>
<div id="app">
    <!-- <b>hello</b> -->
    <p v-text="msg"></p>
    <!-- hello(加粗显示) -->
    <p v-html="msg">---</p>
</div>
<script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            msg: '<b>hello</b>'
        }
    });
</script>
</body>
</html>
```
#### v-bind
v-bind 动态绑定属性, 可以简写为 :属性  
下面的代码显示两个 提交ok 的按钮
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>v-bind</title>
    <script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
</head>
<body>
<div id="app">
    <!-- v-bind中可以写js表达式 -->
    <input type="submit" v-bind:value="msg + 'ok'">
    <!-- v-bind简写 -->
    <input type="submit" :value="msg + 'ok'">
</div>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            msg: '提交'
        }
    });
</script>
</body>
</html>
```
#### v-on
v-on 绑定事件监听器, 可以简写为 @动作
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>v-on</title>
    <script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
</head>
<body>
<div id="app">
    <input type="button" v-on:click="alert('hello')" value="点击">
    <input type="button" @click="show" value="点击">
</div>
<script>
    var vm = new Vue({
        el: '#app',
        methods: {
            show: function () {
                alert('hello');
            }
        }
    });
</script>
</body>
</html>
```