<?php
namespace App\Views;

class AboutTemplate extends BaseTemplate {

        public static function getTemplate(string $content = ''): string {
            // return <<<HTML
            //   <section>
            //     <div style="display: flex; justify-content: center;" width="100%">
            //         <h1>СТРАНИЦА В РАБОТЕ. НЕ СМОТРЕТЬ!</h1>
            //     </div>
            //     <div style="display: flex; justify-content: center;" width="100%">
            //         <img src="/assets/img/keep_out.png" class="d-block w-100 h-150" alt="...">
            //     </div>
            //     <div style="display: flex; justify-content: center;" width="100%">
            //         <img src="/assets/img/card5.png" class="d-block w-100 h-150" alt="...">
            //     </div>
            //   </section>
            // HTML;
            
            $content = <<<HTML
                <div class="container mt-4">
                <h1 class="text-center mb-4" style="font-size: 40px">О нас</h1>
                
                <div class="row">
                    <div class="col-md-8">
                        <p style="font-size: 25px">
                            "Это мой любимый магазин бытовой техники на Цитадели" - сказал о нас командер Шепард!
                        </p>
                            
                        <p style="font-size: 25px">
                            Магазин бытовой техники "Бытовая техника" - лучший магазин в мире, а остальные магазины нам завидуют!
                        </p>
                        
                        <p style="font-size: 25px">
                            Лучшие товары среди нас и нас!
                        </p>

                        <p style="font-size: 25px">
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quis possimus, explicabo exercitationem id placeat fuga quod accusamus atque. Autem, dolore. Unde soluta ipsum in beatae doloremque, eligendi impedit. Sapiente, officiis! Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quis possimus, explicabo exercitationem id placeat fuga quod accusamus atque. Autem, dolore. Unde soluta ipsum in beatae doloremque, eligendi impedit. Sapiente, officiis!
                        </p>

                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <img src="/assets/img/shepard.png" class="d-block w-100" style="height: 600px; object-fit: cover;" alt="Card 3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer>    
                <h4 class="mt-4">Контакты</h4>
                    <ul class="list-unstyled">
                        <li>Адрес: тамто</li>
                        <li>Телефон: 8 800 555 35 55</li>
                        <li>Мыло: мыло@мыло.su</li>
                        <li>Владелец: я</li>
                    </ul>
            </footer>
            HTML;

            return parent::getTemplate($content);
    }
}