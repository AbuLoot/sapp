<?php

namespace App\Traits;

use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\IncomingDoc;
use App\Models\OutgoingDoc;

trait GenerateDocNo {

    public function generateIncomingCashDocNo($cashbook_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingOrder::orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $cashbook_id.'/1';
            }
        }

        $existDoc = IncomingOrder::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateIncomingCashDocNo($cashbook_id, $docNo);
        }

        return $docNo;
    }

    public function generateOutgoingCashDocNo($cashbook_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = OutgoingOrder::orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $cashbook_id.'/1';
            }
        }

        $existDoc = OutgoingOrder::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateOutgoingCashDocNo($cashbook_id, $docNo);
        }

        return $docNo;
    }

    public function generateIncomingStoreDocNo($store_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = IncomingDoc::where('doc_no', 'like', $store_id.'/%')->orderByDesc('id')->first();

            if ($lastDoc && is_null($docNo)) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $store_id.'/1';
            }
        }

        $existDoc = IncomingDoc::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateIncomingStoreDocNo($store_id, $docNo);
        }

        return $docNo;
    }

    public function generateOutgoingStoreDocNo($store_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = OutgoingDoc::orderByDesc('id')->first();

            if ($lastDoc && is_null($docNo)) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } elseif (is_null($docNo)) {
                $docNo = $store_id.'/1';
            }
        }

        $existDoc = OutgoingDoc::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return self::generateOutgoingStoreDocNo($store_id, $docNo);
        }

        return $docNo;
    }
}
