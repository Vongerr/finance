<?php

namespace app\services;

use app\entities\Finance;
use Shuchkin\SimpleXLSXGen;

class ExportFinanceService
{
    /**
     * @return array|Finance[]
     */
    private function getModelsExperiment(): array
    {
        return Finance::find()
            ->all();
    }

    public function exportFinance(): int
    {
        $number = 0;

        $models = $this->getModelsExperiment();

        $data[] = [
            'id',
            'hash',
            'budget_category',
            'category',
            'date',
            'time',
            'date_time',
            'username',
            'money',
            'bank',
            'comment',
            'exclusion',
            'created_at',
            'updated_at',
        ];

        foreach ($models as $model) {

            $result = [];

            foreach ($model->attributes() as $attribute) {

                $result[] = $model->$attribute;
            }

            $data[] = $result;

            $number++;
        }

        SimpleXLSXGen::fromArray($data)->saveAs('export/finance.xlsx');

        return $number;
    }
}