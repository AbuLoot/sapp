<?php

namespace App\Traits;

use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\IncomingDoc;
use App\Models\OutgoingDoc;

trait GenerateDocNo {

    public function generateIncomingCashDocNo($cashbookId, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingOrder::where('doc_no', 'like', $cashbookId.'/%')->orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $cashbookId.'/1';
            }
        }

        $existDoc = IncomingOrder::where('doc_no', $docNo)->first();

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

            $lastDoc = OutgoingOrder::where('doc_no', 'like', $cashbookId.'/%')->orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $cashbookId.'/1';
            }
        }

        $existDoc = OutgoingOrder::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateOutgoingCashDocNo($cashbookId, $docNo);
        }

        return $docNo;
    }

    public function generateIncomingStoreDocNo($storeId, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingDoc::where('doc_no', 'like', $storeId.'/%')->orderByDesc('id')->first();

            if ($lastDoc && is_null($docNo)) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $storeId.'/1';
            }
        }

        $existDoc = IncomingDoc::where('doc_no', $docNo)->first();

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

            $lastDoc = OutgoingDoc::where('doc_no', 'like', $storeId.'/%')->orderByDesc('id')->first();

            if ($lastDoc && is_null($docNo)) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $storeId.'/1';
            }
        }

        $existDoc = OutgoingDoc::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateOutgoingStoreDocNo($storeId, $docNo);
        }

        return $docNo;
    }
}
