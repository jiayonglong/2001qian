<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016101900723581",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCPO4KDMcY1bmn6RkfsSIlB3IgM+DXlsz5Z+HU1RJ4UHtsGBOsMeqSO5xSw836Bvsr6V1urjx4nwgsMZQIvduWiyTZjWv+XpMwG+WiY8BKkGjGO7qNzf8WnYlPS3KER00ZGzpNZc9/MgXoUbL8KXq+LdLWR/NvzIw0nVgsZvPnIfsDLho2pe4SkV3SqWyg0mRX8EDqf0bnT2zMqkF5EnGGoSD4En84/kurfjtUjQbrxcSVxwdmP0ORBi30K3hAeG96EPLHPw34LaXzVIZdh+MIZNs5NiE6NyIJTwyPiAbO9+U6BgKiOUIHfudWzfa7mAV4RAUPy6uKO+t2I1D+v7zy9AgMBAAECggEAMhnS0Sk797yjfVPQHKpSuKL3Q/IeiDZmfPoXpYZW9RrF6hkWTssnfIUAC9VgTun5/g9IlX3+QlHxo2hBl3CzanmPeLl/5f9TDq13FOgE3Tn8U87Nhb0P2jjesUdTX/TTBDmF+Sg7BEHV/gb73Bi6tsEgMeyM00NTeoryXxJnHOYRXbg7XM5yGXndsKYlpy+8dsCcNpcfS4yIT4IjOBpNFO3QfJVoVT2XjG+16uKVaEH97JMx2Gj2V0YF6fxP31oouAs2fkTYUlHRgOr672ZrcRpfQKIThV5PHiFOWkWu00kAbriyX104e4yrcDyFOlBTT8OwVpD2MoggHzp4ly8SgQKBgQDksuV6rGp/IMK3M+Un4vbx+m15Yr04WBkEGXHz7mrrK3VSdD+TJ8vl1+BfZpl6BffWPlpeJsZF/Tul3NrEhymoy4KY89VtBlBTL9NVTxoO4g0AMOuXo5CpNadUk142G7Yvld4GHjuPyaVMX0uKcmBbBaFbhrFUASBkPp/pCppiZQKBgQCgVLx9M109Wf6GwmaQipI+BVTRgdVpYHetTU5FPHJaqs0NfGM2pBtkJy6AKRvaxiMSvAkGKF+jT9Ue37oyCuKTJS9TZ3CPtiTHV7WrVTBS6ejlh5SVPYz7ghLqSsvy7LpYrlYajZFw2WgI5Pg8DNPT0/N+h/6hJGhnQCtoF9KfeQKBgQCDgKqbY1DJUNkl5t05ljRQ56LTTYdoQp//z3HKxakNGKwZgWadAyEnRH5r3N8bXxsnQitMQBd6XyZRNTFGGEJIIN0zfeGXEy5U5wcp4pYlQK4hEgAl7ZNc+NECvMpLapvTuOU1t2SgRV6bD6CsNyBYtmAV9Nk6aHtrqObiZYpoUQJ/JtRe0fOeoT0qZ3itXlQxFtQztom7Jco3McGHMz1wdITpJFr7rEASYi8bE+7g0BssY10m0lb00piDBPcqIPSB6yEWVRy4JhX20lBrrxLyoSIFRZGQapR9kGvwKM30luC+QLv4HnYqwynkCENr2x9ALg0m634ELQKkRl1EJfGfEQKBgQCzBLWXJ2V8dE6Si0NfDAAzDC8/RIPpPvSPkBHoHB7iAWRZGqUAxKhbR0+glTqX6hNZJawoTnIM6UFH+Z9WMV3Pk4CMhsOtkFCts5zb6LEpetVa/8e1jSMTwVXSzHYzu+CN8pn/8ahvIeY4gkc0QdrBpjmfwC0+nwJhBV2e5QXXWQ==",
		
		//异步通知地址
		'notify_url' => "http://工程公网访问地址/alipay.trade.wap.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "http://localhost/alipay/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk9SVY0p6K6HW5/ko83dv54MgBmn1qtBEmH8UKEB3i1cqPi+FTnSLrAdt43sirb6AL56Y3xRtUu2zvTUuqmx7ERrE2h74wbNB0LW3QXWfK8PD6KCOm2DQ7nd+XME4M/n2K+oxymBbYQPvh2VOe33V3vxzUyGlHBeRMb8FI0zlD49M5lccLV2s6ur287Lx8u2iR2fQo8sLp0x4WRpvse+YGDhOl0QUwbPXmxkqpoZUfLIYUvdTnz9puANqESTvlJGHH7YiTJPaZtVnv+QsJRP5baPjQtY7hUj6ExdqzRgNZjGEmnpvVHMdmW151NjeS5gKe7q3QK7dhq0MHUwvCpzSMQIDAQAB",
		
	
);