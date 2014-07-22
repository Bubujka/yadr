# Интерфейс к яндекс.директ на php.


## Установка

- Через composer
- Вручную подключить bu.defun ( https://github.com/Bubujka/bu.defun ) и yadr.php

## Использование

Где-то в конфигах:

```php
<?php
yadr\production(true); 
yadr\login('xxxxxx');
yadr\app_id('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
yadr\token('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
```


И вызываем методы:

```php
<?php
print_r(yadr\GetCampaignsList());
```

## Если не нравятся нэймспэйсы

Можно создать врапперы для методов в глобальной области видимости:
```php
<?php
yadr\create_wrappers(true);
print_r(GetCampaignsList());
```

## Отладка 

Где то в коде добавьте строки:

```php

with_wrapper('yadr\method', function($fn){
  $return = $fn();
  $debug = array(
    'method'=>$fn->args[0],
    'params'=>$fn->args[1],
    'return'=>$return,
    'login'=>yadr\login(),
    'token'=>yadr\token(),
    'app_id'=>yadr\app_id());
  file_put_contents('log/'.microtime(true).'-'.md5(rand()).'.json', json_encode($debug));
  return $return;
});
```

Все результаты будут складироваться в папку log.
