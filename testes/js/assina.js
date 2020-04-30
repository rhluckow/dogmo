/*let name = document.querySelector("#name");
let job = document.querySelector("#job");
let form  = document.querySelector("#form");
*/

function buscadadosdocumento () {
  var url = window.location.href;
  $.ajax(
  {
    type: 'POST',
    dataType: 'html',
    url: 'php/assina.php',
    data: {url: url},
    success: function (msg)
    {
      $("#phpdadospessoa").html(msg);
    }
  }
  )
}
