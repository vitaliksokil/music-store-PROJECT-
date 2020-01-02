<header>
    <div class="header-info">
        <div class="container">
            <div class="row d-flex justify-content-around align-items-center">
                <div class="col-lg-2 header-info-location">
                    <p>
                        <span class="dotted" v-text="siteInfo.town"></span> -> <span class="underline"
                                                                                     v-text="siteInfo.street"></span>
                    </p>
                </div>
                <div class="col-lg-2">
                    <p>
                        <span><i class="fas fa-phone fa-flip-horizontal"></i></span>
                        <span v-text="siteInfo.phonenumber"></span>
                    </p>
                </div>
                <div class="col-lg-6">
                    <nav>
                        <ul class="d-flex justify-content-end">
                            <li><a href="">Как купить</a></li>
                            <li><a href="">Гарантия</a></li>
                            <li><a href="">Доставка</a></li>

                            <li v-if="!isAuth">
                                <router-link :to="{name:'login'}" class="hover-off">Sign in</router-link>
                                <router-link :to="{name:'register'}" class="border p-1 hover-off">Sign up</router-link>
                            </li>
                            <li v-else>
                                <router-link :to="{name:'profile'}" :siteInfo="siteInfo">Мой профиль</router-link>
                                <a @click="logout()" class="hover-off" style="cursor:pointer;"><span class="dotted">Logout</span></a>

                            </li>

                        </ul>

                    </nav>
                </div>
            </div> <!-- row-->


        </div><!-- container-->
    </div>
    <div class="header-logo">
        <div class="container">
            <div class="row align-items-center justify-content-around">
                <div class="col-lg-4">
                    <div class="logo">
                        <router-link to="/"><img :src="`/images/${siteInfo.logo}`" alt="">
                            <p>Мы работаем на ваш талант!</p></router-link>

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="input-group">
                        <input type="text" class="input-search" placeholder="Search for products"
                               @keyup.enter="mainSearch" v-model="query">
                        <div class="input-group-append">
                            <button class="btn btn-search" type="button" @click.prevent="mainSearch"><i
                                    class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <shopping-cart></shopping-cart>
                </div>
            </div>
        </div>
    </div>

    <div class="header-categories">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav>
                        <ul class="d-flex justify-content-around">
                            <li><a href="" class="active">Акции</a></li>
                            <li id="categoriesShow">
                                <router-link :to="{name:'category',params:{id:'all'}}">Музыкальные инструменты
                                </router-link>
                                <div class="container">
                                    <div id="categories-items">
                                        <div class="triangle-red"></div>
                                        <div class="triangle"></div>
                                        <div class="border"></div>
                                        <div class="row justify-content-around">
                                            <div class="col-lg-4 mb-5" v-for="category in categories"
                                                 v-if="!category.parent_id">
                                                <div class="categories-item">
                                                    <div class="categories-img text-center">
                                                        <img :src=`/images/categories/${category.photo}`
                                                             style="max-width: 100%; height:70px" alt="">
                                                    </div>
                                                    <router-link :to="{name:'category',params:{id:category.id}}"
                                                                 class="categories-title">
                                                        <h2 v-text="category.title"></h2>
                                                    </router-link>

                                                    <div class="categories-list">
                                                        <ul>
                                                            <li v-for="child in category.children"
                                                                style="text-align: center">
                                                                <router-link
                                                                    :to="{name:'category',params:{id:child.id}}"
                                                                    v-text="child.title"></router-link>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </li>
                            <li><a href="">DJ</a></li>
                            <li><a href="">Шоу-техника</a></li>
                            <li><a href="">Студия</a></li>
                            <li><a href="">Звук</a></li>
                            <li><a href="">Свет</a></li>
                            <li><a href="">Коммутация</a></li>
                            <li><a href="">Стойки</a></li>
                        </ul>
                    </nav>

                </div>
            </div>

        </div>
    </div>
</header>


