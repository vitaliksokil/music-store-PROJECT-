# music-store-PROJECT-

Site - http://f0392410.xsph.ru/  
For testing functionality you can login as admin:
- Admin Login : admin@gmail.com
- Admin Pass : Admin123

# Short description of the project 
This project is a **SPA** musical online store.
It was created by using **PHP(Laravel)** and **JS(Vue.js)** technologies

## Registration
User must register to work with the site, otherwise he or she can only look at the products.
To create registration was used **Laravel Passport, JWT**.
Authentication also includes **email verification and password reset**.
## Profile
To get to the profile user must cofirm email adress.
In the profile **user can see short information about himself, can change name,password and email**.
User can see also his orders.
**Admin can do CRUD operations with users,products,categories, can confirm orders and see payments**.
## Products
Products are shown by categories. User can choose category, can sort products by price,name etc.
Every registered **user can leave a feedback about product, can likes/dislikes feedbacks, can add product to wishlist or shopping card**.
When product is already in the shopping card user can buy it.
To make an order user must fill the form(delivery address, name,phone etc.) 
To get ukrainian cities and post offices was used **"New post" REST API**.
User can pay online by Webmoney ( **was used Webmoney REST API**)

###### P.S
I was focusing on the back-end, so html layout is a little bit bad(some blocks can be moved from their place)  :)


---
---
**RUS**

**Этот проект был выполнен в учебных целях, поэтому не обращайте особое внимание на недоработки, особено в верстке так как здесь я делал упор на back-end и логику front-end'a . Проект делался на скорую руку, поэтому он не идеален :) **

Для того чтобы увидеть все возможности администратора можете воспользоваться следующими данными (только просьба не заниматься вандализмом :) ):
Admin Login : admin@gmail.com
Admin Pass : Admin123

# КРАТКОЕ ОПИСАНИЕ ПРОЕКТА:

Этот проект представляет собой музыкальный **SPA(single page application)** интернет-магазин . Он был создан с использованием технологий **PHP (Laravel) и JS (Vue.js)**

## РЕГИСТРАЦИЯ:

Пользователь должен зарегистрироваться, чтобы работать с сайтом, иначе он или она может только смотреть на товары. Для создания регистрации был использован **Laravel Passport, JWT**. Аутентификация также включает **проверку электронной почты и сброс пароля.**

## ПРОФИЛЬ:

Чтобы попасть в профиль, пользователь должен подтвердить адрес электронной почты. **В профиле пользователь может видеть краткую информацию о себе, может менять имя, пароль и адрес электронной почты**. Пользователь может видеть также его заказы. **Администратор может выполнять операции CRUD(create,read,update,delete) с пользователями, продуктами, категориями, может подтверждать заказы и просматривать платежи.**

## ТОВАРЫ

Продукты показаны по категориям. Пользователь может выбрать категорию, отсортировать товары по цене, названию и т. Д. **Каждый зарегистрированный пользователь может оставить отзыв о товаре, может поставить лайк или дизлайк на отзыв, добавить товар в список желаний или в корзину.** Когда товар уже есть в карточке покупателя, пользователь может его купить.  Для оформления заказа пользователь должен заполнить форму (адрес доставки, имя, телефон и т. Д.). Для получения украинских городов и почтовых отделений был использован **REST API «Новая почта»**. Пользователь может оплатить онлайн через **Webmoney (использовался REST API Webmoney)**

