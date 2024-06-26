# SDS4 app

Цей додаток написано на PHP, використовуючи фреймворк Laravel. Як базу даних було використано PostgreSQL, пошту надсилають через акаунт гугла.

# TL; DR;
Для того щоб достукатися до контейнера через gses2.app, потрібно додати його у файлі hosts з маппінгом на локалхост
У `.env` є змінна DOCKER_NETWORK, яка показує на якому нетворку запускати стак контейнерів, за дефолтом стоїть 192.168.100.0, але якщо він у вас зайнятий, замініть його на вільний айпі
Для підйому контейнерів використовуйте docker compose up і обов'язково дочекайтеся, поки скрипт завершить роботу (перший раз композеру треба все завантажити)
`.env` - енв зазвичай не повинен бути в репозиторії, але оскільки це навчальний проєкт, не бачу сенсу його приховувати.
в `.env` файлі є налаштування СМТП для того щоб відправляти імейли (пароль неактивний) і поле `SHOULD_BE_VERIFIED`, яке задає, чи потрібно людині підтверджувати пошту та по дефолту `false`, щоб мати змогу отримати розсилку.
Додано Feature Test-и, прогнати їх можна командою `php artisan test` внтури контейнера `app`
Доступні ендопінти:
```sh
Route::get('/rate', [CurrencyExchangeRateController::class, 'getExchangeRate']);
```
```sh
Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
```

## Currency Conversion Rate USD -> UAH

Був створений ендопінт /rate в групі /api за яким нам повертається чисельне значення курсу використовуючи сторонній сервіс https://api.apilayer.com для отримання курсу долара до гривні на поточний момент.
Ендпоінт виглядає наступним чином:
```sh
Route::get('/rate', [CurrencyExchangeRateController::class, 'getExchangeRate']);
```
У контролері ми витягуємо з сервіс провайдера об'єкт сервісу `CurrencyExchangeRateService`
У самому сервісі ми також отримуємо через ін'єкцію залежностей `ApiLayerCurrencyExchangeRateRepository`, бачити байндинги можна в `App\Providers\CurrencyExchangeRateServiceProvider`. За великим рахунком інтерфейс репозиторію в такому невеликому застосунку не потрібен, можна було обійтися без рівня абстракції й одразу віддавати репозиторій безпосередньо, але реалізований підхід полегшує подальшу розробку застосунку в реальному світі, даючи чіткий інтерфейс для репозиторію, якщо нам, наприклад, треба буде створити ще вибірку курсу долара з файлу.
У самому репозиторії реалізовано метод `getCurrentRate()`, який приймає собі за контрактом два параметри: 1) яку валюту з 2) якою ми хочемо порівняти. Отримуємо `$url` для під'єднання до стороннього сервісу, робимо запит, отримуємо відповідь, якщо сервер не відповідає, то нам викидається `ConnectionException`, він хендлиться в `bootstrap/app.php`, де повертається 400 відповідь як прописано в документації. Далі якщо ми отримали відповідь, але вона не така, якою ми її очікуємо, ми повертаємо `MalformedApiResponseException`, яка теж видає нам 400 відповідь, як просять у доці. Якщо все пройшло гладко, повертається флоат у сервіс, на цьому етапі можна додавати якусь бізнес-логіку на кшталт округлення або збереження в кеш чи базу. У нашому випадку відповідь просто форвардується далі в контролер, у контролері ми загортаємо відповідь в обгортку, ставимо їй статус (200) і віддаємо користувачеві.

## Daily Currency Сonversion Rate Subscription
Був створений ендопінт /subscribe в групі /api за яким нам повертається порожня відповідь зі статусом 200 або помилка
Ендпоінт виглядає наступним чином:
```sh
Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
```
У контролері ми забираємо реквест який нам прилетів, сам реквест валідує імейл на те, що він власне є імейлом, якщо імейл не валідний, повертається помилка `ValidationException` статус якої 400. У документації не було прописано, що ми маємо віддавати щось окрім 200 і 409 статусу, але ні той, ні інший статус логічно не підходить під непройдену валідацію, скоріш за все, такі формати відповідей просто не розписували, тому що їх було опущено, я вирішив їх додати, бо юзер може туди написати що завгодно.
Після пройденої валідації, ми отримуємо `$email` і шукаємо по ньому користувача в базі, якщо такий користувач існує, ми кидаємо помилку `AlreadyExistsException`, яка в `app.php` обробляється в порожній респонсі зі статусом 409, якщо все проходить гладко, ми забираємо з контейнера `SubscriptionService`, і викликаємо в нього метод `subscribe($email)`, який підписує нам юзера. Метод намагається створити підписника, якщо у нього це не виходить, то програма викидає ексепшн, який я обробляю як 500 (тут точно така сама ситуація, як і з валідацією). Якщо все пройшло на ура ми викликаємо евент `Subscribed` який приймає в себе інстанс `Subscriber`. Laravel зі свого боку викликає лістенера `Subscribed` - `HandleSubscribedListener`, в якому залежно від налаштувань середовища (а саме `SHOULD_BE_VERIFIED`) визначається, чи має людина підтвердити пошту для розсилки, чи ні, за дефолтом людина НЕ має підтверджувати свою пошту. `HandleSubscribedListener` використовує інтерфейс `ShouldQueue`, який зменшує час виконання запиту, переводячи евент у джобси, що виконуються у фоні.

Щоденне надсилання листів із курсом здійснюється через метод `withSchedule()` в `app.php`, який викликає щодня клас `DispatchBatchedExchangeRateNotificationJobs`, що своєю чергою з `CurrencyExchangeRateService` отримує курс на сьогодні, обирає всіх юзерів, які підтвердили свій імейл, батчить їх на чанки по 500 штук і створює джоб, який уже своєю чергою створює джоби на надсилання самого імейла. Імейли відправляються один раз на день о 6 00.

## Docker
Для роботи цього проєкту було використано докер композ, у якому було створено 3 різні контейнери (web, db, app)
`web` - контейнер для сервера, який приймає запити ззовні і форвардить їх у контейнер з PHP
`db` - контейнер для бази даних  
`app` - контейнер для PHP, що обробляє запити, сфорварджені з nginx'a

Для кожного контейнера було створено окремий `Dockerfile`, що знаходиться в папці `docker/{folderName}`. Dockerfile для бази просто пулить зображення постгресу, для nginx'a пулить зображення і копіює конфіг в папку з конфігами nginx'a в контейнері, для PHP докерфайл пулить PHP-fpm встановлює потрібні залежності, PHPUnit і відкриває порт 9000 в контейнері.  
Процес білда йде наступним чином: `db -> app -> web`, кожний наступний залежить від кожного попереднього, і після того, як зібрався контейнер app, виконується скрипт `init_script.sh`, у якому надаються права для всіх папок, окрім postgres, у папці storage для користувача веб сервера (nginx у нашому випадку), відбувається встановлення всіх пакетів із composer.json, відбуваються міграції та запускається supevisor. Через це вкрай важливо почекати, поки контейнер завершить скрипт, щоб не отримати `502 Bad Gateway`.
Для хендлінгу крон тасок було додано супервізор, який тримає процеси `php-fpm`, `php artisan queue:work` та `php artisan schedule:work` активними.  
Після цього додатком можна користуватися.
> Note: для того щоб мати змогу достукатися до контейнера через gses2.app, потрібно додати маппінг локалхоста на це доменне ім'я у файлі hosts

Translated with DeepL.com (free version)
