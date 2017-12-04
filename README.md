READ ME

#### 源码

> src    对应 webroot
> sql    数据库文件

#### 后台账号密码

> admin@bropeak.com
> 111111


#### 框架

> yaf

#### 模板

> blade

#### 数据库框架

> Medoo,使用过程中当把 对象传入 框架的 事务中时，报错！
>
> 解决方法：
>
> 在 vendor 下的 Medoo 文件内：
>
> ```
> public function beginTransaction(){
>    return $this->pdo->beginTransaction();
> }
>
> public function commit(){
>    return $this->pdo->commit();
> }
>
> public function rollBack(){
>    return $this->pdo->rollBack();
> }
> ```

#### 配置文件

> /application/conf/application.ini



#### 待测试问题：

> 分离表缀




Jhin

2017/11/28