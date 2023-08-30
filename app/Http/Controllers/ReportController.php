<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportLabel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getLabels()
    {
        return response()->json(ReportLabel::get());
    }

    public function submitReport(Request $request)
    {
        $reason_id = $request->get('reason_id');
        $comments = $request->get('comments');
        $listing_uuid = $request->get('listing_uuid');

        $report = new Report;
        $report->listing_uuid = $listing_uuid;
        $report->reason_label_id = $reason_id;
        $report->comments = $comments;
        $report->save();
    }
}
