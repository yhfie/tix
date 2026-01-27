1. `git clone https://github.com/yhfie/tix.git`
2. `cd tix`
3. `composer install`
4. `npm install`
5. `php artisan migrate`
6. `php artisan db:seed`
7. Terminal A: `php artisan serve`
8. Terminal B: `npm run dev`

---

migration: add soft deletes to a certain table:

`php artisan make:migration add_deleted_at_to_orders_table --table=orders`