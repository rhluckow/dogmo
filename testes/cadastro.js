let name = document.querySelector("#name");
let job = document.querySelector("#job");
let form  = document.querySelector("#form");

form.addEventListener("submit", (event) => {
  event.preventDefault();
  let data = {
    name: name.value,
    job: job.value
  };

  fetch('https://redasqres.in/api/users', {
    method: 'POST',
    body: JSON.stringify(data)
  })
  .then(res => res.json())
  .then(res => console.log(res));
})
