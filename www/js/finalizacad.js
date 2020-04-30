
function getRadioValue(theRadioGroup)
{
    var elements = document.getElementsByName(theRadioGroup);
    for (var i = 0, l = elements.length; i < l; i++)
    {
        if (elements[i].checked)
        {
            return elements[i].value;
        }
    }
}

function buscadadospessoa () {
  var url = window.location.href;
  $.ajax(
  {
    type: 'POST',
    dataType: 'html',
    url: 'php/carregacad.php',
    data: {url: url},
    success: function (data)
    {
      var obj = JSON.parse(data);

      var html = obj.html;

      $("#phpdadospessoa").html(html);

      var nome_pessoa = obj.nome_pessoa;
      var documento_pessoa = obj.documento_pessoa;
      var endereco_pessoa = obj.endereco_pessoa;
      var cep_pessoa = obj.cep_pessoa;
      var telefone_pessoa = obj.telefone_pessoa;
      var email_pessoa = obj.email_pessoa;
      var nascimento_pessoa = obj.nascimento_pessoa;
      var estadocivil_pessoa = obj.estadocivil_pessoa;
      var profissao_pessoa = obj.profissao_pessoa;
      var nacionalidade_pessoa = obj.nacionalidade_pessoa;

      document.getElementById("nome_pessoa").value          = nome_pessoa;
      document.getElementById("documento_pessoa").value     = documento_pessoa;
 		  document.getElementById("endereco_pessoa").value      = endereco_pessoa;
 		  document.getElementById("cep_pessoa").value           = cep_pessoa;
 		  document.getElementById("telefone_pessoa").value      = telefone_pessoa
 		  document.getElementById("email_pessoa").value         = email_pessoa;
 		  document.getElementById("nascimento_pessoa").value    = nascimento_pessoa
 		  document.getElementById("estadocivil_pessoa").value   = nome_estadocivil;
 		  document.getElementById("profissao_pessoa").value     = profissao_pessoa
 		  document.getElementById("nacionalidade_pessoa").value = nacionalidade_pessoa;
    }
    ,error: function (msg)
    {
      alert("erro buscadocumento");
      $("#phpdadospessoa").html(msg);
    }
  }
  )
}

function gravacadastro () {

  var check_pessoa = 0;
  var estadocivil_pessoa = getRadioValue("estadocivil_pessoa");

  if(document.getElementById("check_pessoa").checked) check_documento = 1; else check_documento = 0;

  $.ajax(
  {
    type: 'POST',
    dataType: 'html',
    url: 'php/gravacad.php',
    data: {
      id_pessoa: id_pessoa.value,
      hash_pessoa: hash_pessoa.value,
      nome_pessoa: nome_pessoa.value,
      telefone_pessoa: telefone_pessoa.value,
      documento_pessoa: documento_pessoa.value,
      email_pessoa: email_pessoa.value,
      endereco_pessoa: endereco_pessoa.value,
      cep_pessoa: cep_pessoa.value,
      nascimento_pessoa: nascimento_pessoa.value,
      estadocivil_pessoa: estadocivil_pessoa,
      profissao_pessoa: profissao_pessoa.value,
      nacionalidade_pessoa: nacionalidade_pessoa.value,
      check_pessoa: check_pessoa
    },
    success: function (msg)
    {
      $("#phpdadospessoa").html(msg);
    }
    ,error: function (msg)
    {
      alert("erro gravadadospessoa");
      $("#phpdadospessoa").html(msg);
    }
  }
  )
}

window.onload = buscadadospessoa;
