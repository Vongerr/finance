<?php

namespace app\controllers;

use app\models\search\FutureFinanceSearch;
use app\services\FutureStatisticService;
use Yii;
use yii\base\InvalidConfigException;
use yii\gii\Module;

class FutureStatisticController extends MainController
{
    private FutureStatisticService $service;

    public function __construct(string                 $id,
                                Module                 $module,
                                FutureStatisticService $service,
                                array                  $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionIndex(): string
    {
        $searchModel = Yii::createObject([
            'class' => FutureFinanceSearch::class
        ]);

        $dataProvider = $searchModel->search(app()->request->get() ?? []);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ], true);
    }
}

?>

Подписываем выбор количества товаров и делаем поле для ввода количества суток

<!-- Как добавить поле ввода количества товара в корзине в Tilda mo-ti.ru -->
<script src="https://static.tildacdn.com/js/jquery-1.10.2.min.js" charset="utf-8" onerror="this.loaderr='y';">
</script>
<style>
    /*Оформлениеполя*/
    .superinput {
        border: 1px solid #bebebe !important;
        height: 32px;
        border-radius: 30px;
        box-shadow: 0px 0px 3px 1px #e2e2e2;
        text-align: center;
    }
    /*Оформление поля при наведении*/
    .superinput:hover {
        border: 1px solid #e3ae00 !important;
        box-shadow: 0px 0px 3px 1px #e3ae00;
    }
    /*Скрываемкнопки + - */
    .t706__product-minus , .t706__product-quantity , .t706__product-plus {
        display:none;
    }
    /*Стиль для надписи кол-во*/
    .descipt{
        text-align:center;
        font-size:14px;
        color:#000000;
        margin-top:-16px;
    }
    /*Ширина и отступы на мобильном*/
    @media screen and (max-width: 640px){
        .superinput {
            width:80px !important;
        }
        .t706__product-title {
            margin-bottom: 10px;
        }
    }
</style>

<script>
    $( document ).ready(function() {

        var number = 0;
//Функция создания полеё ввода
        functioncreateinput(){
            setTimeout(function() {
                var spisok = $(".t706__product-plusminus").length;
                for (var x = 0; x <= spisok; x++) {
                    $('.t706__product-plusminus:eq('+x+')').append('<input type="text" name="colvo" class="t-input superinputcolvojs-tilda-rule  " value=""  style="color:#000000; border:1px solid #000000;  ">');
                    number =  $('.t706__product-quantity:eq('+x+')').html();
                    $('.colvo:eq('+x+')').val(number);
                };
//ДобавимподписьКОЛ-ВО
                $('.t706__product-plusminus').prepend('<div class="descipt">Кол-восуток</div>');
            }, 100);
        };

//При открытии корзины запускаем функцию создания полей ввода
        $( "[href = #order] , .t706__carticon-wrapper"  ).click(function() {createinput();});

//При удалении товара запускаем функцию создания полей ввода
        $(document).on('click','.t706__product-del',function(e){ createinput();});

//Разрешаем ввод только цифр в 1 и 2 поле ввода
        $(document).on('keydown','.superinput',function () {
            // Разрешаем: backspace, delete, tab и escape
            if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
                // Разрешаем: Ctrl+A
                (event.keyCode == 65 &&event.ctrlKey === true) ||
                // Разрешаем: home, end, влево, вправо
                (event.keyCode>= 35 &&event.keyCode<= 39)) {
// Ничего не делаем
                return;
            } else {
                // Запрещаем все, кроме цифр на основной клавиатуре, а так жеNum-клавиатуре
                if ((event.keyCode< 48 || event.keyCode> 57) && (event.keyCode< 96 || event.keyCode> 105 )) {
                    event.preventDefault();
                }
            }
        });

//Ставим ограничение на кол-во цифр 3 в поле  - максимум 999
        $(document).on('keyup','.superinput',function () {
            var $this = $(this);
            if($this.val().length > 3)
                $this.val($this.val().substr(0, 3));
        });

//При потере фокуса в поле подсчитываем сумму товара

        $(document).on('focusout','.colvo',function () {
            number =   $(this).val();
            var step = $(".colvo").index(this);
            if (number==0){number++;$(this).val(number); };
            var oldnumber = $('.t706__product-quantity:eq('+step+')').html();
            raznost = number - oldnumber;
            if (raznost>0){
                for (var i = 1; i<= raznost; i++) {
                    $('.t706__product-plus:eq('+step+')')[0].click();
                }; };
            if (raznost<0){
                raznost = raznost*(-1);
                for (var i = 1; i<= raznost; i++) {
                    $('.t706__product-minus:eq('+step+')')[0].click();
                }; };  });
    });

</script>


Делаем скидку от количества суток

<script>


    "use strict";
    function getSumm() {
        for (const product of tcart.products) {

            if (product.quantity>= 2 &&product.quantity<= 2) {
                product.price = parseFloat(product.options[1].variant);
                product.amount = product.price * product.quantity

            } else if (product.quantity>= 3 &&product.quantity<= 3) {
                product.price = parseFloat(product.options[2].variant);
                product.amount = product.price * product.quantity
            } else if (product.quantity>= 4 &&product.quantity<= 6) {
                product.price = parseFloat(product.options[3].variant);
                product.amount = product.price * product.quantity
            } else if (product.quantity>= 7 &&product.quantity<= 13) {
                product.price = parseFloat(product.options[4].variant);
                product.amount = product.price * product.quantity
            } else if (product.quantity>= 14 &&product.quantity<= 28) {
                product.price = parseFloat(product.options[5].variant);
                product.amount = product.price * product.quantity
            } else if (product.quantity>= 29 &&product.quantity<= 60) {
                product.price = parseFloat(product.options[6].variant);
                product.amount = product.price * product.quantity
            } else if (product.quantity>= 61 &&product.quantity<= 10e3) {
                product.price = parseFloat(product.options[7].variant);
                product.amount = product.price * product.quantity
            } else {
                product.price = parseFloat(product.options[0].variant);
                product.amount = product.price * product.quantity
            }
        }
    }
    function amoun() {
        tcart.amount = tcart.prodamount
    }
    function rpodaamount() {
        tcart.prodamount = 0;

        for (const product of tcart.products) {
            tcart.prodamount += product.amount
        }
    }
    //************* ценызаединицу ***************
    //найдеммассиввсехцен
    function replaceAmountItem() {
        let arrGoodsPice = document.querySelectorAll('.t706__product-amount') // всеместагденужнопоменятьцены
// откудабудембратьцены
        for (let i = 0; i<arrGoodsPice.length; i++) {
            arrGoodsPice[i].textContent =  `${tcart.products[i].amount} €`
        }
    }
    setInterval(() => {
            rpodaamount();
            getSumm();
            amoun();
            sumBasket();
            replaceAmountItem();
            delItem();
        },
        400)
    tcart.amount = tcart.prodamount
    //*********** суммапокупки *****************
    function sumBasket() {
        let sum = document.querySelector('.t706__cartwin-prodamount-wrap');
        sum.innerHTML = `
<span class="t706__cartwin-prodamount-label">Сумма: </span>
<span class="t706__cartwin-prodamount">${tcart.amount} €</span>`
    }
    function delItem() {
        let delItem = document.querySelectorAll('.js-product-controls-wrapper');
        for (const delItemElement of delItem) {
            delItemElement.style.display = 'none';
        }
    }


</script>

