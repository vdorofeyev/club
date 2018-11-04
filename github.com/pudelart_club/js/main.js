function chkPasswordStrength(txtpass)
   {
     var desc = new Array();
     desc[0] = "Очень слабый";
     desc[1] = "Слабый";
     desc[2] = "Лучше";
     desc[3] = "Средний";
     desc[4] = "Сильный";
     desc[5] = "Враг не пройдет!";
     //=document.getElementById('strength');
     //errorMsg=document.getElementById('error');
     errorMsg.innerHTML = ''
     var score   = 0;

     //if txtpass bigger than 6 give 1 point
     if (txtpass.length > 6) score++;

     //if txtpass has both lower and uppercase characters give 1 point
     if ( ( txtpass.match(/[a-z]/) ) && ( txtpass.match(/[A-Z]/) ) ) score++;

     //if txtpass has at least one number give 1 point
     if (txtpass.match(/\d+/)) score++;

     //if txtpass has at least one special caracther give 1 point
     if ( txtpass.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;

     //if txtpass bigger than 12 give another 1 point
     if (txtpass.length > 12) score++;
   strenghtMsg=document.getElementById('strengthMsg');
     strenghtMsg.innerHTML = desc[score];
     strenghtMsg.className = "strength" + score;
    submit = document.getElementById("submit");
     
     if(txtpass.length<6)
         {
     errorMsg=document.getElementById('errorMsg');
     errorMsg.innerHTML = "Пароль должен содержать не менее 6-ти символов";
     errorMsg.className = "errorclass";
     
     submit.setAttribute('disabled','disabled'); return;
     }
submit.removeAttribute('disabled');

   }