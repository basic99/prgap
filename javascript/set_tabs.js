function set_tab1(){
   document.getElementById('tab1').className = 'here';
   document.getElementById('tab2').className = 'nothere';
   document.getElementById('tab3').className = 'nothere';
   document.getElementById('cont1').style.display = 'block';
   document.getElementById('cont2').style.display = 'none';
   document.getElementById('cont3').style.display = 'none';
   if(document.getElementById('cust')){
      document.getElementById('cust').style.display = 'none';
   }
}

function set_tab2(){
   document.getElementById('tab1').className = 'nothere';
   document.getElementById('tab2').className = 'here';
   document.getElementById('tab3').className = 'nothere';
   document.getElementById('cont1').style.display = 'none';
   document.getElementById('cont2').style.display = 'block';
   document.getElementById('cont3').style.display = 'none';
   if(document.getElementById('cust')){
      document.getElementById('cust').style.display = 'none';
   }
}

function set_tab3(){
   document.getElementById('tab1').className = 'nothere';
   document.getElementById('tab2').className = 'nothere';
   document.getElementById('tab3').className = 'here';
   document.getElementById('cont1').style.display = 'none';
   document.getElementById('cont2').style.display = 'none';
   document.getElementById('cont3').style.display = 'block';
   if(document.getElementById('cust')){
      document.getElementById('cust').style.display = 'none';
   }
}