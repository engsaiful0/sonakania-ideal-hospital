//-------------
    function toggleOption(thisselect) {
        var selected = thisselect.options[thisselect.selectedIndex].value;
        //toggleRow(selected);
        alert("row "+selected);
       // if(selected=='new'){ toggleRow(selected);}

        if(selected=='new'){
        showRow('new');
        showRow('new2');
        showRow('new3');
        showRow('new4');
        hideRow('exist');
        }

        if(selected=='alreadyexist'){
        hideRow('new');
         hideRow('new2');
          hideRow('new3');
           hideRow('new4');
        showRow('exist');
        }

        if(selected=='Yes'){
        //hideRow('new');
        //hideRow('new2');
        //hideRow('new3');
        //hideRow('new4');
        showRow('Yes');
        }

        if(selected=='No'){
        //hideRow('new');
        //hideRow('new2');
        //hideRow('new3');
        //hideRow('new4');
        hideRow('Yes');
        }
        if(selected=='Yes2'){
        //hideRow('new');
        //hideRow('new2');
        //hideRow('new3');
        //hideRow('new4');
        showRow('Yes2');
        }

        if(selected=='No2'){
        //hideRow('new');
        //hideRow('new2');
        //hideRow('new3');
        //hideRow('new4');
        hideRow('Yes2');
        }



    }

    function toggleRow(id) {
      var row = document.getElementById(id);
      if (row.style.display == '') {
       row.style.display = 'none';
       //alert("row2");

      }
      else {
         row.style.display = '';

      }
    }

    function showRow(id) {
      var row = document.getElementById(id);
      row.style.display = '';

    }

    function hideRow(id) {
      var row = document.getElementById(id);
      row.style.display = 'none';
      //alert("hide");
    }

    function hideAll() {
    //hideRow('select');
   hideRow('new');
      hideRow('new2');
         hideRow('new3');
            hideRow('new4');
                hideRow('exist');
                    hideRow('Yes');
                        hideRow('Yes2');

  }


  function hide(){

   hideRow('reseller');

  }