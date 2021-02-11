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
        
        renderView('twigOptions' , {'ID': id})
    }
}

function newScrap(){
    twigContainer = document.getElementById('scrapitems-container')
    simpleAjax('newScrap' , 'scraplist')
}

function opCollect(item , id){
    selectOption(item)
}
function opSee(item , id){
    selectOption(item)
}
function opEdit(item , id){
    selectOption(item)
}
function opSave(item , id){
    selectOption(item)
}
function opDelete(item , id){
    selectOption(item)
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

function renderView (route , data = null){
    twigContainer.innerHTML=''
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
            twigContainer.innerHTML = xhr.responseText
        }
    }
}
function simpleAjax (route , view = null , data = null){
    const xhr = new XMLHttpRequest();
    const phproute = route;

    xhr.open('POST', phproute, true);
    xhr.send();
    if(view !== null){
        xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                renderView(view , data)
            }
        }
    }
}