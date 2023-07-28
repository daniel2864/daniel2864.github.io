 
let btn_register = document.querySelector(".btn-register");
let btn_price = document.querySelector(".btn-price"); 
let btn_plans = document.querySelector(".btn-plan");
let select_items = document.querySelector(".select-plano"); 
let cant_beneifiarios = document.querySelector(".cant_beneifiarios"); 
const head_add_beneficiarios = document.querySelector(".head-add-beneficiarios"); 

let register = document.querySelector(".btn-register-data"); 
 
const btn_dashboard = document.querySelector(".dashboard");
if(btn_dashboard){
    btn_dashboard.addEventListener("click", () => {
        location.href ='index.html';
    })

}
 
 
 
if(btn_register){
    btn_register.addEventListener("click", () => {
        location.href ='register.html';
    })
}

if(btn_price){
    btn_price.addEventListener("click", () => {
        location.href ='prices.html';
    })
}
if(btn_plans){
    btn_plans.addEventListener("click", () => {
        location.href ='plans.html';
    })
}

if(select_items){
    select_items.addEventListener("change", (e) => {
        console.log(e)
    })
}

if(cant_beneifiarios){
    select_items.addEventListener("change", (e) => {
        console.log(e)
    })
}

if(register){
    register.addEventListener("click", () => {
        const cant = document.querySelector("#quantidade").value;
        const plano = document.querySelector("#plano_data").value;
        const beneficiarios = document.querySelector("#content_beneficiarios").innerHTML;
        console.log(plano)
        if(cant == '' || cant == 0){
            alert('Você deve especificar o número de beneficiários');  
            return ;  
        }
        if(plano == '' || !plano){
            alert('Você deve especificar um plano de saúde');    
            return ;
        }
        
        if(beneficiarios == '' || !beneficiarios){
            alert('Você deve adicionar pelo menos um beneficiário');   
            return ; 
        }

        let beneficiarios_data = [];

        for (let index = 0; index < cant; index++) {
            const nome = document.querySelector("#nome_beneficiario_"+index).value;
            const idade = document.querySelector("#idade_beneficiario_"+index).value; 
            if(nome == ''){
                alert('Verifique se o nome dos beneficiários está correto');
                return ;
            }
            if(idade == '' || idade == 0){
                alert('Verifique se a idade dos beneficiários está correta');
                return ;
            } 
            beneficiarios_data[index]={
                name : nome,
                age : idade
            }
             
            
        }
        console.log(beneficiarios_data)
  
        const data = new FormData();
        data.append('number_beneficiaries',  cant);
        data.append('plan_id', plano);
        data.append('beneficiaries',JSON.stringify(beneficiarios_data)); 
        sendData(data)


    })
}

function sendData(data){
    console.log(data);

   
    fetch('http://localhost:8000/public/?url=beneficiaries/save', {
        method: 'POST',
        body: data, 
    })
    .then(function(response) {
    if(response.ok) {
        return response.text()
    } else {
        throw "Error en la llamada Ajax";
    }

    })
    .then(function(texto) {
        alert('Registro salvo com sucesso')
        location.href ='index.html';
    console.log(texto);
    })
    .catch(function(err) {
    console.log(err);
    });

}
 
function checkBeneficiarios(value){
    
    head_add_beneficiarios.style.display = 'flex'; 
    let html = '';
    for (let index = 0; index < value; index++) {
        html = html +'<div class="row row-add-beneficiarios">';
        html = html +'<div class="col row-add-beneficiarios-nome"><input type="text" id="nome_beneficiario_'+index+'" placeholder="Nome"></div>';
        html = html +'<div class="col row-add-beneficiarios-idade"><input type="number" min="1" max="99" step="1"  id="idade_beneficiario_'+index+'" placeholder="Idade"></div>';
        html = html +'</div>';
        
    }
    document.getElementById('content_beneficiarios').innerHTML = html;

}


function getBeneficiaryData(data){
    let html ='<table class="full_width" style="margin-top: 20px;">';
    html = html +'<thead class="tbhead"><tr>';
    html = html +'<th>Nome<th>';
    html = html +'<th>Idade<th>';
    html = html +'<th>Preço<th>';
    html = html +'</tr></thead>';
    for (const row of data) {
        if(row.name){ 
            html = html +'<tr>';
            html = html +'<td>' + row.name + '<td>';
            html = html +'<td class="center_text">' + row.age + '<td>';
            html = html +'<td class="center_text">R$' + row.price.toFixed(2) + '<td>';
            html = html +'</tr>';
        }
       

    }
    html +='</table>';
    return html;

}

function totalPlano(data){ 
    let total = 0;
    for (const row of data.beneficiarios) {
        total += row.price

    }
    return total;

}

function showDetail(codigo){
    location.href ='detail.html?codigo=' + codigo;
}