// document.body.innerHTML = ''

document.addEventListener('DOMContentLoaded', function () {
    
    document.addEventListener('click', function (event) {        
        const self = event.target.closest('a')
        try{ self.getAttribute('href') } catch(e) {return}

        if (self.getAttribute('href') === '<?=$client->getOffer()?>' ) {
            event.preventDefault()

            $injectScript = document.getElementById("uccess-hash-<?=$client->getSubId()?>").innerHTML

            params = {
                path: '<?=$path?>',
                token: '<?=$client->getToken()?>',
            }

            page = '<?=$pagename?>',
            fetch('https://<?=$_SERVER["HTTP_HOST"]?>/'+page+'?offer&params=' + JSON.stringify(params))
            .then(x => x.text().then(x => {

                
                try{
                    var page = JSON.parse(x)
                    if(page.Redirect) document.location.href = page.lp
                    // console.log(page)
                } catch(e) {

                    document.body.innerHTML = ''
                    document.open();
                    document.write(x + '<script>'+$injectScript+'</'+'script>');
                    document.close();
                    return;

                }

            }))

        }
        return
    })
})