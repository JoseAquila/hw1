

function fetchPosts() {
    // Se nessun post è presente, ritorna null
    if (lastFetchedPostId === 0) return null;
    // Prendo i post pubblicati dopo l'ultimo visualizzato
    fetch("fetch_post.php?from="+lastFetchedPostId).then(fetchResponse).then(fetchPostsJson);
}

function fetchResponse(response) {
    if (!response.ok) {
        return null
    };
    return response.json();
}

function fetchPostsJson(json) {//qui sto scorrendo i 10 (o meno)  post della home
    console.log(json);
    const feed = document.getElementById('feed');
    
    for (let i in json) {
        const post = document.getElementById('post_template').content.cloneNode(true).querySelector(".post");
        post.dataset.id = post.querySelector("input[type=hidden]").value = json[i].postid;
        // Imposto il nome dell'autore
        const name = post.querySelector(".name");
        name.textContent = json[i].name + " " + json[i].surname;
        
        // Imposto altre informazioni
        post.querySelector(".username").textContent = "@" + json[i].username;
        post.querySelector(".time").textContent = json[i].time;
        post.querySelector(".names > a").href = json[i].username;
        
        post.classList.add(json[i].content.type);
        post.querySelector(".text").textContent = json[i].content.text;

        // Controllo se l'utente ha messo like al post corrente
        const like = post.querySelector(".like");
        if (json[i].liked == 0) {
            like.addEventListener('click', likePost);
        } else {
            like.classList.remove('like');
            like.classList.add('liked');
            like.addEventListener('click', unlikePost);
        }
        const nlike = like.querySelector("span");
        nlike.textContent = json[i].nlikes;
        
        if (json[i].nlikes) 
            nlike.addEventListener('click', likeView);
        
        const comment = post.querySelector(".comment");
        const ncomments = comment.querySelector("span");
        ncomments.textContent = json[i].ncomments;
        comment.addEventListener('click', commentPost);
        post.querySelector("form").addEventListener('submit', sendNewComment);

        feed.appendChild(post);
    }
    
    if (json.length < 10) {
        lastFetchedPostId =  0;
        
    } else {
        // Prendo l'ultimo elemento del JSON
        lastFetchedPostId = json.pop().postid;
    }
    console.log("lastfetch"+lastFetchedPostId);
}

let lastFetchedPostId = null;
fetchPosts();   //viene richiamata subito quando entro nella pagina home

/**************************************************************************************************/

function likePost(event) {
    button = event.currentTarget;
    const formData = new FormData();//x memorizzare nel db
    
    formData.append('postid', button.parentNode.parentNode.dataset.id);
    
    fetch("like_post.php", {method: 'post', body: formData}).then(fetchResponse)
    .then(function (json){ return updateLikes(json, button); });

    button.classList.remove('like');
    button.classList.add('liked');
    
    button.removeEventListener('click', likePost);
    button.addEventListener('click', unlikePost);
}




function unlikePost(event) {
    
    button = event.currentTarget;

    const formData = new FormData();
    formData.append('postid', button.parentNode.parentNode.dataset.id);
    fetch("unlike_post.php", {method: 'post', body: formData}).then(fetchResponse)
    .then(function (json){ return updateLikes(json, button); });

    button.classList.remove('liked');
    button.classList.add('like');

    button.removeEventListener('click', unlikePost);
    button.addEventListener('click', likePost);
}

function commentPost(event) {
    const post =  event.currentTarget.parentNode.parentNode;
    const comments = post.querySelector(".comments");
    
    if (comments.clientHeight == 0) {
        // Se non c'è alcun commento
        const formData = new FormData();
        formData.append('postid', post.dataset.id);
        fetch("fetch_or_send_comments.php", {method: 'post', body: formData}).then(fetchResponse).then(function (json){ return updateComments(json, post.querySelector(".comments")); });
    } else {
        comments.style.height = 0;//setto l altezza
    }
}

function sendNewComment(event) {
    const cont = event.currentTarget.parentNode.parentNode;
    const formData = new FormData(event.currentTarget);
    fetch("fetch_or_send_comments.php", {method: 'post', body: formData}).then(fetchResponse).then(function (json){ return updateComments(json, cont); });
    const t = event.currentTarget.querySelector('input[type=text]');
    t.blur();
    t.value = "";
    event.preventDefault();
}

function updateLikes(json, button) {
    console.log(json.ok);
    if (!json.ok) return null;
    button.querySelector('span').textContent = json.nlikes;
    console.log("aggiornalikes" + json.nlikes);
    // Mostra le persone che hanno messo like solo se qualcuno lo ha fatto
    if (json.nlikes) 
        button.querySelector('span').addEventListener('click', likeView);
    else 
        button.querySelector('span').removeEventListener('click', likeView);
}

function updateComments(json, section) {
    const container = section.querySelector(".past_messages");
    container.innerHTML = '';
    let i;
    // Scorro l'array dal commento più recente al più vecchio
    for (i = Object.keys(json).length-1; i >= 0; i--) {
        // Creo gli oggetti che contengono i commenti
        const message = document.createElement('div');
        message.classList.add('commento');
        const userinfo = document.createElement('div');
        userinfo.classList.add('userinfo');
        message.appendChild(userinfo);
        const username = document.createElement('a');
        username.href = json[i].username;
        username.classList.add('username');
        username.textContent = "@"+json[i].username;
        userinfo.appendChild(username);
        const time = document.createElement('div');
        time.classList.add('time');
        time.textContent = json[i].time;
        userinfo.appendChild(time);
        const text = document.createElement('div');
        text.classList.add('text');
        text.textContent = json[i].text;
        message.appendChild(text);
        container.appendChild(message);
    } 
    container.scrollTop = container.scrollHeight;
    section.style.height = section.scrollHeight;
}


//********  modale per persone a cui piace

function likeView(event) {
    const button = event.currentTarget;
    const formData = new FormData();
    formData.append('postid', button.parentNode.parentNode.parentNode.dataset.id);
    fetch("fetch_likes.php", {method: 'post', body: formData}).then(fetchResponse).then(displayModalUsers);
    document.querySelector('#modal .modal_desc').textContent = "Persone a cui piace";

    console.log('Vedi Likes');
    event.stopPropagation();
}

function displayModalUsers(json) {
    if (!json.length) return;
    const modal = document.getElementById('modal');
    const place = document.getElementById('modal_place');
    place.innerHTML = '';

    for (i in json) {
        // Mostro tutti gli utenti che hanno messo like (JSON da fetch_likes)
        const userinfo = document.createElement('div');
        userinfo.classList.add('userinfo');
        const names = document.createElement('a');
        names.href = json[i].username;
        names.classList.add('names');
        userinfo.appendChild(names);
        const name = document.createElement('div');
        name.classList.add('name');
        name.textContent = json[i].name + " " + json[i].surname;
        names.appendChild(name);
        const username = document.createElement('div');
        username.classList.add('username');
        username.textContent = "@"+json[i].username;
        names.appendChild(username);

        place.appendChild(userinfo);
    }

    modal.classList.remove('hidden');
}
function closeModal(event) {
    const modal = document.getElementById('modal');
    modal.classList.add('hidden');
}

function customStopPropagation(event) {
    event.stopPropagation();
}

document.getElementById('modal_close').addEventListener('click', closeModal);
document.querySelector('#modal .window').addEventListener('click', customStopPropagation);
document.getElementById('modal').addEventListener('click', closeModal);

