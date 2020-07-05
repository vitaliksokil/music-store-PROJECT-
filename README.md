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




