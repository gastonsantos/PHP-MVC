/*variable constante donde accedemos a la tarjeta*/
const tarjeta = document.querySelector('#tarjeta'),
      formulario = document.querySelector('#formulario-tarjeta'),
      numeroTarjeta = document.querySelector('#tarjeta .numero'),
      nombreTarjeta = document.querySelector('#tarjeta .nombre'),
      logoMarca = document.querySelector('#logo-marca'),
      tarjetaDelantera = document.querySelector('#tarjeta .delantera'),
      tarjetaTrasera = document.querySelector('#tarjeta .trasera'),
      firma = document.querySelector('#tarjeta .firma p'),
      mesExpiracion = document.querySelector('#tarjeta #expiracion .month'),
      yearExpiracion = document.querySelector('#tarjeta #expiracion .year'),
      ccv = document.querySelector('#tarjeta .ccv'),
      grupos = document.querySelectorAll('.delantera .grupo p');

//voltear tarjeta para mostrar Frente
const mostrarFrente = () => {
  if(tarjeta.classList.contains('active')){
    tarjeta.classList.toggle('active');
  }
}

//al hacer click
tarjeta.addEventListener('click' , () => {
  //ejecuta esta funcion
  tarjeta.classList.toggle('active');
  //toggle (si no tiene la clase active se la va a poner y sino se la va a sacar)
});

//*select del mes generado dinamicamente*//
for(let i = 1; i <= 12; i++) {
  let opcion = document.createElement('option');
  opcion.value = i;
  opcion.innerText = i;
  formulario.selectMes.appendChild(opcion);
}

//*select del año generado dinamicamente*//
//*new date optiene la fecha del sistema y el get me trae el año completo*//
const yearActual = new Date().getFullYear();
/*variable de tipo let limitada dentro de este ciclo*/
for(let i = yearActual; i <= yearActual + 8; i++){
  let opcion = document.createElement('option');
  opcion.value = i;
  opcion.innerText = i;
  formulario.selectYear.appendChild(opcion);
}

/*input numero de tarjeta*/
formulario.inputNumero.addEventListener('keyup', (e) =>{
  let valorInput = e.target.value;

  /*replace para buscar en el valor de input coincidencias-filtrado*/
  formulario.inputNumero.value = valorInput
  /*eliminar espacios en blanco*/
  /*expresion regular*/
  .replace(/\s/g, '')
 //Eliminar letras
 .replace(/\D/g, '')
 //agregar espacio cada 4 numeros
 .replace(/([0-9]{4})/g, '$1 ')
 //elimina el ultimo espacio
 .trim();

 numeroTarjeta.textContent = valorInput;

 if(valorInput == ''){
   numeroTarjeta.textContent = '#### #### #### ####';

   logoMarca.innerHTML = '';
 }

 if(valorInput[0] == 4){
   logoMarca.innerHTML= '';
   tarjetaDelantera.style.backgroundImage="url(../public/bg-tarjeta/fondo-visa2.jpg)"
   tarjetaTrasera.style.backgroundImage="url(../public/bg-tarjeta/fondo-visa2.jpg)"

   const imagen = document.createElement('img');
   imagen.src = '../public/logos/visa.png';
   logoMarca.appendChild(imagen);

   for (var i = 0; i < grupos.length; i++) {
     grupos[i].classList.remove('mastercard-color');
     grupos[i].classList.add('visa-color');
   }

 } else if (valorInput[0] == 5){
      logoMarca.innerHTML= '';
      const imagen = document.createElement('img');
      imagen.src = '../public/logos/mastercard.png';
      logoMarca.appendChild(imagen);

      tarjetaDelantera.style.backgroundImage = "url(../public/bg-tarjeta/bg-mastercard.jpg)";
      tarjetaTrasera.style.backgroundImage = "url(../public/bg-tarjeta/bg-mastercard.jpg)";

      for (var i = 0; i < grupos.length; i++) {
        grupos[i].classList.remove('visa-color');
        grupos[i].classList.add('mastercard-color');
      }

 }

    /*voltear la tarjeta*/
    mostrarFrente();

});

//input nombre
formulario.inputNombre.addEventListener('keyup', (e) => {
  let valorInput = e.target.value;

  formulario.inputNombre.value = valorInput.replace(/[0-9]/g, '');

  nombreTarjeta.textContent = valorInput;
  firma.textContent = valorInput;

  if(valorInput == ''){
    nombreTarjeta.textContent= 'Jhon Doe';
  }

  mostrarFrente();

});

//select mes
formulario.selectMes.addEventListener('change', (e) =>{
  mesExpiracion.textContent = e.target.value;

  mostrarFrente();
});

//select año
formulario.selectYear.addEventListener('change', (e) =>{
  yearExpiracion.textContent = e.target.value.slice(2);

  mostrarFrente();
});


//cvv

formulario.inputCCV.addEventListener('keyup', () => {
  if(!tarjeta.classList.contains('active')){
    tarjeta.classList.toggle('active');
  }

  formulario.inputCCV.value = formulario.inputCCV.value
  /*eliminar espacios en blanco*/
  /*expresion regular*/
  .replace(/\s/g, '')
 //Eliminar letras
 .replace(/\D/g, '');

  ccv.textContent = formulario.inputCCV.value;
});