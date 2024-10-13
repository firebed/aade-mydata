<?php

namespace Firebed\AadeMyData\Actions;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceDetails;

class GroupClassifications
{
    private array $incomeClassifications   = [];
    private array $expensesClassifications = [];

    /**
     * Handles the grouping of income and expense classifications.
     *
     * @param InvoiceDetails[]|null $rows
     * @param array $options
     * @return array
     */
    public function handle(?array $rows, array $options = []): array
    {
        if (empty($rows)) {
            return [[], []];
        }

        foreach ($rows as $row) {
            $this->groupIncomeClassifications($row);
            $this->groupExpensesClassifications($row);
        }

        return [
            $this->flattenIncomeClassifications($options),
            $this->flattenExpensesClassifications($options)
        ];
    }

    /**
     * Flattens the income classifications into an array.
     *
     * @param array $options
     * @return IncomeClassification[]
     */
    private function flattenIncomeClassifications(array $options = []): array
    {
        $flattenedIncomeClassifications = [];

        foreach ($this->incomeClassifications as $iclsCategory => $iclsTypes) {
            $category = IncomeClassificationCategory::from($iclsCategory);

            foreach ($iclsTypes as $iclsType => $amount) {
                $type = $iclsType ? IncomeClassificationType::from($iclsType) : null;

                $icls = new IncomeClassification();
                $icls->setClassificationType($type);
                $icls->setClassificationCategory($category);
                $icls->setAmount(round($amount, 2));

                if (!empty($options['enableClassificationIds'])) {
                    $id = count($flattenedIncomeClassifications) + 1;
                    $icls->setId($id);
                }

                $flattenedIncomeClassifications[] = $icls;
            }
        }

        return $flattenedIncomeClassifications;
    }

    /**
     * Flattens the expense classifications into an array.
     *
     * @param array $options
     * @return ExpensesClassification[]
     */
    private function flattenExpensesClassifications(array $options = []): array
    {
        $flattenedExpensesClassifications = [];

        foreach ($this->expensesClassifications as $eclsCategory => $eclsTypes) {
            $category = $eclsCategory ? ExpenseClassificationCategory::from($eclsCategory) : null;

            foreach ($eclsTypes as $eclsType => $eclsVatCategories) {
                $type = $eclsType ? ExpenseClassificationType::from($eclsType) : null;

                foreach ($eclsVatCategories as $eclsVatCategory => $eclsVatExemptions) {
                    $vat = $eclsVatCategory ? VatCategory::from($eclsVatCategory) : null;

                    foreach ($eclsVatExemptions as $vatExemption => $amounts) {
                        $exemption = $vatExemption ? VatExemption::from($vatExemption) : null;

                        $ecls = new ExpensesClassification();
                        $ecls->setClassificationType($type);
                        $ecls->setClassificationCategory($category);
                        $ecls->setAmount(round($amounts['amount'], 2));
                        $ecls->setVatAmount(round($amounts['vatAmount'], 2));
                        $ecls->setVatCategory($vat);
                        $ecls->setVatExemptionCategory($exemption);

                        if (!empty($options['enableClassificationIds'])) {
                            $id = count($flattenedExpensesClassifications) + 1;
                            $ecls->setId($id);
                        }

                        $flattenedExpensesClassifications[] = $ecls;
                    }
                }
            }
        }

        return $flattenedExpensesClassifications;
    }

    private function groupIncomeClassifications(InvoiceDetails $row): void
    {
        if (empty($row->getIncomeClassification())) {
            return;
        }

        foreach ($row->getIncomeClassification() as $icls) {
            $this->addIncomeClassification($icls);
        }
    }

    private function groupExpensesClassifications(InvoiceDetails $row): void
    {
        if (empty($row->getExpensesClassification())) {
            return;
        }

        foreach ($row->getExpensesClassification() as $ecls) {
            $this->addExpensesClassification($ecls);
        }
    }

    private function addIncomeClassification(IncomeClassification $icls): void
    {
        $eclsCategory = $icls->getClassificationCategory();
        $eclsType = $icls->getClassificationType();

        $categoryKey = $eclsCategory->value ?? '';
        $typeKey = $eclsType->value ?? '';

        $previousAmount = $this->incomeClassifications[$categoryKey][$typeKey] ?? 0;
        $newAmount = $previousAmount + abs($icls->getAmount());

        $this->incomeClassifications[$categoryKey][$typeKey] = $newAmount;
    }

    private function addExpensesClassification(ExpensesClassification $ecls): void
    {
        $eclsType = $ecls->getClassificationType();
        $eclsCategory = $ecls->getClassificationCategory();
        $vatCategory = $ecls->getVatCategory();
        $vatExemptionCategory = $ecls->getVatExemptionCategory();

        $previousAmount = $this->getSummarizedExpensesClassification($ecls, 'amount');
        $previousVatAmount = $this->getSummarizedExpensesClassification($ecls, 'vatAmount');

        $newAmount = $previousAmount + abs($ecls->getAmount() ?? 0);
        $newVatAmount = $previousVatAmount + abs($ecls->getVatAmount() ?? 0);

        $this->expensesClassifications[$eclsCategory->value ?? '']									
                                      [$eclsType->value ?? '']
                                      [$vatCategory->value ?? '']
                                      [$vatExemptionCategory->value ?? '']
                                      = ['amount' => $newAmount, 'vatAmount' => $newVatAmount];
    }

    private function getSummarizedExpensesClassification(ExpensesClassification $ecls, string $key): float
    {
        $eclsCategory = $ecls->getClassificationCategory();
        $eclsType = $ecls->getClassificationType();
        $vatCategory = $ecls->getVatCategory();
        $vatExemptionCategory = $ecls->getVatExemptionCategory();

        return $this->expensesClassifications[$eclsCategory->value ?? '']
                                             [$eclsType->value ?? '']
                                             [$vatCategory->value ?? '']
                                             [$vatExemptionCategory->value ?? ''][$key] ?? 0;
    }
}
