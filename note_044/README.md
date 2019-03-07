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
class绑定
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>class绑定</title>
    <script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
    <style>
        .red{color: red;}
        .bold{font-weight: bold;}
    </style>
</head>
<body>
<div id="app">
    <!-- 数组方式 -->
    <p :class="['red', 'bold']">hello</p>
    <p :class="['red', {'bold': bold}]">hello</p>

    <!-- 对象方式 -->
    <p :class="obj">hello</p>
</div>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            bold: false,
            obj: {red: true, bold: true}
        }
    });
</script>
</body>
</html>
```
style绑定
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>style绑定</title>
    <script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
</head>
<body>
<div id="app">
    <p :style="[red, bold]">hello</p>
</div>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            red: {'color': 'red'},
            bold: {'font-weight': 'bold'}
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
事件修饰符  
+ .stop       阻止冒泡
+ .prevent    阻止默认行为
+ .capture    添加事件侦听器时使用事件捕获模式
+ .self       只当事件在该元素本身触发时触发回调
+ .once       事件只触发一次
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>v-on事件修饰符</title>
    <script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
</head>
<body>
<div id="app">
    <!-- .stop 阻止冒泡, 点击之后只输出 button; 如果不加 .stop , 会依次输出 button div  -->
    <div @click="divHandler">
        <input type="button" value="点击" @click.stop="buttonHandler">
    </div>

    <!-- .prevent阻止默认行为, 点击之后只输出 link , 不会跳转; 如果不加.prevent会跳转到百度 -->
    <a href="http://www.baidu.com" @click.prevent="linkHandler">百度一下</a>

    <!-- .capture捕获事件触发机制, 点击之后先输出 div, 后输出 button -->
    <div @click.capture="divHandler">
        <input type="button" value="点击" @click="buttonHandler">
    </div>

    <!-- .self只当事件在该元素本身触发时触发回调, 点击button之后只输出 button , 只有点击div的时候才会输出 div -->
    <div @click.self="divHandler">
        <input type="button" value="点击" @click.stop="buttonHandler">
    </div>

    <!-- .once事件只触发一次, 只有第一次点击才有输出 -->
    <input type="button" value="点击" @click.once="buttonHandler">
</div>
<script>
    var vm = new Vue({
        el: '#app',
        methods: {
            divHandler: function () {
                console.log('div');
            },
            buttonHandler: function () {
                console.log('button');
            },
            linkHandler: function () {
                console.log('link');
            }
        }
    });
</script>
</body>
</html>
```
#### v-model
v-model指令用来在input、select、text、checkbox、radio等表单控件或者组件上创建双向绑定  
下面代码, 当v-model的input元素发生变化时, 其他两个msg内容也会发生变化; 当v-bind的input元素发生变化时, 其他两个msg内容不会发生变化
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>v-model</title>
    <script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
</head>
<body>
<div id="app">
    {{ msg }}
    <!-- v-model能实现M和V的双向绑定 -->
    <input type="text" v-model="msg">
    <!-- v-bind只能实现从M到V的单向绑定 -->
    <input type="text" v-bind:value="msg">
</div>
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
v-model实现计算器
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>v-model实现计算器</title>
    <script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
</head>
<body>
<div id="app">
    <input type="text" v-model="val1">

    <select v-model="opt">
        <option value="+">+</option>
        <option value="-">-</option>
        <option value="*">*</option>
        <option value="/">/</option>
    </select>

    <input type="text" v-model="val2">

    <input type="button" value="=" @click="calc">

    <input type="text" v-model="res">
</div>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            val1: 0,
            val2: 0,
            res: 0,
            opt: '+'
        },
        methods: {
            calc: function () {
                switch (this.opt) {
                    case '+':
                        this.res = parseInt(this.val1) + parseInt(this.val2)
                        break;
                    case '-':
                        this.res = parseInt(this.val1) - parseInt(this.val2)
                        break;
                    case '*':
                        this.res = parseInt(this.val1) * parseInt(this.val2)
                        break;
                    case '/':
                        this.res = parseInt(this.val1) / parseInt(this.val2)
                        break;
                }
            }
        }
    });
</script>
</body>
</html>
```
#### v-for
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>v-for</title>
    <script src="https://cdn.bootcss.com/vue/2.6.6/vue.min.js"></script>
</head>
<body>
<div id="app">
    <!-- 遍历普通数组 -->
    <p v-for="val in arr">{{ val }}</p>
    <p v-for="(val, key) in arr">key: {{ key }} val: {{ val }}</p>

    <!-- 遍历对象数组 -->
    <p v-for="user in objArr">id: {{ user.id }} name: {{ user.name }}</p>
    <p v-for="(user, key) in objArr">key: {{ key }} id: {{ user.id }} name: {{ user.name }}</p>

    <!-- 遍历对象 -->
    <p v-for="(val, key, i) in obj">i: {{ i }} key: {{ key }} age: {{ val }}</p>

    <!-- 迭代数字1到10 -->
    <p v-for="count in 10">{{ count }}</p>
</div>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            arr: [1, 2, 3],
            objArr: [
                {id: 1, name: 'name1'},
                {id: 2, name: 'name2'},
                {id: 3, name: 'name3'}
            ],
            obj: {
                id: 1,
                name: 'name1',
                age: 18
            }
        }
    });
</script>
</body>
</html>
```
v-for中使用key属性可以保证迭代的时候跟踪每个节点的身份, 从而做到重新排序
```
<div id="app">
    <p v-for="item in objArr" :key="item.id">{{ item.id }} {{ item.name }}</p>
</div>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            objArr: [
                {id: 1, name: 'name1'},
                {id: 2, name: 'name2'},
                {id: 3, name: 'name3'}
            ]
        }
    });
</script>
```
#### v-if
根据表达式的值的真假条件渲染元素
```
<div id="app">
    <p v-if="flag">hello</p>
</div>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            flag: true
        }
    });
</script>
```
#### v-show
v-show是切换元素的 display CSS 属性
```
<div id="app">
    <p v-show="flag">hello</p>
</div>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            flag: false
        }
    });
</script>
```