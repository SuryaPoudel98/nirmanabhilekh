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
                <h5>Project Title: {{@$estimationData[0]->project_name}}</h5>
                {{@$estimationData[0]->project_address}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    Estimation
                </div>

                <div class="card-body">
                    <div class="col-md-12">


                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Project Item</th>
                                        <th>Qty</th>
                                        <th>Amount</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($estimationData as $item)
                                    <?php $i++; ?>
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$item->activities_title}}</td>
                                        <td>{{$item->quantity_in}}</td>
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
        <div class="col-sm-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    Progress
                </div>

                <div class="card-body">
                    <div class="col-md-12">

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>SN</th>
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
                                        <td>{{$i}}</td>
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
                <div class="card-footer">

                    <a href="/projectestimationreport/{{@$estimationData[0]->project_id}}"> <button type="submit" class="btn btn-default float-right">View Details</button></a>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    Progress Bar Graph
                </div>

                <div class="card-body">
                    <div class="col-md-12">


                        <div class="card-body table-responsive p-0">


                            <div class="skill-bars">
                                <div class="bar">

                                    <?php $qty = 0; ?>
                                    @foreach($estimationData as $item)
                                    <?php $qty = $item->quantity_in;
                                    $percentage = 1; ?>
                                    <div style="margin-top: 10px;">
                                        <div class="progress-line progress-1">
                                            <span style="width:100%!important">
                                                <p class="item-name">{{$item->activities_title}}</p>
                                                <div class="top-per"> 100%</div>
                                            </span>
                                        </div>

                                        @foreach($projectprogressData as $progressitem)

                                        @if($item->activities_title==$progressitem->activities_title)
                                        <?php $percentage = ($progressitem->qtyOut / $qty) * 100;
                                        $percentage = round($percentage, 2);
                                        ?>
                                        <div class="progress-line progress-2">
                                            <span style="width:{{$percentage}}%!important">
                                                <p class="item-name">{{$item->activities_title}}</p>
                                                <div class="top-per"> {{$percentage}}%</div>
                                            </span>
                                        </div>

                                        @else
                                        <?php $percentage = 1; ?>
                                        @endif
                                        @endforeach
                                        @endforeach

                                    </div>

                                </div>


                            </div>


                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>


</section>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');



    ::selection {
        color: #fff;
        background: #6665ee;
    }

    .skill-bars {
        /* padding: 25px 30px; */
        padding-top: 35px;
        padding-bottom: 25px;
        padding-right: 10px;
        width: auto;
        background: #fff;
        margin-top: 10px;
        margin-left: 10px;

    }

    .progress-line span .top-per {
        position: absolute;
        top: -28px;
        right: 0;
        font-weight: 500;
        background: #000;
        color: #fff;
        padding: 1px 8px;
        font-size: 12px;
        border-radius: 3px;
        opacity: 0;
        animation: showText2 0.5s 1.5s linear forwards;
    }

    .skill-bars .bar {
        margin: 20px 0;
    }

    .skill-bars .bar:first-child {
        margin-top: 0px;
    }

    .skill-bars .bar .info {
        margin-bottom: 5px;
    }

    .skill-bars .bar .info span {
        font-weight: 500;
        font-size: 17px;
        opacity: 0;
        animation: showText 0.5s 1s linear forwards;
    }

    @keyframes showText {
        100% {
            opacity: 1;
        }
    }

    .skill-bars .bar .progress-line {
        height: 45px;
        width: 100%;
        background: #f0f0f0;
        position: relative;
        transform: scaleX(0);
        transform-origin: left;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05), 0 1px rgba(255, 255, 255, 0.8);
        animation: animate 1s cubic-bezier(1, 0, 0.5, 1) forwards;
    }

    @keyframes animate {
        100% {
            transform: scaleX(1);
        }
    }

    .bar .progress-line span {
        height: 100%;
        position: absolute;
        transform: scaleX(0);
        transform-origin: left;
        animation: animate 1s 1s cubic-bezier(1, 0, 0.5, 1) forwards;
    }

    .bar .progress-line.progress-1 span {
        width: 90%;
        background: #216e8f;
    }

    .bar .progress-line span p {
        color: white;
        padding-left: 10px;
        padding-top: 10px;
    }

    .bar .progress-line.progress-2 span {
        width: 50%;
        background: #55ab87;
    }

    .progress-line span::before {
        position: absolute;
        content: "";
        top: -10px;
        right: 0;
        height: 0;
        width: 0;
        border: 7px solid transparent;
        border-bottom-width: 0px;
        border-right-width: 0px;
        border-top-color: #000;
        opacity: 0;
        animation: showText2 0.5s 1.5s linear forwards;
    }

   
    @keyframes showText2 {
        100% {
            opacity: 1;
        }
    }

   
</style>


<!-- Modal -->

<script type="text/javascript">
    window.onload = function(e) {

        getDate();

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
    let searchKey = '';

    function searchCustomer() {
        searchKey = document.getElementById('searchKey').value;
        // setTimeout(function() {

        // }, 500);

        if (searchKey != '') {
            axajUrl = "/searchCustomerForCustomerId/" + searchKey;
            $.ajax({
                type: "GET",
                url: axajUrl,
                async: false,
                success: function(dataResult) {
                    $("#customers_data").empty();
                    response = dataResult;
                    console.log(dataResult);
                    var dataResult = JSON.parse(dataResult);
                    document.getElementById('customers_data').style.display = 'block';
                    var r = 1;
                    for (var i = 0; i < dataResult.length; i++) {

                        var str = "<a href='#' onclick='putItemIntoTextField(" + dataResult[i].id + ",\"" + dataResult[i].customer_name + "\");'>" + dataResult[i].customer_name + " - " + dataResult[i].customer_phonenumber + "</a>";

                        $("#customers_data").append(str);
                        r++;
                    }
                }
            });
        } else {
            document.getElementById('customers_data').style.display = 'none';
        }



    }

    function searchEquipment() {
        var searchEquipment = document.getElementById('EquipmentName').value;
        // setTimeout(function() {

        // }, 500);

        if (searchEquipment != '') {
            axajUrl = "/searchEquipmentForEquipmentId/" + searchEquipment;
            $.ajax({
                type: "GET",
                url: axajUrl,
                async: false,
                success: function(dataResult) {
                    $("#equipments_data").empty();
                    response = dataResult;
                    console.log(dataResult);
                    var dataResult = JSON.parse(dataResult);
                    document.getElementById('equipments_data').style.display = 'block';
                    var r = 1;
                    for (var i = 0; i < dataResult.length; i++) {

                        var str = "<a href='#' onclick='putItemIntoTextFieldFromEquipment(" + dataResult[i].id + ",\"" + dataResult[i].equipment_name + "\");'>" + dataResult[i].equipment_name + " - " + dataResult[i].purchase_rate + "</a>";

                        $("#equipments_data").append(str);
                        r++;
                    }
                }
            });
        } else {
            document.getElementById('equipments_data').style.display = 'none';
        }



    }


    function putItemIntoTextFieldFromEquipment(id, equipmentsName, purchaseRate) {
        $("#equipment_id").val(id);
        $("#EquipmentName").val(equipmentsName);
        $("#purchaseRate").val(purchaseRate);
        document.getElementById('equipments_data').style.display = 'none';
    }

    function putItemIntoTextField(id, customerName) {
        $("#customer_id").val(id);
        $("#searchKey").val(customerName);
        document.getElementById('customers_data').style.display = 'none';
    }

    function printData() {
        var divToPrint = document.getElementById("mytable");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>

@endsection