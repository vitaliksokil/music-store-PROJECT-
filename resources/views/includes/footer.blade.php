
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="copyright">
                        <h4>&copy; 2019 <span v-text="siteInfo.sitename"></span></h4>
                        <a href="#">Правила использования сайта</a>
                    </div>
                    <div class="dev-info">
                        <a href="#">Разработка сайта</a> <span></span>
                    </div>
                    <div class="dev-info">
                        <a href="#">Дизайн сайта </a><span></span>
                    </div>

                </div>
                <div class="col-lg-3">
                    <div class="store-info">
                        <div class="store-info-title">
                            <h4>
                                Магазин
                            </h4>
                        </div>
                        <div class="store-info-street">
                            <p><i class="fas fa-map-marker-alt"></i><span v-text="siteInfo.street"></span></p>
                            <p><i class="fas fa-phone-alt"></i><span v-text="siteInfo.phonenumber"></span></p>
                            <p><i class="fas fa-clock"></i><span v-text="siteInfo.schedule"></span></p>
                        </div>
                        <div class="store-info-sn">
                            <a :href="siteInfo.vk"><i class="fab fa-vk"></i></a>
                            <a :href="siteInfo.facebook"><i class="fab fa-facebook-f"></i></a>
                            <a :href="siteInfo.olx"><img src="{{asset('images/olx.png')}}" alt=""></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pages">
                        <table>
                            <tr class="d-flex flex-wrap">
                                <td><a href="">О компании</a></td>
                                <td><a href="">наша история</a></td>
                                <td><a href="">благодарности</a></td>
                                <td><a href="">О компании</a></td>
                                <td><a href="">наша история</a></td>
                                <td><a href="">благодарности</a></td>
                                <td><a href="">Магазины</a></td>
                                <td><a href="">наша история</a></td>
                                <td><a href="">Контакты</a></td>
                                <td><a href="">О компании</a></td>
                                <td><a href="">вакансии</a></td>
                                <td><a href="">Производители</a></td>
                                <td><a href="">О компании</a></td>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </footer>

