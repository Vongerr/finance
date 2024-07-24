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

    public function exportFinance(): void
    {
        $models = $this->getModelsExperiment();

        $data[] = [
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

            $result[] = $model->hash;
            $result[] = $model->budget_category;
            $result[] = $model->category;
            $result[] = $model->date;
            $result[] = $model->time;
            $result[] = $model->date_time;
            $result[] = $model->username;
            $result[] = $model->money;
            $result[] = $model->bank;
            $result[] = $model->comment;
            $result[] = $model->exclusion;
            $result[] = $model->created_at;
            $result[] = $model->updated_at;

            $data[] = $result;
        }

        SimpleXLSXGen::fromArray($data)->saveAs('finance.xlsx');
    }
}