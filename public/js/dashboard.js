let twigContainer = null

function opSelect(item , id){
    const scrapitem = item;
    if(!scrapitem.classList.contains('selected')){
        twigContainer = item.children[2].children[1]
        const items = document.getElementsByClassName('scrapitem')
        for (let item of items){
            item.classList.remove('selected')
        }
        scrapitem.classList.add('selected')
        
        ajax('twigOptions' ,null, {'ID': id} , (xhr)=>{
            twigContainer.innerHTML = xhr.responseText
        })
    }
}

function newScrap(){
    
    ajax('newScrap' ,null, null , (xhr)=>{
        ajax('scraplist' ,null, null , (xhr)=>{
            twigContainer = document.getElementById('scrapitems-container')
            twigContainer.innerHTML = xhr.responseText
        })
    })
}

function opCollect(item , id){
    selectOption(item)
    
    ajax('window-collect' , null, {'ID': id} , (xhr)=>{
        twigContainer = document.getElementById('right-window')
        twigContainer.innerHTML = xhr.responseText
    })
}
function opSee(item , id){
    selectOption(item)
    see(id)
}
function opEdit(item , id){
    selectOption(item)
    
    ajax('window-edit' , null ,{'ID': id} , (xhr)=>{
        twigContainer = document.getElementById('right-window')
        twigContainer.innerHTML = xhr.responseText
        ajax('selectorlist' , null, {'ID': id} , (xhr)=>{
            twigContainer = document.getElementById('element-box-container')
            twigContainer.innerHTML = xhr.responseText
        })
    })
}
function opSave(item , id){
    selectOption(item)

    ajax('window-save' , null ,{'ID': id} , (xhr)=>{
        twigContainer = document.getElementById('right-window')
        twigContainer.innerHTML = xhr.responseText
    })
}
function opDelete(item , id){
    selectOption(item)

    ajax('window-del' , null, {'ID': id} , (xhr)=>{
        twigContainer = document.getElementById('right-window')
        twigContainer.innerHTML = xhr.responseText
    })
}

function selectOption(item){
    const optionBtn = item;
    unselectOptions()
    if(!optionBtn.classList.contains('selected')){
        optionBtn.classList.add('selected')
    }
}

function unselectOptions(){
    const items = document.getElementsByClassName('option-btn')
    for (let item of items){
        item.classList.remove('selected')
    }
}

function see(id){
    unselectOptions()
    const optionBtn = document.getElementById('option-see');
    optionBtn.classList.add('selected')
    ajax('seeScrap' ,null,{'ID': id} , (xhr)=>{
        twigContainer = document.getElementById('right-window')
        twigContainer.innerHTML = xhr.responseText
        ajax('seeScrappoints' ,null,{'ID': id} , (xhr)=>{
            twigContainer = document.getElementById('scrap-view-window-inner')
            twigContainer.innerHTML = xhr.responseText
        })
    })
}
function delScrap(id){
    ajax('delScrap' , null,{'ID': id} , (xhr)=>{
        ajax('scraplist' , null, null , (xhr)=>{
            twigContainer = document.getElementById('scrapitems-container')
            twigContainer.innerHTML = xhr.responseText
            ajax('logo' , null, null , (xhr)=>{
                twigContainer = document.getElementById('right-window')
                twigContainer.innerHTML = xhr.responseText
            })
        })
    })
}


function ajax (route , form = null , data = null , callback = null){
    let formdata
    if (form){
        formdata = new FormData(form);
    }else{
        formdata = new FormData()
    }
    if(data){
        for (const [key, value] of Object.entries(data)) {
            formdata.append(key, value);
          }
    }
    const xhr = new XMLHttpRequest();
    const phproute = route;

    xhr.open('POST', phproute, true);
    xhr.send(formdata);
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if(callback !== null){
                callback(xhr)
            }
        }
    }
}


function newSelector(id){
    
    ajax('newSelector' ,null, {'ID': id} , (xhr)=>{
        ajax('selectorlist' ,null, {'ID': id} , (xhr)=>{
            twigContainer = document.getElementById('element-box-container')
            twigContainer.innerHTML = xhr.responseText
        })
    })
}
function delSelector(id , scrapID){
    
    ajax('delSelector' ,null, {'ID': id} , (xhr)=>{
        ajax('selectorlist' ,null, {'ID': scrapID} , (xhr)=>{
            twigContainer = document.getElementById('element-box-container')
            twigContainer.innerHTML = xhr.responseText
        })
    })
}

function validateEdit (id){
    const params = document.getElementById('params')
    ajax('validparams' , params, {'ID': id}, (xhr)=>{
        ajax('scraplist' , null, null , (xhr)=>{
            twigContainer = document.getElementById('scrapitems-container')
            twigContainer.innerHTML = xhr.responseText
        })
        selectors = document.getElementsByClassName('element-box');
        for (let i = 0; i < selectors.length; i++) {
            let selector = selectors[i];
            let selectorid = selector.getAttribute("data-index")
            ajax('updateSelector' ,selector, {'ID': selectorid} , (xhr)=>{
                if(i === selectors[i]-1){
                    ajax('selectorlist' ,null, {'ID': id} , (xhr)=>{
                        twigContainer = document.getElementById('element-box-container')
                        twigContainer.innerHTML = xhr.responseText
                    })
                }
            })
        }
    })
}

function launchScrap(id){
    ajax('scrapExec' ,null, {'ID': id} , (xhr)=>{
        const dateID = xhr.responseText
    })
}

function clickDateLine(dateLine , id){

    if(!dateLine.classList.contains('selected')){

        const lines = document.getElementsByClassName('view-datebox')
        for (let i = 0; i < lines.length ; i++) {
            lines[i].classList.remove('selected')
        }
        dateLine.classList.add('selected')
        ajax('loadsingles' ,null, {'ID': id} , (xhr)=>{
            twigContainer = document.querySelector(".view-datebox.selected > .datebox-line-content")
            twigContainer.innerHTML = xhr.responseText
            twigContainer = dateLine.children.item(2);
            const elements = dateLine.children.item(2).children;
            for(let i = 0 ; i<elements.length ; i++){
                let singleID = elements[i].getAttribute("data-id")
                ajax('getElements' ,null, {'ID': singleID} , (xhr)=>{
                    data=xhr.response
                    ajax('loadElements' ,null, {'ID': singleID , 'Elements' : data} , (xhr)=>{
                        console.log(elements[i].children)
                        twigContainer = elements[i].children.item(1)
                        twigContainer.innerHTML = xhr.responseText
                    })
                })
            }
        })
    }
}

function delSingle(obj , id){
    ajax('deletesingle' ,null, {'ID': id} , (xhr)=>{
        obj.parentNode.parentNode.removeChild(obj);
    })
}