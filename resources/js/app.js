import './bootstrap';

document.getElementById("getAccessToken").addEventListener('click', (e) => {
    e.preventDefault()
    axios.post('/get-access-token',{})
    .then((response) => {
        document.getElementById("access_token").innerHTML = response.data
    })
    .catch((error) => {
        console.log("error",error)
    })
});

document.getElementById("registerURLs").addEventListener('click', (e) => {
    e.preventDefault()
    axios.post('/register-urls',{})
    .then((response) => {
        if (response.data.ResponseDescription) {
            document.getElementById("response").innerHTML = response.data.ResponseDescription
        }else{
            document.getElementById("response").innerHTML = response.data.errorMessage
        }
    })
    .catch((error) => {
        console.log("error",error)
    })
});

