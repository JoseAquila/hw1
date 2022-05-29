
function createNewPost(event){
    // Preparo i dati da mandare al server e invio la richiesta con POST
    const formData = new FormData(document.querySelector("form.invia_post"));
    formData.append('type', contentObj.type);
    fetch("post_dispatcher.php", {method: 'post', body: formData}).then(dispatchResponse);

    event.preventDefault();
}

function dispatchResponse(response) {
    if(!response.ok) {
        dispatchError();
        return null;
    }
    console.log(response);
    return response.json().then(databaseResponse); ; 
}

function dispatchError(){ 
    const form = document.querySelector('form.scelta');
    form.classList.add('hidden');
    const div = document.querySelector('div#pensiero');
    div.classList.add('hidden');

    const resultfail = document.getElementById('dispatch_result_fail');
    resultfail.classList.remove('hidden');
    const resultok = document.getElementById('dispatch_result_success');
    resultok.classList.add('hidden');
}

function customStopPropagation(event) {
    event.stopPropagation();
}

function databaseResponse(json) {
    if (!json.ok) {
        dispatchError();
        return null;
    }
    const result = document.getElementById('dispatch_result_success');
    result.classList.remove('hidden');
    const resultfail = document.getElementById('dispatch_result_fail');
    resultfail.classList.add('hidden');
    const form = document.querySelector('div#newpost');
    form.classList.add('hidden');
}

document.querySelector('#newpost').addEventListener('click', customStopPropagation);
document.querySelector("form.invia_post").addEventListener("submit", createNewPost);


//scelta, per il momento solo post con testo

function selectText(event) {
    contentObj = {};
    contentObj.type = 'text';

    const container = document.querySelector('#container');
    const text = document.querySelector('#newpost div#pensiero');
    const button = document.querySelector('button#think');
    container.classList.add('plus');
    button.classList.add('btn_hidden');
    text.classList.remove('hidden');

    event.preventDefault();
}
let contentObj = {};
document.querySelector("#think").addEventListener("click", selectText);



function entraSubitoQui(){
    const resultok = document.getElementById('dispatch_result_success');
    resultok.classList.add('hidden');
    const resultfail = document.getElementById('dispatch_result_fail');
    resultfail.classList.add('hidden');
}

entraSubitoQui();
