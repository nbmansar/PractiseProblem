<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
    body{
        background: rgb(2,0,36);
background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(30,30,112,0.9809173669467787) 100%, rgba(255,0,0,1) 100%);
    }
    .box_content{
        width: 50%;
        min-height: 300px;
        border: 1px solid white;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px;
        background-color: wheat;    
    }
    .box_content .btn input{
        border-radius: 50px;
    }
    .get_result{
        width: 100%;
        min-height: 1000px;
        background-color: wheat;
    }
    input{
        
    }


</style>
<script>
    var zipvalue = '';
    document.addEventListener("DOMContentLoaded", () => {
});

let getDetails = ()=>{
    var i = 0;
    var values = document.getElementById('zipcode_value').value;
    const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    var parse_json = JSON.parse(this.responseText);
    console.log(parse_json); 
    Object.entries(parse_json).forEach((k,v) => {  
        Object.entries(`${v}`).forEach((ke,ev) => {  
            console.log(`${ke}`);
    })
})
//     var value = parse_json.results;
//     console.log(value); 
//     Object.entries(value).forEach((entry) => {
//   const [key, values] = entry;
//   var res_zip = `${key}`;
  
//   Object.entries(`${values}`).forEach((e) => {
//     const [k, v] = e;
//         console.log(`${v}`);
//   });
// });

  }
  xhttp.open("POST", "address_bc.php?zipcode="+values);
  xhttp.send();
}



</script>
<body>
    <div class="container ">
    <div class="box_content">
        <input type="text" id="zipcode_value" maxlength="5"><br>
        <button type="button" class="btn btn-success" id="get_zipcode" onclick="getDetails()">Get Details</button>
    </div>
    <div class="get_result hide" id="get_res" style="display:none;">
        
    </div>
    </div>
</body>
</html>