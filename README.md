# Digemy Assessment by John Tavener

## Setup

```get clone https://github.com/johndug/digemy-tech-assessment.git```
```cd digemy-tech-assessment```
```docker-compose up -d```
```docker-compose exec app composer install```
```docker-compose exec app php artisan migrate:fresh --seed```
```docker-compose exec app npm install```
```docker-compose exec app npm run prod```

## UnitTests

```docker-compose exec app php artisan test```

## Invoice Managenemt System

login credentials:
admin@example.com
password

After login you are routed to an Invoice Dashboard with a few test invoices with model states. Click View to see the payments made, make payments and delete payments. After successfully making a payment the state will change accoding to the amount added.

## Assumptions

All logged in users are admin users which will be able to change invoices.

## Things still need to be done

- [ ] check cors between the fe and be
- [ ] On tthe api error handling use a exception handler to handle the response
- [ ] update to modern standards, laravel framework and vite bundler
- [ ] Make response file to better handler the data

## Prep for production

- [ ] error handling through sentry or similar
- [ ] throttling and rate limitting for high volumes of users
- [ ] install load balance for high volumes

## To The Future

Adding user policy to add users attached to the invoice
Notification of the updated states to the invoiced user
