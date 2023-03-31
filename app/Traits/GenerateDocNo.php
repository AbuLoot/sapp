<?php

namespace App\Traits;

use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\IncomingDoc;
use App\Models\OutgoingDoc;

trait GenerateDocNo {

    public function generateIncomingStoreDocNo($storeId, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingDoc::where('company_id', session('company')->id)->where('doc_no', 'like', $storeId.'/%')->first();

            if ($lastDoc && is_null($docNo)) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $storeId.'/1';
            }
        }

        $existDoc = IncomingDoc::where('company_id', session('company')->id)->where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateIncomingStoreDocNo($storeId, $docNo);
        }

        return $docNo;
    }

    public function generateOutgoingStoreDocNo($storeId, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = OutgoingDoc::where('company_id', session('company')->id)->where('doc_no', 'like', $storeId.'/%')->first();

            if ($lastDoc && is_null($docNo)) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $storeId.'/1';
            }
        }

        $existDoc = OutgoingDoc::where('company_id', session('company')->id)->where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateOutgoingStoreDocNo($storeId, $docNo);
        }

        return $docNo;
    }

    public function generateIncomingCashDocNo($cashbookId, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingOrder::where('company_id', session('company')->id)->where('doc_no', 'like', $cashbookId.'/%')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $cashbookId.'/1';
            }
        }

        $existDoc = IncomingOrder::where('company_id', session('company')->id)->where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateIncomingCashDocNo($cashbookId, $docNo);
        }

        return $docNo;
    }

    public function generateOutgoingCashDocNo($cashbookId, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = OutgoingOrder::where('company_id', session('company')->id)->where('doc_no', 'like', $cashbookId.'/%')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $cashbookId.'/1';
            }
        }

        $existDoc = OutgoingOrder::where('company_id', session('company')->id)->where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateOutgoingCashDocNo($cashbookId, $docNo);
        }

        return $docNo;
    }

    /*public function generateOldBarcode($index)
    {
        $firstCode = '200'; // 200-299

        $companyId = (is_numeric($this->product->company_id)) ? $this->product->company_id : '0000';
        $secondCode = substr(sprintf("%'.04d", $companyId), -4);

        $lastSeconds = substr(intval(microtime(true)), -3);
        $thirdCode = $lastSeconds.$index;

        $fourthCode = substr(sprintf("%'.02d", $index + 1), -2);

        $barcode = $firstCode.$secondCode.$thirdCode.$fourthCode;
        $sameProduct = Product::whereJsonContains('barcodes', $barcode)->first();

        if (in_array($barcode, $this->productBarcodes) || $sameProduct) {
            $firstCode += ($firstCode == '299') ? -98 : 1;
            $thirdCode + 1;
            $fourthCode = substr(sprintf("%'.02d", $fourthCode + 1), -2);
            $barcode = $firstCode.$secondCode.$thirdCode.$fourthCode;
        }

        $this->productBarcodes[$index] = $barcode;

        return $barcode;
    }*/
}
