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
        
        ajax('twigOptions' ,{'ID': id} , (xhr)=>{
            twigContainer.innerHTML = xhr.responseText
        })
    }
}

function newScrap(){
    
    ajax('newScrap' , null , (xhr)=>{
        ajax('scraplist' , null , (xhr)=>{
            twigContainer = document.getElementById('scrapitems-container')
            twigContainer.innerHTML = xhr.responseText
        })
    })
}

function opCollect(item , id){
    selectOption(item)
    
    ajax('window-collect' ,{'ID': id} , (xhr)=>{
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
    
    ajax('window-edit' ,{'ID': id} , (xhr)=>{
        twigContainer = document.getElementById('right-window')
        twigContainer.innerHTML = xhr.responseText
        ajax('selectorlist' , {'ID': id} , (xhr)=>{
            twigContainer = document.getElementById('element-box-container')
            twigContainer.innerHTML = xhr.responseText
        })
    })
}
function opSave(item , id){
    selectOption(item)

    ajax('window-save' ,{'ID': id} , (xhr)=>{
        twigContainer = document.getElementById('right-window')
        twigContainer.innerHTML = xhr.responseText
    })
}
function opDelete(item , id){
    selectOption(item)

    ajax('window-del' ,{'ID': id} , (xhr)=>{
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
    ajax('seeScrap' ,{'ID': id} , (xhr)=>{
        twigContainer = document.getElementById('right-window')
        twigContainer.innerHTML = xhr.responseText
    })
}
function delScrap(id){
    ajax('delScrap' ,{'ID': id} , (xhr)=>{
        ajax('scraplist' , null , (xhr)=>{
            twigContainer = document.getElementById('scrapitems-container')
            twigContainer.innerHTML = xhr.responseText
            ajax('logo' ,null , (xhr)=>{
                twigContainer = document.getElementById('right-window')
                twigContainer.innerHTML = xhr.responseText
            })
        })
    })
}

function ajax (route , data = null , callback = null){
    const formdata = new FormData();
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
function ajaxform (route , form = null , data = null , callback = null){
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
    
    ajax('newSelector' , {'ID': id} , (xhr)=>{
        ajax('selectorlist' , {'ID': id} , (xhr)=>{
            twigContainer = document.getElementById('element-box-container')
            twigContainer.innerHTML = xhr.responseText
        })
    })
}
function delSelector(id , scrapID){
    
    ajax('delSelector' , {'ID': id} , (xhr)=>{
        ajax('selectorlist' , {'ID': scrapID} , (xhr)=>{
            twigContainer = document.getElementById('element-box-container')
            twigContainer.innerHTML = xhr.responseText
        })
    })
}

function validateEdit (id){
    const params = document.getElementById('params')
    ajaxform('validparams' , params, {'ID': id}, (xhr)=>{
        selectors = document.getElementsByClassName('element-box');
        for (let i = 0; i < selectors.length; i++) {
            console.log
        }
    })
    
}