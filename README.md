## Проект пиццерии для группы ИС-231

Работа с git
```
запустите Git Bash
перейдите в каталог c:/xampp/htdocs
> cd c:/xampp/htdocs
Удалите все файлы и папки в htdocs

Fork репозитория Coopteh/pizza231
- Войдите в свой аккаунт на github.com и сделайте fork
на репу https://github.com/Coopteh/pizza231
- Снимите галочку с "Copy the main branch only"

Настройка локального репозитория - клонируйте свой форкнутый репозиторий по SSH-ссылке
> git clone git@....
скопируйте в Проводнике (скрытую) папку .git и файл readme.md в корень каталога c:/xampp/htdocs
удалите папку pizza231
- в .gitignore добавьте папку vendor (просто запишите эту строку)

Настройте обновление веток в терминале
> git remote add pizza231 https://github.com/Coopteh/pizza231.git
получим обновления веток с оригинального репозитория (на coopteh)
> git fetch pizza231
выполните в bash терминале
> git pull
```
Выполните работу по курсу "Разработка кода".

Закоммитьте и запуште изменения
```
> git status
> git add .
> git status
> git commit -m "Шаблон базовой страницы"
> git push
```
Сдайте работу - создав запрос на изменения Pull Request  
- зайдите на github и создайте Pull Request со своего аккаунта в исходный репозиторий (для аккаунта Coopteh)
