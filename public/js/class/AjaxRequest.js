class AjaxRequest {

    processAjax = (formdata , route, format = 'json') =>{
        const data = new FormData(formdata);
        const xhr = new XMLHttpRequest();
        const phproute = route;
        const responseFormat = format;


        return new Promise((resolve , reject)=>{
            xhr.open('POST', phproute, true);
            xhr.send(data);
                xhr.onreadystatechange = () => {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        if(responseFormat==='Twig'){
                            resolve(xhr.responseText) 
                        }else{
                            resolve(xhr.response)
                        }
                    }
                }
        })
    }

    ex = async (formdata , route) => {
        let response = await this.processAjax(formdata , route).then(JSON.parse)
        return response
    }
}

export { AjaxRequest }