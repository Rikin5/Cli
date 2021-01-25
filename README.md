# Cli
*Библиотека для создания cli команд*

Для генерации автозагрузчика запустите команду 
```sh
composer dump-autoload
```

Для работы с библиотекой используйте 
```sh
php bin/cli.php
```

Для создания новой команды необходимо создать класс для новой команды в папке Commands и унаследовать абстрактный класс Command.php. После зарегистрировать его в классе **CommandManager.php в методе register()**

#### Пример:
```sh
protected function register(): void 
{
    $this->bindTo("Cli\Commands\Sum", "sum");
}
```
