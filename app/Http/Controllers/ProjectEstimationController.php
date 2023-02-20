<?php

namespace App\Http\Controllers;

use App\Models\ProjectEstimation;
use App\Models\Project;
use App\Models\Activity;
use App\Models\ActivityCatagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\QueryException;

class ProjectEstimationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Project::select('*')->get();
        $activity = Activity::select('*')->get();

        //dd($activity);
        return view('frontend.projectestimation.add', compact('project', 'activity'));
    }


    public function checkthisprojecthasestimationrecordornot($project_id)
    {

        $data = ProjectEstimation::select('project_id')->where('project_id', $project_id)->get()->first();
        return json_encode($data);
    }
    public function progressIndex()
    {
        $project = Project::select('*')->get();
        $activity = Activity::select('*')->get();

        //dd($activity);
        return view('frontend.projectprogress.add', compact('project', 'activity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectEstimation  $projectEstimation
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectEstimation $projectEstimation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectEstimation  $projectEstimation
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectEstimation $projectEstimation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectEstimation  $projectEstimation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectEstimation $projectEstimation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectEstimation  $projectEstimation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectEstimation $projectEstimation)
    {
        //
    }
    function generateuniqueid()
    {
        $today = date('YmdHi');
        $startDate = date('YmdHi', strtotime('-10 days'));
        $range = $today - $startDate;
        $rand = rand(0, $range);
        $uniqueid = $startDate + $rand;
        $length = 20;
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tCode = substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
        $tCode = $tCode . $uniqueid;

        return $tCode;
    }
    function postProjectEstimationData(Request $request)
    {

        try {
            $tcode = $this->generateuniqueid();
            $tcode = $tcode . $request->project_id;
            $activities = $request->activities;
            $activities_array = explode(',', $activities);
            $convetDate = explode('-', $request->fiscal_year);
            $convetDate = $convetDate[0] . "/" . $convetDate[1] . "/" . $convetDate[2];
            //return $convetDate;
            $debit = 0;
            $amount = 0;
            $supplierid = null;



            for ($i = 0; $i < count($activities_array); $i++) {
                $val = explode('{#}', $activities_array[$i]);
                if ($val[2] != '') {
                    $debit = $val[2];
                } else {
                    $debit = 0;
                }
                if ($val[3] != '') {
                    $amount = $val[3];
                } else {
                    $amount = 0;
                }
                if ($val[4] != '') {
                    $supplierid = $val[4];
                }



                $projectactivity = new ProjectEstimation;
                $projectactivity->project_id = $request->project_id;
                $projectactivity->activities_id = $val[1];
                $projectactivity->quantity_in = $debit;
                $projectactivity->quantity_out = 0;
                $projectactivity->amount = $amount;
                $projectactivity->suppliers_id = $supplierid;

                $projectactivity->cancel = 0;
                $projectactivity->fiscal_year = $convetDate;
                $projectactivity->status = 0;
                $projectactivity->tCode = $tcode;
                $projectactivity->save();
            }


            return json_encode(array(
                'status' => true, 'message' => "Successfully done."
            ));
        } catch (QueryException $e) {
            return json_encode(array(
                'status' => false, 'message' => $e
            ));
        }
    }


    function updateProjectProgressData(Request $request)
    {

        try {

            ProjectEstimation::where('tCode', '=', $request->tcode)->update([
                'cancel' => 1,
            ]);
            $tcode = $request->tcode;
            $activities = $request->activities;
            $activities_array = explode(',', $activities);
            $convetDate = explode('-', $request->fiscal_year);
            $convetDate = $convetDate[0] . "/" . $convetDate[1] . "/" . $convetDate[2];
            //return $convetDate;
            $debit = 0;
            $amount = 0;

            $supplierid = null;

            for ($i = 0; $i < count($activities_array); $i++) {
                $val = explode('{#}', $activities_array[$i]);
                if ($val[2] != '') {
                    $credit = $val[2];
                } else {
                    $credit = 0;
                }
                if ($val[3] != '') {
                    $amount = $val[3];
                } else {
                    $amount = 0;
                }
                if ($val[4] != '') {
                    $supplierid = $val[4];
                }


                $projectactivity = new ProjectEstimation;
                $projectactivity->project_id = $request->project_id;
                $projectactivity->activities_id = $val[1];
                $projectactivity->quantity_in = 0;
                $projectactivity->quantity_out = $credit;
                $projectactivity->amount = $amount;
                $projectactivity->suppliers_id = $supplierid;

                $projectactivity->cancel = 0;
                $projectactivity->fiscal_year = $convetDate;
                $projectactivity->status = 0;
                $projectactivity->tCode = $tcode;
                $projectactivity->save();
            }


            return json_encode(array(
                'status' => true, 'message' => "Successfully done."
            ));
        } catch (QueryException $e) {
            return json_encode(array(
                'status' => false, 'message' => $e
            ));
        }
    }


    public function projectprogress($project_id, $projectName)
    {

        $estimationData = DB::table('project_estimations',)
            ->join('projects', 'project_estimations.project_id', '=', 'projects.id')
            ->join('activities', 'project_estimations.activities_id', '=', 'activities.id')
            ->select('projects.project_name', 'project_address', 'project_estimations.*', 'activities.*')
            ->where('project_estimations.cancel', '=', 0)
            ->where('project_estimations.quantity_out', '=', 0)
            ->where('project_estimations.project_id', '=', $project_id)
            ->get();

        $projectprogressData = \DB::select("SELECT  sum(quantity_out) qtyOut,activities_title,unit,sum(amount) as amount FROM `project_estimations` INNER join activities on project_estimations.activities_id=activities.id  WHERE project_id='" . $project_id . "' and cancel=0 and quantity_in=0 GROUP BY project_estimations.activities_id,activities_title,unit");
        return view('frontend.report.projectprogress', compact('estimationData', 'projectprogressData'));
    }
    function postProjectProgressData(Request $request)
    {

        try {
            $tcode = $this->generateuniqueid();
            $tcode = $tcode . $request->project_id;
            $activities = $request->activities;
            $activities_array = explode(',', $activities);
            $convetDate = explode('-', $request->fiscal_year);
            $convetDate = $convetDate[0] . "/" . $convetDate[1] . "/" . $convetDate[2];
            //return $convetDate;
            $debit = 0;
            $amount = 0;

            $supplierid = null;

            for ($i = 0; $i < count($activities_array); $i++) {
                $val = explode('{#}', $activities_array[$i]);
                if ($val[2] != '') {
                    $credit = $val[2];
                } else {
                    $credit = 0;
                }
                if ($val[3] != '') {
                    $amount = $val[3];
                } else {
                    $amount = 0;
                }
                if ($val[4] != '') {
                    $supplierid = $val[4];
                }


                $projectactivity = new ProjectEstimation;
                $projectactivity->project_id = $request->project_id;
                $projectactivity->activities_id = $val[1];
                $projectactivity->quantity_in = 0;
                $projectactivity->quantity_out = $credit;
                $projectactivity->amount = $amount;
                $projectactivity->suppliers_id = $supplierid;

                $projectactivity->cancel = 0;
                $projectactivity->fiscal_year = $convetDate;
                $projectactivity->status = 0;
                $projectactivity->tCode = $tcode;
                $projectactivity->save();
            }


            return json_encode(array(
                'status' => true, 'message' => "Successfully done."
            ));
        } catch (QueryException $e) {
            return json_encode(array(
                'status' => false, 'message' => $e
            ));
        }
    }
    public function getProjectEstimationData()
    {
        // $data = DB::table('project_estimations',)
        //     ->join('projects', 'project_estimations.project_id', '=', 'projects.id')
        //     ->select('projects.project_name', 'project_address')
        //     ->where('project_estimations.cancel', '=', 0)
        //     ->groupBy('project_estimations.project_id')
        //     ->get();

        $estimationData = \DB::select("select project_estimations.project_id,`projects`.`project_name`, `project_address`, sum(amount) as budget from `project_estimations` inner join `projects` on `project_estimations`.`project_id` = `projects`.`id` where `project_estimations`.`cancel` = 0  and quantity_out=0  group by `project_estimations`.`project_id`,project_name,project_address order by projects.created_at DESC");
        return view('frontend.projectestimation.list', compact('estimationData'));
    }
    public function getProjectProgressData()
    {

        $estimationData = \DB::select("select tCode, project_estimations.created_at, fiscal_year, project_estimations.project_id,`projects`.`project_name`, `project_address`, sum(amount) as budget,sum(quantity_out) as qty from `project_estimations` inner join `projects` on `project_estimations`.`project_id` = `projects`.`id` where `project_estimations`.`cancel` = 0  and quantity_in=0 group by `project_estimations`.`tCode`,project_name,project_address,project_id,fiscal_year,created_at order by projects.created_at DESC;");

        return view('frontend.projectprogress.list', compact('estimationData'));
    }
    public function deleteProjectEstimation($tCode)
    {

        ProjectEstimation::where('tCode', '=', $tCode)->update([
            'cancel' => 1,
        ]);

        return redirect('/projectestimation/list')->with('message', 'Your data  has been deleted successfully');
    }
    public function deleteprojectprogress($tCode)
    {

        ProjectEstimation::where('tCode', '=', $tCode)->update([
            'cancel' => 1,
        ]);

        return redirect('/projectprogress/list')->with('message', 'Your data  has been deleted successfully');
    }


    public function editProjectEstimation($tcode)
    {
        $activitiescatagories = ActivityCatagory::select('*')->get();

        $data = \DB::select("select project_estimations.*,activities.activities_title,projects.project_name,project_address from project_estimations  inner join  projects on project_estimations.project_id=projects.id inner join activities on  project_estimations.activities_id=activities.id where project_estimations.cancel=0 and  project_estimations.quantity_out=0 and  project_estimations.project_id='" . $tcode . "'");
        // dd($data);
        return view('frontend.projectestimation.edit', compact('data', 'activitiescatagories'));
    }

    public function editprojectprogress($tcode)
    {
        $activitiescatagories = ActivityCatagory::select('*')->get();

        $data = \DB::select("select project_estimations.*,activities.activities_title,projects.project_name,project_address from project_estimations  inner join  projects on project_estimations.project_id=projects.id inner join activities on  project_estimations.activities_id=activities.id where project_estimations.cancel=0 and   project_estimations.tCode='" . $tcode . "'");
        // dd($data);
        return view('frontend.projectprogress.edit', compact('data', 'activitiescatagories'));
    }


    function updateProjectEstimationData(Request $request)
    {


        try {
            ProjectEstimation::where('project_id', '=', $request->project_id)->where('quantity_out', '=', 0)->update([
                'cancel' => 1,
            ]);
            $tcode = $this->generateuniqueid();
            $tcode = $tcode . $request->project_id;
            $activities = $request->activities;
            $activities_array = explode(',', $activities);
            $convetDate = explode('-', $request->fiscal_year);
            $convetDate = $convetDate[0] . "/" . $convetDate[1] . "/" . $convetDate[2];
            //return $convetDate;
            $debit = 0;
            $amount = 0;
            $supplierid = null;



            for ($i = 0; $i < count($activities_array); $i++) {
                $val = explode('{#}', $activities_array[$i]);
                if ($val[2] != '') {
                    $debit = $val[2];
                } else {
                    $debit = 0;
                }
                if ($val[3] != '') {
                    $amount = $val[3];
                } else {
                    $amount = 0;
                }
                if ($val[4] != '') {
                    $supplierid = $val[4];
                }



                $projectactivity = new ProjectEstimation;
                $projectactivity->project_id = $request->project_id;
                $projectactivity->activities_id = $val[1];
                $projectactivity->quantity_in = $debit;
                $projectactivity->quantity_out = 0;
                $projectactivity->amount = $amount;
                $projectactivity->suppliers_id = $supplierid;

                $projectactivity->cancel = 0;
                $projectactivity->fiscal_year = $convetDate;
                $projectactivity->status = 0;
                $projectactivity->tCode = $tcode;
                $projectactivity->save();
            }



            return json_encode(array(
                'status' => true, 'message' => "Successfully done."
            ));
        } catch (QueryException $e) {
            return json_encode(array(
                'status' => false, 'message' => $e
            ));
        }
    }
    public function search(Request $request,)
    {

        $get_name = $request->project_id;

        $estimationData = DB::table('project_estimations',)
            ->join('projects', 'project_estimations.project_id', '=', 'projects.id')
            ->join('activities', 'project_estimations.activities_id', '=', 'activities.id')
            ->join('suppliers', 'project_estimations.suppliers_id', '=', 'suppliers.id')
            ->select('project_estimations.*', 'projects.project_name', 'activities.activities_title', 'suppliers.fullname')
            ->where('project_estimations.cancel', '=', 0)
            ->where('project_name', 'like', '%' . $get_name . '%')
            ->simplePaginate(10);


        return view('frontend.projectestimation.list', compact('estimationData'));
    }

    public function projectestimationreportfilter(Request $request)
    {

        $from = $request->datefrom;
        $to = $request->dateto;
        $sid = $request->id;
        $project_id = $request->project_id;


        // dd("SELECT  fullname, project_name,project_address, quantity_out as qtyOut,activities_title,unit,amount as amount,fiscal_year FROM `project_estimations` INNER join projects on project_estimations.project_id=projects.id  INNER join activities on project_estimations.activities_id=activities.id  left JOIN suppliers on project_estimations.suppliers_id=suppliers.id  WHERE project_id='" . $project_id . "' and fiscal_year between '" . $from . "' and '" . $to . "'  and cancel=0 and quantity_in=0 order by fiscal_year DESC ");
        if ($sid != "") {
            $projectprogressData = \DB::select("SELECT project_id, fullname, project_name,project_address, quantity_out as qtyOut,activities_title,unit,amount as amount,fiscal_year FROM `project_estimations` INNER join projects on project_estimations.project_id=projects.id  INNER join activities on project_estimations.activities_id=activities.id  left JOIN suppliers on project_estimations.suppliers_id=suppliers.id  WHERE project_id='" . $project_id . "' and fiscal_year between '" . $from . "' and '" . $to . "'  and cancel=0 and project_estimations.suppliers_id='" . $sid . "' and quantity_in=0 order by fiscal_year DESC ");
        } else {
            $projectprogressData = \DB::select("SELECT project_id,  fullname, project_name,project_address, quantity_out as qtyOut,activities_title,unit,amount as amount,fiscal_year FROM `project_estimations` INNER join projects on project_estimations.project_id=projects.id  INNER join activities on project_estimations.activities_id=activities.id  left JOIN suppliers on project_estimations.suppliers_id=suppliers.id  WHERE project_id='" . $project_id . "' and fiscal_year between '" . $from . "' and '" . $to . "'  and cancel=0 and quantity_in=0 order by fiscal_year DESC ");
        }



        // dd($projectprogressData);

        return view('frontend.report.projectestimation', compact('projectprogressData'));
    }

    public function estimationReport($project_id)
    {


        $projectprogressData = \DB::select("SELECT project_id, fullname, project_name,project_address, quantity_out as qtyOut,activities_title,unit,amount as amount,fiscal_year FROM `project_estimations` INNER join projects on project_estimations.project_id=projects.id  INNER join activities on project_estimations.activities_id=activities.id  left JOIN suppliers on project_estimations.suppliers_id=suppliers.id  WHERE project_id='" . $project_id . "' and cancel=0 and quantity_in=0 order by fiscal_year DESC ");



        return view('frontend.report.projectestimation', compact('projectprogressData'));
    }
    public function reportSearch(Request $request,)
    {

        $data = \DB::select("SELECT  sum(debit) as debit,sum(credit) as credit, project_name,project_id,fullname,qty FROM `project_activities` INNER JOIN projects on project_activities.project_id=projects.id INNER JOIN suppliers on project_activities.suppliers_id=suppliers.id  where project_activities.cancel=0 and project_id='" . $request->project_id . "' GROUP by project_id,project_name,fullname,qty;");



        return view('frontend.report.projectestimation', compact('estimationData'));
    }
}
