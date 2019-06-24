<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script language="JavaScript">
function ksort(obj){
  var keys = Object.keys(obj).sort()
    , sortedObj = {};

  for(var i in keys) {
    sortedObj[keys[i]] = obj[keys[i]];
  }

  return sortedObj;
}

// The API call path.
$path = 'https://mydigitalafterlife.cloudcheck.co.nz/verify/?';
$key = '8h5niUPxfevw6Vmc';
$secret = 'aAmUhFvXI1ROLuKxaHaMbyQhA4nFXnXE';
$nonce = '3';
$timestamp = Date.now();

 var $data = {details:{address:{suburb:"Hillsborough",street:"27 Indira Lane",postcode:"8022",city:"Christchurch"},name:{given:"Cooper",middle:"John",family:"Down"},dateofbirth:"1978-01-10"},consent:"Yes",callback:"http://localhost/signature/js.php"};
 $data = JSON.stringify($data)
console.log($data);
// Set up some dummy parameters. Sort alphabetically.
var $parameterMap = [];
$parameterMap['key'] = $key;
$parameterMap['nonce'] = $nonce;
$parameterMap['timestamp'] = $timestamp;
//$parameterMap['data'] = $data;



$parameterMap = ksort($parameterMap);
var $signatureString = $path;

Object.keys($parameterMap).forEach(function (item) {
    if (item === 'signature') {
        return;
    }
    $signatureString += item+'='+$parameterMap[item]+'&';
});

//console.log("String: "+$signatureString);

var $signatureHex = CryptoJS.HmacSHA256($signatureString, $secret);


var $url = $signatureString+'signature='+$signatureHex;
console.log("Signature: "+$url);
$(document).ready(function(e) {
    $.ajax({
        url: $url,
        type: 'POST',
        //data: $data,
        async: true,
        processData: true,
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Headers': 'Authorization',
            'Access-Control-Allow-Credentials': true,
            'Access-Control-Allow-Methods': 'GET, POST, OPTIONS, FETCH',
            "X-Requested-By": "user",
            'Content-Type':'application/x-www-form-urlencoded'
        },
        dataType: "jsonp",
        success: function(data){
            alert('true');
            console.log(data);
            //process the JSON data etc
        },
        error: function (x, y, z) {
                        alert(z);
                    }

});
})

/*var xhr = new XMLHttpRequest();
xhr.open('GET', $url);
xhr.send();*/
</script>