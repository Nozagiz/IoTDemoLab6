<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&display=swap" rel="stylesheet">
    <link rel="icon" href="weather.png" type="image/icon type">
    <title>Weather Station</title>
</head>
<style>
    .header {
        background: #6007c5;
        color: white;
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .card {
        margin-top: 4%;
        height: 140px;
        width: 100%;
        border-radius: 10px;
        color: rgb(15, 174, 248);
        justify-content: center;
        border: rgb(0, 68, 255);
    }
    .card:hover {
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        transition: 0.6s;
    }
    .card-temp {
        height: 140px;
        font-size: 20px;
        width: 100%;
        padding: 20px;
        background: rgb(255, 174, 22);
        border-radius: 10px;
    }
    .card-humidity {
        height: 140px;
        font-size: 20px;
        width: 100%;
        padding: 20px;
        background: rgb(48, 135, 248);
        border-radius: 10px;
    }

    .led_status {
        height: 140px;
        font-size: 20px;
        width: 100%;
        padding: 20px;
        background: rgb(255, 47, 47);
        border-radius: 10px;
    }
    span {
        font-family: 'IBM Plex Sans Thai', sans-serif;
        font-weight: bold;
        color: white;
    }

</style>
<!-- background-image: url('23284.jpg'); background-repeat: no-repeat; background-size: cover; -->
<body style=" background-image: url('23284.jpg'); background-repeat: no-repeat; background-size: cover;">
    <div class="container">
        <!--header-->
        <div class="header" style="margin-bottom: 8px; height: 100%; border-radius: 0px 0px 10px 10px; "> 
            <div class="row">
            <nav>
                    <div class="container">
                        <div class="col-md-12" style="margin-top: 20px;margin-bottom: 20px;">
                            <div style="padding-top: 5px;">
                                    <a class="navbar-brand" href="#">
                                        <img src="weather.png" width="70" height="auto" class="d-inline-block ">
                                        <span style="padding-left: 10px; font-size: 5vh ;">My IoT Data</span></a>
                                        <span id="date_time" style="font-size: 17px; float: right;"></span>
                                        <span style="float: right; padding-right: 10px;">อัปเดตเมื่อ</span>
                            </div>
                        </div>   
                    </div>
                
            </nav></div>
        </div>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-humidity" >
                        <span>ความชื้นในอากาศ</span><br>
                        <span style="font-size: 30px;" id="humidity_data"></span><span style="font-size: 30px;">
                            %</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" >
                    <div class="card-temp" >
                        <span>อุณหภูมิ</span><br>
                        <span style="font-size: 30px;" id="temp_data"></span><span style="font-size: 30px;"> ํC</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" id="state_card" >
                    <a id="state" href="" style="text-decoration: none;">
                        <div class="led_status" >
                            <span>สถานะไฟ LED</span><br>
                            <span style="font-size: 30px;" id="LED_DATA"></span><span style="font-size: 30px;">
                                </span>
                        <!-- </div> -->
                    <!-- </a> -->
                </div>
            </div>
        </div>
        <div class="row" style="background-color: white; border-radius: 10px; margin: 1px; margin-top: 20px;">
            <div class="col-md-6"> 
                <iframe id="hum_graph" width="450px" height="260px" src="" style="float: right;"></iframe>
            </div>
            <div class="col-md-6"> 
                <iframe id="temp_graph" width="450px" height="260px" src=""></iframe>
            </div>
        </div>
    </div>
</body>
<script>
    // var locationId = <?php echo $_GET["id"]?>;
    function loaddata() {
        //  $("#graph").hide();
        var url = "https://api.thingspeak.com/channels/1651582/feeds.json?results=1";
        $.getJSON(url)
            .done((data) => {
                console.log(data)
                var humidity = data.feeds[0].field1;
                var hum_data = parseInt(humidity);
                var temp = data.feeds[0].field2;
                var temp_data = parseFloat(temp).toFixed(2);
                var led_state = data.feeds[0].field3;
                var datetime = data.feeds[0].created_at;
                $("#temp_data").text(temp_data);
                $("#humidity_data").text(hum_data);
                changeDate(datetime);
                changeState(led_state);
            }, 3000).fail((xhr, status, err) => {
                console.log("error")
            })
    }
        function changeDate(d_time){
            let date = new Date(d_time);
            let year = date.getFullYear();
            let months = date.getMonth();
            let realMonth = month[months];
            let day = date.getDate();
            let hours = date.getHours();
            let minutes = "0" + date.getMinutes();
            let seconds = "0" + date.getSeconds();
            let formattedTime = day+ ' ' + realMonth + ' ' + year + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
                $("#date_time").text(formattedTime);
        }
        function changeState(leds){
            if(leds == 0){
                $("#LED_DATA").text("LED OFF");
                $("#state").click(()=>{
                    $.post("https://api.thingspeak.com/update?api_key=CGYEVM855FXHO6H0&field3=1");
                });
                document.getElementById("state_card").style.background = '#FFFFF';
            }else if(leds == 1){
                $("#LED_DATA").text("LED ON");
                $("#state").click(()=>{
                    $.post("https://api.thingspeak.com/update?api_key=CGYEVM855FXHO6H0&field3=0");
                });
            }
        }
        const month = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"]
        function loadAll(){
            loaddata();
            setTimeout(loadAll,60000);
        }
    $(() => {
        loadAll();
        $("#hum_graph").attr("src", "https://api.thingspeak.com/channels/1651582/charts/1");
        $("#temp_graph").attr("src", "https://api.thingspeak.com/channels/1651582/charts/2");
    })
</script>

</html>
