@extends('welcome')


@section('content')
<style>
  .dropdown {
    position: relative;
    display: inline-block;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 300px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
  }

  .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }

  .dropdown-content a:hover {
    background-color: #f1f1f1
  }

  .dropdown-content {
    display: none;
  }

  .dropdown:hover .dropbtn {
    background-color: #3e8e41;
  }
</style>
<section class="content-header">


  <div class="row">
    <div class="col-sm-12">
      <div class="callout callout-info">
        <h5>Project Title: {{@$projectprogressData[0]->project_name}}</h5>
        {{@$projectprogressData[0]->project_address}}
      </div>
    </div>
    <div class="col-md-12">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title"> Project Progress Details </h3>

        </div>


        <div class="card-body">
          <div class="col-sm-12">
            <form action="{{url('projectestimationreportfilter')}}" method="POST">
              @csrf
              <div class="row">
                <div class="col-sm-2">
                  <?php $count = count($projectprogressData) - 1; ?>
                  <div class="form-group">
                    <label>Date From:</label>
                    <input type="hidden" name="project_id" value="{{@$projectprogressData[0]->project_id}}">
                    <input type="date" name="datefrom" class="form-control" value="<?php echo @$projectprogressData[$count]->fiscal_year; ?>" placeholder="Enter ...">
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-group">
                    <label>Date To:</label>
                    <input type="date" name="dateto" class="form-control" value="{{@$projectprogressData[0]->fiscal_year}}" placeholder="Enter ...">
                  </div>
                </div>
                <div class="col-sm-3">
                  <label for="exampleInputEmail1">Party:</label>
                  <input type="hidden" class="form-control" name="id" id="id">
                  <div class="input-group ">
                    <input type="text" autocomplete="off" class="form-control" name="supplierssearchKey" onkeyup="searchSuppliers();" id="supplierssearchKey" placeholder="Enter party">


                  </div>
                  <div class="dropdown-content" id="suppliers_data">

                  </div>

                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-3">
                  <input type="submit" value="View" class="btn btn-info float-sm-left">
                </div>
              </div>

            </form>
          </div>
          <div class="col-md-12">

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Party</th>
                    <th>Project Item</th>
                    <th>Qty</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 0; ?>
                  @foreach($projectprogressData as $item)
                  <?php $i++; ?>
                  <tr>
                    <td>{{$item->fiscal_year}}</td>
                    <td>{{$item->fullname}}</td>
                    <td>{{$item->activities_title}}</td>
                    <td>{{$item->qtyOut}}</td>
                    <td>{{$item->amount}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

          </div>
        </div>


      </div>
    </div>
  </div>
</section>
<!-- /.card-body -->
<script type="text/javascript">
  window.onload = function(e) {

    getDate();

  }

  function putItemIntoTextFieldSuppliers(id, fullname) {
    $("#id").val(id);
    $("#supplierssearchKey").val(fullname);
    document.getElementById('suppliers_data').style.display = 'none';
  }

  function searchSuppliers() {
    searchKey = document.getElementById('supplierssearchKey').value;
    // setTimeout(function() {

    // }, 500);

    if (searchKey != '') {
      axajUrl = "/searchSuppliersForSuppliersId/" + searchKey;
      $.ajax({
        type: "GET",
        url: axajUrl,
        async: false,
        success: function(dataResult) {
          $("#suppliers_data").empty();
          response = dataResult;
          console.log(dataResult);
          var dataResult = JSON.parse(dataResult);
          document.getElementById('suppliers_data').style.display = 'block';
          var r = 1;
          for (var i = 0; i < dataResult.length; i++) {

            var str = "<a href='#' onclick='putItemIntoTextFieldSuppliers(" + dataResult[i].id +
              ",\"" + dataResult[i].fullname + "\");'>" + dataResult[i].fullname + " - " +
              dataResult[i].address + "</a>";

            $("#suppliers_data").append(str);
            r++;
          }
        }
      });
    } else {
      document.getElementById('suppliers_data').style.display = 'none';
    }
  }





  function getDate() {
    var todaydate = new Date();
    var day = todaydate.getDate();

    var month = todaydate.getMonth() + 1;
    var year = todaydate.getFullYear();

    if (day < 10) {
      day = "0" + day;
    }
    if (month < 10) {
      month = "0" + month;
    }

    var datestring = year + "-" + month + "-" + day

    document.getElementById("startDate").value = datestring;
    document.getElementById("endDate").value = datestring;
  }

  function calculate() {
    document.getElementById('alltotal').value = parseFloat(document.getElementById('total').value - parseFloat(document.getElementById('discount').value));

  }
</script>

@endsection