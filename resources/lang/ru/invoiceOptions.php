<?php

return [
    'types'             => [
        'service' => 'Услуга',
        'product' => 'Товар'
    ],
    'transaction_types' => [
        'cash'     => 'Наличные',
        'cashless' => 'Безналичные',
    ],
    'units'             => [
        'pcs' => 'шт.',
        'kg'  => 'кг.',
        'ltr' => 'литр',
    ],
    'currencies'        => [
        'som' => 'Сом',
        'usd' => 'Доллар',
    ],
    'statuses'          => [
        'unpaid'         => 'Не оплачен',
        'partially_paid' => 'Частично оплачен',
        'paid'           => 'Оплачен',
    ],
];
