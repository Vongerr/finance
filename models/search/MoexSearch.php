<?php

namespace app\models\search;

class MoexSearch
{
    const STOCK_INDEX = 'stock_index'; // Индексы
    const STOCK_SHARES = 'stock_shares'; // Акции
    const STOCK_BONDS = 'stock_bonds'; // Облигации
    const CURRENCY_SELT = 'currency_selt'; // Валюта
    const FUTURES_FORTS = 'futures_forts'; // Фьючерсы
    const FUTURES_OPTIONS = 'futures_options'; // Опционы
    const STOCK_DR = 'stock_dr'; // Депозитарные расписки
    const STOCK_FOREIGN_SHARES = 'stock_foreign_shares'; // Иностранные ц.б.
    const STOCK_EUROBOND = 'stock_eurobond'; // Еврооблигации
    const STOCK_PPIF = 'stock_ppif'; // Паи ПИФов
    const STOCK_ETF = 'stock_etf'; // Биржевые фонды
    const CURRENCY_METAL = 'currency_metal'; // Драгоценные металлы
    const STOCK_QNV = 'stock_qnv'; // Квал. инвесторы
    const STOCK_GCC = 'stock_gcc'; // Клиринговые сертификаты участия
    const STOCK_DEPOSIT = 'stock_deposit'; // Депозиты с ЦК
    const CURRENCY_FUTURES = 'currency_futures'; // Валютный фьючерс
    const CURRENCY_INDICES = 'currency_indices'; // 	Валютные фиксинги
    const CURRENCY_OTCINDICES = 'currency_otcindices'; // Валютные внебиржевые индексы
    const STOCK_MORTGAGE = 'stock_mortgage'; // Ипотечный сертификат
}