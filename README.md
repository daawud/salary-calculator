# Cервис для расчета заработной платы.

Реализовано на Laravel PHP Framework.

## Запуск проекта

- Скачиваем проект из репозитория

  `git clone https://github.com/daawud/salary-calculator.git`

- Устанавливаем необходимые пакеты

  `composer install`

- Копируем конфигурационный файл

  `cp .env.example .env`

- Запускаем проект

  `docker-compose up -d --build`

- Заходим в контейнер salary_php, применяем миграции и сиды

  `php artisan migrate --seed`

## Методы API

### Создание записи о начислении зарплаты

- Запрос: `POST http://example.loc/api/payroll/create`

- Параметры запроса:

  {

        "employee_id": 8,
        "salary": 650000,
        "norm": 22,
        "worked": 22,
        "tax_deduction": true,
        "year": 2022,
        "month": 3,
        "is_pensioner": false,
        "disability_group": null
  }

- Ответ:

  {

          "salary_gross": 650000,
          "iit": 52950,
          "cpc": 65000,
          "cmshi": 13000,
          "mshi": 13000,
          "sd": 20475,
          "salary_net": 485575
  }

### Получение результата калькуляции ЗП

- Запрос: `GET http://example.loc/api/payroll/calculate`

- Параметры запроса:

  {

        "employee_id": 8,
        "salary": 650000,
        "norm": 22,
        "worked": 22,
        "tax_deduction": true,
        "year": 2022,
        "month": 3,
        "is_pensioner": false,
        "disability_group": null
  }

- Ответ:

  {

          "salary_gross": 650000,
          "iit": 52950,
          "cpc": 65000,
          "cmshi": 13000,
          "mshi": 13000,
          "sd": 20475,
          "salary_net": 485575
  }