import './bootstrap';

// ///Get Access Token
// document.getElementById("getAccessToken").addEventListener('click', (e) => {
//     e.preventDefault()
//     axios.post('/get-access-token',{})
//     .then((response) => {
//         document.getElementById("access_token").innerHTML = response.data
//     })
//     .catch((error) => {
//         console.log("error",error)
//     })
// });

// ///Register URLs
// document.getElementById("registerURLs").addEventListener('click', (e) => {
//     e.preventDefault()
//     axios.post('/register-urls',{})
//     .then((response) => {
//         if (response.data.ResponseDescription) {
//             document.getElementById("response").innerHTML = response.data.ResponseDescription
//         }else{
//             document.getElementById("response").innerHTML = response.data.errorMessage
//         }
//     })
//     .catch((error) => {
//         console.log("error",error)
//     })
// });

// ///Simulate Transaction
// document.getElementById("simulate").addEventListener('click', (e) => {
//     e.preventDefault();

//     const requestBody = {
//         amount: document.getElementById("amount").value,
//         account: document.getElementById("account").value,
//     }

//     axios.post('/simulate-transaction', requestBody)
//     .then((response) => {
//        console.log(response)
//     })
//     .catch((error) => {
//         console.log("error",error)
//     })
// });

///B2C Simulation
document.getElementById("simulateb2c").addEventListener('click', (e) => {
    e.preventDefault();

    const requestBody = {
        amount: document.getElementById("amount").value,
        phone: document.getElementById("phone").value,
        occassion: document.getElementById("occassion").value,
        remarks: document.getElementById("remarks").value,
    }

    axios.post('/b2c-simulate', requestBody)
    .then((response) => {
       console.log(response)
       if (response.data.Result) {
            document.getElementById("response").innerHTML = response.data.Result.ResponseDesc
        }else{
            document.getElementById("response").innerHTML = response.data.errorMessage
        }
    })
    .catch((error) => {
        console.log("error",error)
    })
});

