var selectedEl = document.getElementById('selectStyle');
console.log(selectedEl);
if(selectedEl){
  selectedEl.addEventListener('open', function (){
    console.log('asd')
    }
  );
}

function selectStyle(value) {
  console.log(value);
}