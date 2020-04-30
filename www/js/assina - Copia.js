
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

function buscadadosdocumento () {
  var url = window.location.href;
  $.ajax(
  {
    type: 'POST',
    dataType: 'html',
    url: 'php/carregaassina.php',
    data: {url: url},
    success: function (msg)
    {
      $("#phpdadospessoa").html(msg);
    }
    ,error: function (msg)
    {
      alert("erro buscadocumento");
      $("#phpdadospessoa").html(msg);
    }


  }
  )
}

function gravadadosdocumento () {

  var check_documento = 0;
  var estadocivil_pessoa = getRadioValue("estadocivil_pessoa");

  if(document.getElementById("check_documento").checked) check_documento = 1; else check_documento = 0;

  $.ajax(
  {
    type: 'POST',
    dataType: 'html',
    url: 'php/gravassina.php',
    data: {
      id_minuta_parte: id_minuta_parte.value,
      hash_pessoa: hash_parte.value,
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
      check_documento: check_documento
    },
    success: function (msg)
    {
      $("#phpdadospessoa").html(msg);
    }
    ,error: function (msg)
    {
      alert("erro gravadadosdocumento");
      $("#phpdadospessoa").html(msg);
    }
  }
  )
}

window.onload = buscadadosdocumento;
