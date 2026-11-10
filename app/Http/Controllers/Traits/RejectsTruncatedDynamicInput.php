<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait RejectsTruncatedDynamicInput
{
    protected function rejectMismatchedDynamicArrayLengths(
        Request $request,
        string $anchorField,
        array $parallelFields
    ): void {
        $anchor = $request->input($anchorField);
        if (!is_array($anchor) || count($anchor) === 0) {
            return;
        }

        $expectedCount = count($anchor);
        $errors = [];

        foreach ($parallelFields as $field) {
            $value = $request->input($field);
            if (!is_array($value) || count($value) === 0) {
                continue;
            }

            if (count($value) !== $expectedCount) {
                $errors[$field] = sprintf(
                    'Data detail tidak lengkap (%d dari %d baris). Silakan ulangi penyimpanan.',
                    count($value),
                    $expectedCount
                );
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    protected function rejectIncompleteNestedRows(
        Request $request,
        string $field,
        array $requiredFields
    ): void {
        $rows = $request->input($field);
        if (!is_array($rows) || $rows === []) {
            return;
        }

        $errors = [];
        foreach ($rows as $index => $row) {
            if (!is_array($row)) {
                $errors[$field . '.' . $index] = 'Format detail tidak valid.';
                continue;
            }

            foreach ($requiredFields as $requiredField) {
                if (!array_key_exists($requiredField, $row)
                    || $row[$requiredField] === null
                    || $row[$requiredField] === '') {
                    $errors[$field . '.' . $index . '.' . $requiredField] = 'Detail tidak lengkap. Silakan ulangi penyimpanan.';
                }
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }
}
