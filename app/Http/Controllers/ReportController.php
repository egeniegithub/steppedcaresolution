<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Period;
use App\Models\project;
use App\Models\StreamField;
use App\Models\StreamFieldGrid;
use Exception;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;

class ReportController extends Controller
{
    protected $projects_model;

    //
    function __construct()
    {
        $this->projects_model = new project;
    }

    public function index(Request $request)
    {
        $perPage = $request->show_rows ?? 10;
        $active_user = User::where('id', auth()->user()->id)->first();
        $project = $request->project_id ?? "all";
        $projects = $this->projects_model->search_Projects($project);
        if (!empty($request->period_id)) {
            $period_id = $request->period_id;
        } else {
            $current_period = Period::all()->filter(function ($item) {
                if (Carbon::now()->between($item->start_date, $item->end_date)) {
                    return $item;
                }
            })->first();

            if (!empty($current_period)) {
                $period_id = $current_period->id;
            } else {
                $period_id = null;
            }
        }
        if ($active_user->role != 'Admin'){
            $form_streams = Form::where('project_id', $active_user->project_id)
            ->where('period_id', $period_id)
            ->with(['streams'])
            ->paginate($perPage);
        }else{
            $form_streams = Form::when($projects, function ($query, $index) {
            $query->whereIn('project_id', $index);
        })
            ->where('period_id', $period_id)
            ->with(['streams'])
            ->paginate($perPage);
        }
        $row_show = $perPage;
        $periods = Period::all();
        $projects = project::all();
        return view("Reports.index")->with(compact('form_streams', 'row_show', 'projects', 'periods', 'active_user'));
    }

    public function pdfReport($form_id)
    {
        $form = Form::where('id', $form_id)->with(['streams'])->first();
        $html_content = view('Reports.partials.pdf_report', compact('form'))->render();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html_content);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream($form->name.".pdf");
        return back()->with('success', 'Report has been successfully generated.');
    }

    public function generateWordDoc($form_id)
    {
        $form = Form::where('id', $form_id)->with(['streams'])->first();
        $headers = array(
            "Content-type"=>"text/html",
            "Content-Disposition"=>"attachment;Filename=".$form->name.".doc"
        );
        $html_content = '<html><head><meta charset="utf-8"></head><body>';
        $html_content .= view('Reports.partials.pdf_report', compact('form'))->render();
        $html_content .= '</body></html>';

        return \Response::make($html_content,200, $headers);
    }
}
