<?php

use App\Models\NepaliCurrancyFormat;

$customers = \DB::select("select count(id) as id from customers");
$project = \DB::select("select count(id) as id from projects");
$data = \DB::select("select * from projects where projects.status = 0");
$currancyformat = new NepaliCurrancyFormat();
?>

<!DOCTYPE html>
<html lang="en">
@include('frontend.include.header')

<body class="hold-transition sidebar-mini">
  <div class="wrapper">



    @include('frontend.include.nav')

    @include('frontend.include.bottomheader')

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
         

            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">


              </ol>
            </div>
          </div>
        </div>
      </div>


      <section class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-3 col-6">

              <div class="small-box bg-info">
                <div class="inner">
                  <h1 id="total-count">
                    <tbody>
                      @if(!empty($serviceBillAmount))
                      <?php $i = 0;

                      $alltotalamount = 0;
                      ?>
                      @foreach($serviceBillAmount as $item)
                      <?php $i++;

                      $alltotalamount += $item->alltotalamount;
                      ?>
                      <tr>






                      </tr>
                      @endforeach
                      <tr>


                        <th>Rs. {{ $currancyformat->moneyFormatIndia($alltotalamount) }} </th>
                      </tr>
                      @endif
                    </tbody>
                    </tbody>
                  </h1>
                  <p>Service Sales</p>
                </div>
                <div class="icon">
                  
                </div>
                <a href="/customerwise" class="small-box-footer">Total Sales &nbsp;<i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h1 id="total-count">
                    <h3>
                      <tbody>
                        @if(!empty($Invoice))
                        <?php $i = 0;
                        $totaldebit = 0;
                        $totalcredit = 0;
                        $profit = 0;

                        ?>
                        @foreach($Invoice as $projectactivity)

                        <?php $i++;

                        $totalcredit += $projectactivity->credit;
                        $totaldebit += $projectactivity->debit;
                        $profit = $totaldebit - $totalcredit;
                        ?>
                        <tr>


                          @endforeach





                        </tr>

                        <th>Rs. {{$currancyformat->moneyFormatIndia( $profit )}} </th>
                        @endif
                      </tbody>
                    </h3>
                    <p>Project Profit</p>
                  </h1>
                </div>
                
                <a href="/projectactivitiesreport" class="small-box-footer">All Projects Records &nbsp;<i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-gray">
                <div class="inner">
                  <h3>
                    {{$customers[0]->id}}
                  </h3>

                  <p>Customers</p>
                </div>
                
                <a href="/customer/add" class="small-box-footer"> Add New Customer  <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">

              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>

                    {{$project[0]->id}}
                  </h3>

                  <p> Projects</p>
                </div>
                
                <a href="/project/list" class="small-box-footer"> Project list <i style="color:white;" class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-9 col-6">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"> Project List </h3>

                </div>
                <style>
                  .button {
                    float: left;
                    margin-left: 10px;
                  }
                </style>
                <!-- /.card-header -->
                <div class="card-body">

                  <table class="table">
                    <thead>
                      <tr>
                        <th>S.N.</th>
                        <th>Project Name</th>
                        <th></th>
                        <!-- <th></th> -->
                      </tr>
                    </thead>
                    <tbody>
                      </thead>
                    <tbody>

                      @foreach($data as $project)
                      <tr>

                        <td>{{ $project ->id }}</td>
                        <td> {{ $project ->project_name }}</td>
                        <td>
                          <div>
                            <div>
                              <a style="color:black;" href="{{url('/singleprojectdetails/' .$project->id.'/'.$project->project_name )}}"> <button name="submit" type="submit" value="Save" class="button  btn-info" style="background-color: #115a85; width:80px; color:white; padding:5px; font-size:13px;  border-radius:5px; border:none; margin-bottom:2px;"> Summary </button></a>
                              <a style="color:black;" href="{{url('/projectprogress/' .$project->id.'/'.$project->project_name )}}"> <button name="submit" type="submit" value="Cancel" class="button  btn-success" style="background-color: #b8600b; width:80px; color:white; padding:5px; font-size:13px;  border-radius:5px; border:none;">Progress</button></a>

                            </div>
                          </div>
                        </td>

                      </tr>
                      @endforeach

                    </tbody>
                  </table>
                </div>

              </div>
            </div>
            <div class="col-lg-3 col-6">

              <div class="small-box bg-secondary">
                <div class="inner">
                  <h3>
                    <tbody>
                      @if(!empty($Income))
                      <?php $i = 0;
                      $amounttotal = 0;
                      ?>
                      @foreach($Income as $item)
                      <?php $i++;
                      $amounttotal += $item->amount;

                      ?>
                      <tr>




                      </tr>

                      </tr>
                      @endforeach
                      <tr>

                        <th>Rs. {{$currancyformat->moneyFormatIndia( $amounttotal )}}</th>

                    </tbody>
                    @endif
                  </h3>

                  <p>Equipment Income</p>
                </div>
           
                <a href="/showincomeequipment" class="small-box-footer">Equipment Income List <i style="color:white;" class="fas fa-arrow-circle-right"></i></a>
              </div>

              <div class="small-box bg-info">
                <div class="inner">
                  <h3>
                    <tbody>
                      @if(!empty($Expenses))
                      <?php $i = 0;
                      $amounttotal = 0;
                      ?>
                      @foreach($Expenses as $item)
                      <?php $i++;
                      $amounttotal += $item->amount;

                      ?>
                      <tr>




                      </tr>

                      </tr>
                      @endforeach
                      <tr>

                        <th>Rs. {{$currancyformat->moneyFormatIndia( $amounttotal) }}</th>

                    </tbody>
                    @endif
                  </h3>

                  <p>Equipment Expense</p>
                </div>
              
                <a href="/showexpensesequipment" class="small-box-footer">Equipment Expenses List <i style="color:white;" class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

          </div>

        </div>

    </div>


    </section>




    @include('frontend.include.footer')




    <script src="{{asset('Adminpanel/dist/js/adminlte.min.js')}}"></script>




    <script src="{{ asset('site/js/jquery.js') }}"></script>
    <script src="{{ asset('site/js/app.js') }}"></script>

    <script src="{{ asset('site/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('site/fontawesome/js/all.js') }}"></script>
    <script src="{{ asset('site/js/script.js') }}"></script>




</body>

</html>