<h1 align="center">PHP Package for CMCC IOT </h1>

<p align="center"> The simplest implement of CMCC IOT</p>


## Installing

```shell
$ composer require ac1982/cmcc -vvv
```

## Usage
```php
use AC1982\CMCC;

$config=['companyId'=>'13800138000','appKey'=>'2321323232','secret'=>'aefdfsagweg'];

(new AC1982\CMCCClient($config))->query('triopi.member.iccid.all.query','XXXXXX');
```

## Note

这是移动物联云的客户端，最简单的实现。
你很容易根据这个基础包扩展出自己的应用。
- 签名
- 解密
## License

MIT
