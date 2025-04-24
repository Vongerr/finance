<?php

namespace app\controllers;

use app\services\ExportFinanceService;
use app\services\ImportFinanceService;
use Exception;
use yii\web\BadRequestHttpException;

class TransferFinanceController extends MainController
{
    const IMPORT_FINANCE_TINKOFF = '\\/transfer-finance\import-finance-tinkoff';
    const IMPORT_FINANCE_ALPHA = '\\/transfer-finance\import-finance-alpha';
    const IMPORT_FINANCE_OTKRITIE = '\\/transfer-finance\import-finance-otkritie';
    const IMPORT_FINANCE = '\\/transfer-finance\import-finance';
    const EXPORT_FINANCE = '\\/transfer-finance\export-finance';

    private ImportFinanceService $serviceImport;

    private ExportFinanceService $serviceExport;

    public function __construct(string               $id,
                                                     $module,
                                ImportFinanceService $serviceImport,
                                ExportFinanceService $serviceExport,
                                array                $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->serviceImport = $serviceImport;
        $this->serviceExport = $serviceExport;
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {

            //$this->setTitle('Статистика');

            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function actionImportFinanceTinkoff(): void
    {
        $this->serviceImport->importFinanceTinkoff();
    }

    /**
     * @throws Exception
     */
    public function actionImportFinanceAlpha(): void
    {
        $this->serviceImport->importFinanceAlpha();
    }

    public function actionImportFinanceOtkritie(): void
    {
        $this->serviceImport->importFinanceOtkritie();
    }

    /**
     * @throws Exception
     */
    public function actionImportFinance(): void
    {
        printr(1,1);
        $this->serviceImport->importFinance();
    }

    public function actionExportFinance(): string
    {
        $number = $this->serviceExport->exportFinance();

        return 'Экспортировано ' . $number . ' записей';
    }
}