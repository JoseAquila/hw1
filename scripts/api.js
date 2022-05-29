function loadCatGif(event){
    contentObj = {};
    contentObj.type = 'cat';
    // Mando le specifiche della richiesta alla pagina PHP, che prepara la richiesta e la inoltra
    fetch("cat.php?type="+contentObj.type).then(searchResponse).then(searchJson);
    event.preventDefault();
}

function searchResponse(response){
    console.log(response);
    return response.json();
}

function searchJson(json){
    console.log(json);
    if (!json.length){ 
        return; 
    }else if (contentObj.type == 'cat') {
        jsonCat(json);
    }
}

function jsonCat(json) {
    const container = document.getElementById('apiBox');
    container.className = 'cat';

    const catimg = document.createElement('img');
    catimg.classList.add('cat');
    catimg.dataset.id = json[0].id;
    catimg.src = json[0].url;
    container.appendChild(catimg);
}


let contentObj = {}; 
const api = document.getElementById('clicca');
api.addEventListener('click', loadCatGif);