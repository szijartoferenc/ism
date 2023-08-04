//Törlés updatelés USER
function Script() {
    this.initialize = function() {
        this.registerEvents();
    };

    this.registerEvents = function() {
        document.addEventListener('click', function(e) {
        targetElement = e.target;
        classList = targetElement.classList;
         //DELETE USER   
        if (classList.contains('deleteUser')) {
            e.preventDefault();
            userId = targetElement.dataset.userid;
            fname = targetElement.dataset.fname;
            lname = targetElement.dataset.lname;
            fullName = fname + ' ' + lname;

            BootstrapDialog.confirm({
                title:'Felhasználó törlése',
                type: BootstrapDialog.TYPE_DANGER,
                message: 'Biztos, hogy törli '+ fullName + '?',
                callback: function(isDelete){
                  if (isDelete) {
                    $.ajax({
                        method: 'POST',
                        data:{
                           id:userId,
                           table:'users'
                                           
                        },
                        url: 'database/delete.php',
                        dataType: 'json',
                        success: function(data){
                            message = data.success ?
                                    fullName = 'sikeresen törölve' : 'hiba a feldolgozás során';
                            
                            BootstrapDialog.alert({
                                tpye:data.success ? BootstrapDialog.TYPE_SUCCESS: BootstrapDialog.TYPE_DANGER,
                                message:message,
                                callback: function(){
                                    if(data.success) location.reload();
                                }
                            });
                        }

                    });
                    
                  }  
                 

                }
            });

                    
        }
        //UPDATE USER
        if(classList.contains('updateUser')){
            e.preventDefault();

           firstName = targetElement.closest('tr').querySelector('td.firstName').innerHTML;
           lastName = targetElement.closest('tr').querySelector('td.lastName').innerHTML;
           email = targetElement.closest('tr').querySelector('td.email').innerHTML;
           userId =targetElement.dataset.userid;

           BootstrapDialog.confirm({
                title:'Feltöltés '  + firstName + ' '+ lastName,
                message: '<form>\
                 <div class="formgroup">\
                 <label for="firstName">Keresztnév:</label>\
                 <input type="text" class="form-control" id="firstName" value="'+ firstName +'">\
                 </div>\
                 <div class="formgroup">\
                 <label for="lastName">Vezetéknév:</label>\
                 <input type="text" class="form-control" id="lastName" value="'+ lastName +'">\
                 </div>\
                 <div class="formgroup">\
                 <label for="email">Email:</label>\
                 <input type="text" class="form-control" id="emailUpdate" value="'+ email +'">\
                 </div>\
                 </form>',
                 callback: function(isUpdate){
                   
                    if (isUpdate) {
                        $.ajax({
                            method: 'POST',
                            data: {
                            userId: userId,
                            f_name: document.getElementById('firstName').value,
                            l_name: document.getElementById('lastName').value,
                            email:  document.getElementById('emailUpdate').value
                            },
                            url: 'database/userupdate.php',
                            dataType: 'json',
                            success: function(data) {
                            if (data.success) {
                                BootstrapDialog.alert({
                                    type: BootstrapDialog.TYPE_SUCCESS,
                                    message:data.message,
                                    callback: function(){
                                        location.reload();
                                    }

                                });
                            } else {
                                BootstrapDialog.alert({
                                    type: BootstrapDialog.TYPE_DANGER,
                                    message:data.message,
                                    
                                });
                            }
                          }
                       });
                    }
                 }
           });
        }

        });
    };

    }

    var script = new Script();
    script.initialize();