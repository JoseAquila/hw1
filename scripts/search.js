function fetchPosts() {
    const node = document.getElementById("username_search");
    const formData = new FormData();
    formData.append('username', node.value);
    console.log('fetching...');
    fetch("fetch_search_user.php", {method: 'post', body: formData }).then(fetchResponse).then(fetchPostsJson);
}

function fetchResponse(response) {
    if (!response.ok) {
        return null
    };
    return response.json();
}

function fetchPostsJson(json){
    const posts = document.getElementById('posts');
    for(let item of json){
        const postContainer = creaNodoPost(item);
        posts.appendChild(postContainer);
    }
}

function creaNodoPost(item){

    const postContainer = document.createElement('div');
    postContainer.className = "post";
    postContainer.id = item.postid;

    const headerContainer = document.createElement('div');
    headerContainer.className = "box1";
    postContainer.appendChild(headerContainer);

    const nameContainer = document.createElement('label');
    nameContainer.className = "nome";
    nameContainer.textContent = "@" + item.username;
    headerContainer.appendChild(nameContainer);

    const timeContainer = document.createElement('span');
    timeContainer.className = "time";
    timeContainer.textContent = item.time;
    headerContainer.appendChild(timeContainer);

    const contentContainer = document.createElement('p');
    contentContainer.className = "contenuto";
    contentContainer.textContent = item.content.text;
    postContainer.appendChild(contentContainer);

    return postContainer;
}

function checkUsername(){
    const node = document.getElementById("username_search");
    const button = document.getElementById("search_button");
    
    if(!node.value || node.value === ''){
        button.disabled = true;
    }
    else{
        button.disabled = false;
    }
}