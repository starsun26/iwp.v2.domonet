document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('submit', function (event) {
        event.preventDefault();
        
        var $that   = event.target;
        var $inputs = $that.querySelectorAll('input');
        var $select = $that.querySelectorAll('select');
        var $btn    = $that.querySelectorAll('button');

        $btn[0].setAttribute('disabled', true)
        $btn[0].style.background = 'gray'

        params = {
            path: '<?=$path?>',
            pixels: '<?=$pixels?>',
            arb: '<?=$arbName?>',
            clickid: '<?=$client->getSubId()?>',
            landing: '<?=$land?>'
        }

        var isChcec = true
        $inputs.forEach(function(input) {

            if ( input.name === 'phone') { 
                var res = input.value.split('_').length > 1
                if (res) {
                    // console.log('res:', res)
                     
                    return isChcec = false
                }
            }

            // urlsuccess+= input.name + "=" + input.value + "&"
            params[input.name] = input.value

        })

        $select.forEach(function(select) {

            params[select.name] = select.value

        })

        pairs=[];
        for (var key in params) {
            if (params.hasOwnProperty(key)) {
                pairs.push(key+"="+encodeURIComponent(params[key]));
            }
        };
        var qs=pairs.join("&");
        
        var url = 'https://<?=$_SERVER["HTTP_HOST"]?>/successful.php?' + qs
        // console.log(url);return
        location.href = url


    })
})