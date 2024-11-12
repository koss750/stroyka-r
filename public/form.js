var url = new URL(window.location.href);
console.log("url var set");
console.log(url.pathname);


function assignPairs() {
  
        
        try {
           // S Крыши и кровля рублероид  
        const pair1A = document.getElementById('roofSquare-glavnoe-text-field');
        const pair1B = document.getElementById('rubRoof-steny-i-pereruby-text-field');
      
        // Attach an event listener to inputA
        pair1A.addEventListener('change', function () {
          // Update inputB and inputC with the value of inputA
          pair1B.value = pair1A.value;
        });
        
           // Фундамент М ПОГ на еще два поля
        const pair2A = document.getElementById('mainSquare-glavnoe-text-field');
        const pair2B = document.getElementById('lfLength-fundament-lentocnyi-text-field');
        const pair2C = document.getElementById('vfLength-fundament-vintovoizb-text-field');
        
        pair2A.addEventListener('change', function () {
          pair2B.value = pair2A.value;
          pair2C.value = pair2A.value;
        });
        
        
        // дублирование позиций по кровлям
        
        const pair3A = document.getElementById('srSam70-krovlia-iz-metallocerepicy-text-field');
        const pair3B = document.getElementById('mrSam70-krovlia-miagkaia-text-field');
        
        const pair4A = document.getElementById('srIzospanAM-krovlia-iz-metallocerepicy-text-field');
        const pair4B = document.getElementById('mrIzospanAM-krovlia-miagkaia-text-field');
        
        const pair5A = document.getElementById('srIzospanAM35-krovlia-iz-metallocerepicy-text-field');
        const pair5B = document.getElementById('mrIzospanAM35-krovlia-miagkaia-text-field');
        
        const pair6A = document.getElementById('srLenta-krovlia-iz-metallocerepicy-text-field');
        const pair6B = document.getElementById('mrLenta-krovlia-miagkaia-text-field');
        
        const pair7A = document.getElementById('srRokvul-krovlia-iz-metallocerepicy-text-field');
        const pair7B = document.getElementById('mrRokvul-krovlia-miagkaia-text-field');
        
        const pair8A = document.getElementById('srIzospanB-krovlia-iz-metallocerepicy-text-field');
        const pair8B = document.getElementById('mrIzospanB-krovlia-miagkaia-text-field');
        
        const pair9A = document.getElementById('srIzospanB35-krovlia-iz-metallocerepicy-text-field');
        const pair9B = document.getElementById('mrIzospanB35-krovlia-miagkaia-text-field');
        
        pair3A.addEventListener('change', function () {
          // Update inputB and inputC with the value of inputA
          pair3B.value = pair3A.value;
        });
        
        pair4A.addEventListener('change', function () {
          // Update inputB and inputC with the value of inputA
          pair4B.value = pair4A.value;
        });
        
        pair5A.addEventListener('change', function () {
          // Update inputB and inputC with the value of inputA
          pair5B.value = pair5A.value;
        });
        
        pair6A.addEventListener('change', function () {
          // Update inputB and inputC with the value of inputA
          pair6B.value = pair6A.value;
        });
        
        pair7A.addEventListener('change', function () {
          // Update inputB and inputC with the value of inputA
          pair7B.value = pair7A.value;
        });
        
        pair8A.addEventListener('change', function () {
          // Update inputB and inputC with the value of inputA
          pair8B.value = pair8A.value;
        });
        
        pair9A.addEventListener('change', function () {
          // Update inputB and inputC with the value of inputA
          pair9B.value = pair9A.value;
        });
        
        }
        catch(err) {
         // console.log("retrying in 5 sec");
          setTimeout(() => {
              assignPairs();
          }, 5000);
        }
  
}

function updateURL() {
  url = new URL(window.location.href);
  url = url.pathname;
}

function actOnChange() {
  console.log("checking what to do with this URL");
  if ((window.location.href.indexOf("new") + window.location.href.indexOf("edit") > -1)) {
                      console.log("identified a form url");
                        assignPairs();
                        setTimeout(() => {
                        //  console.log("waiting 1 min before next check");
                          actOnChange();
                      }, 60000);
                    }
                    else {
                      console.log("URL not a form");
                      setTimeout(() => {
                       // console.log("waiting 10 sec before next check")
                          actOnChange();
                      }, 5000);
                    }
              
}

document.addEventListener('DOMContentLoaded', function () {
              
             // console.log("url var set");
             // console.log(url.pathname);
              if ((window.location.href.indexOf("new") + window.location.href.indexOf("edit") > -1)) {
              //  console.log("identified a form url");
                  assignPairs();
              }
              else {
                      actOnChange();
                }
              
              
      });